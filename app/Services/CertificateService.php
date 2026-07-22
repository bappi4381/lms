<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CertificateService
{
    /**
     * If the user has completed every lesson in the course, issue a
     * certificate (idempotent — returns the existing one if already issued).
     */
    public function issueIfEligible(User $user, Course $course): ?Certificate
    {
        $existing = Certificate::where('user_id', $user->id)->where('course_id', $course->id)->first();

        if ($existing) {
            return $existing;
        }

        if (! $this->hasCompletedCourse($user, $course)) {
            return null;
        }

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_number' => $this->generateCertificateNumber(),
            'issued_at' => now(),
        ]);

        $certificate->update(['pdf_path' => $this->generatePdf($certificate)]);

        return $certificate;
    }

    public function hasCompletedCourse(User $user, Course $course): bool
    {
        $lessonIds = $course->modules->flatMap->lessons->pluck('id');

        if ($lessonIds->isEmpty()) {
            return false;
        }

        $completedCount = \App\Models\LessonProgress::where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('is_completed', true)
            ->count();

        return $completedCount >= $lessonIds->count();
    }

    protected function generateCertificateNumber(): string
    {
        return 'CERT-'.now()->format('Y').'-'.Str::upper(Str::random(8));
    }

    protected function generatePdf(Certificate $certificate): string
    {
        $certificate->loadMissing('user', 'course');

        $pdf = Pdf::loadView('certificates.pdf', ['certificate' => $certificate]);

        $path = "certificates/{$certificate->certificate_number}.pdf";
        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}
