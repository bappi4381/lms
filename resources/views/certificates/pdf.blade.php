<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 0;
            margin: 0;
        }
        .wrapper {
            border: 12px solid #4f46e5;
            padding: 60px 50px;
            text-align: center;
            height: 700px;
        }
        .brand {
            font-size: 14px;
            letter-spacing: 3px;
            color: #6366f1;
            text-transform: uppercase;
            font-weight: bold;
        }
        h1 {
            font-size: 34px;
            color: #111827;
            margin: 20px 0 5px;
        }
        .subtitle {
            font-size: 16px;
            color: #6b7280;
        }
        .name {
            font-size: 30px;
            font-weight: bold;
            color: #4f46e5;
            margin: 30px 0 10px;
            border-bottom: 2px solid #e5e7eb;
            display: inline-block;
            padding-bottom: 10px;
        }
        .course {
            font-size: 20px;
            color: #111827;
            margin: 20px 0;
            font-weight: bold;
        }
        .meta {
            margin-top: 50px;
            font-size: 12px;
            color: #9ca3af;
        }
        .cert-number {
            margin-top: 10px;
            font-size: 12px;
            color: #6b7280;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="brand">Certificate of Completion</div>
        <h1>সাফল্যের সনদ</h1>
        <p class="subtitle">এই সার্টিফিকেটটি প্রদান করা হচ্ছে</p>

        <div class="name">{{ $certificate->user->name }}</div>

        <p class="subtitle">সফলভাবে সম্পন্ন করার জন্য কোর্স</p>
        <div class="course">{{ $certificate->course->title }}</div>

        <div class="meta">
            ইস্যুর তারিখ: {{ $certificate->issued_at->format('d F, Y') }}
        </div>
        <div class="cert-number">
            সার্টিফিকেট নম্বর: {{ $certificate->certificate_number }}
        </div>
    </div>
</body>
</html>
