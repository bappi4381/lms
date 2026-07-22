<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function index(): View
    {
        $certificates = auth()->user()->certificates()->with('course')->latest('issued_at')->get();

        return view('profile.certificates', compact('certificates'));
    }

    public function download(Certificate $certificate): Response
    {
        abort_unless($certificate->user_id === auth()->id(), 403);
        abort_unless($certificate->pdf_path && Storage::disk('public')->exists($certificate->pdf_path), 404);

        return Storage::disk('public')->download($certificate->pdf_path, "{$certificate->certificate_number}.pdf");
    }

    /**
     * Public certificate verification page — anyone with the certificate
     * number can confirm it's genuine (no auth required).
     */
    public function verify(string $certificateNumber): View
    {
        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with('user', 'course')
            ->firstOrFail();

        return view('certificates.verify', compact('certificate'));
    }
}
