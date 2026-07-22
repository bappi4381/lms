<?php

return [
    'library_id' => env('BUNNY_LIBRARY_ID', env('Video_library_ID')),
    'api_key' => env('BUNNY_API_KEY', env('API_key')),
    'token_key' => env('BUNNY_TOKEN_KEY'),

    // Signed URL validity window. Kept short (5-10 minutes) so a leaked
    // link can't be reused/redistributed for long — this is the core of
    // our "don't let paid videos get pirated" defense.
    'token_expiry_seconds' => env('BUNNY_TOKEN_EXPIRY_SECONDS', 600),
];
