<?php

namespace App\Services;

class BunnyStreamService
{
    /**
     * Build a short-lived, signed Bunny Stream iframe URL for the given video.
     * The token expires after `bunny.token_expiry_seconds` (5-10 minutes by
     * default) so a copied/leaked link cannot be replayed indefinitely.
     */
    public function signedEmbedUrl(string $videoId): string
    {
        $libraryId = config('bunny.library_id');
        $tokenKey = config('bunny.token_key');

        $baseUrl = "https://iframe.mediadelivery.net/embed/{$libraryId}/{$videoId}?autoplay=false&loop=false&muted=false&preload=true&responsive=true";

        if (! $tokenKey) {
            return $baseUrl;
        }

        $expires = time() + (int) config('bunny.token_expiry_seconds', 600);
        $signature = hash('sha256', $tokenKey.$videoId.$expires);

        return "{$baseUrl}&token={$signature}&expires={$expires}";
    }
}
