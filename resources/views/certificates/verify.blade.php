<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>সার্টিফিকেট ভেরিফিকেশন</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-neu-base min-h-screen flex items-center justify-center p-6">
    <div class="max-w-lg w-full neu-raised-lg rounded-md-lg p-8 text-center">
        <div class="w-16 h-16 rounded-full neu-inset-sm text-neu-heading flex items-center justify-center text-3xl mx-auto mb-4">✓</div>
        <h1 class="text-xl font-extrabold text-neu-heading mb-1">সার্টিফিকেট বৈধ</h1>
        <p class="text-sm text-neu-muted mb-6">এই সার্টিফিকেটটি আমাদের সিস্টেমে যাচাইকৃত।</p>

        <div class="text-left neu-inset-sm rounded-md-md p-5 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-neu-muted">নাম:</span><span class="font-bold text-neu-heading">{{ $certificate->user->name }}</span></div>
            <div class="flex justify-between"><span class="text-neu-muted">কোর্স:</span><span class="font-bold text-neu-heading">{{ $certificate->course->title }}</span></div>
            <div class="flex justify-between"><span class="text-neu-muted">ইস্যুর তারিখ:</span><span class="font-bold text-neu-heading">{{ $certificate->issued_at->format('d M, Y') }}</span></div>
            <div class="flex justify-between"><span class="text-neu-muted">সার্টিফিকেট নং:</span><span class="font-bold font-mono text-neu-heading">{{ $certificate->certificate_number }}</span></div>
        </div>
    </div>
</body>
</html>
