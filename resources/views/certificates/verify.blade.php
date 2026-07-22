<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>সার্টিফিকেট ভেরিফিকেশন</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-lg border border-emerald-200 p-8 text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-4">✓</div>
        <h1 class="text-xl font-extrabold text-gray-900 mb-1">সার্টিফিকেট বৈধ</h1>
        <p class="text-sm text-gray-500 mb-6">এই সার্টিফিকেটটি আমাদের সিস্টেমে যাচাইকৃত।</p>

        <div class="text-left bg-gray-50 rounded-xl p-5 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">নাম:</span><span class="font-bold">{{ $certificate->user->name }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">কোর্স:</span><span class="font-bold">{{ $certificate->course->title }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">ইস্যুর তারিখ:</span><span class="font-bold">{{ $certificate->issued_at->format('d M, Y') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">সার্টিফিকেট নং:</span><span class="font-bold font-mono">{{ $certificate->certificate_number }}</span></div>
        </div>
    </div>
</body>
</html>
