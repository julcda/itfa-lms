<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate - {{ $certificate->certificate_number }}</title>
    <style>
        @page { margin: 0; size: A4 landscape; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            width: 297mm; height: 210mm;
            font-family: DejaVu Sans, sans-serif;
            background: #fff;
            overflow: hidden;
        }
        .outer-border {
            position: absolute; top: 10mm; left: 10mm;
            right: 10mm; bottom: 10mm;
            border: 4px solid #065f46;
            border-radius: 8px;
        }
        .inner-border {
            position: absolute; top: 14mm; left: 14mm;
            right: 14mm; bottom: 14mm;
            border: 1.5px solid #6ee7b7;
            border-radius: 6px;
        }
        .header-band {
            position: absolute; top: 18mm; left: 18mm; right: 18mm;
            background: #065f46;
            padding: 8mm 12mm;
            border-radius: 4px 4px 0 0;
        }
        .org-name {
            color: #fff; font-size: 22px; font-weight: bold;
            text-align: center; letter-spacing: 2px;
        }
        .org-sub {
            color: #6ee7b7; font-size: 11px;
            text-align: center; letter-spacing: 1px; margin-top: 2px;
        }
        .body-area {
            position: absolute; top: 54mm; left: 18mm; right: 18mm; bottom: 18mm;
            display: flex; flex-direction: column; align-items: center;
            padding: 6mm 12mm;
        }
        .cert-title {
            font-size: 32px; font-weight: bold;
            color: #065f46; text-align: center;
            letter-spacing: 4px; text-transform: uppercase;
            margin-bottom: 4mm;
        }
        .cert-subtitle {
            font-size: 11px; color: #6b7280;
            text-align: center; letter-spacing: 2px;
            text-transform: uppercase; margin-bottom: 8mm;
        }
        .awarded-to { font-size: 11px; color: #6b7280; text-align: center; }
        .student-name {
            font-size: 28px; font-weight: bold; color: #1f2937;
            text-align: center; border-bottom: 2px solid #065f46;
            padding-bottom: 2mm; margin: 3mm 0 5mm 0;
            min-width: 120mm;
        }
        .completion-text { font-size: 12px; color: #374151; text-align: center; line-height: 1.6; }
        .course-name {
            font-size: 16px; font-weight: bold; color: #065f46;
            text-align: center; margin: 3mm 0;
        }
        .details-row {
            display: flex; justify-content: center; gap: 20mm;
            margin-top: 8mm; width: 100%;
        }
        .detail-item { text-align: center; }
        .detail-label { font-size: 9px; color: #9ca3af; letter-spacing: 1px; text-transform: uppercase; }
        .detail-value { font-size: 11px; color: #1f2937; font-weight: bold; margin-top: 1mm; }
        .seal {
            position: absolute; bottom: 22mm; right: 28mm;
            width: 25mm; height: 25mm;
            background: #065f46;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
        .seal-text { color: #fff; font-size: 7px; font-weight: bold; text-align: center; letter-spacing: 0.5px; }
        .corner { position: absolute; width: 8mm; height: 8mm; }
        .corner-tl { top: 16mm; left: 16mm; border-top: 2px solid #6ee7b7; border-left: 2px solid #6ee7b7; }
        .corner-tr { top: 16mm; right: 16mm; border-top: 2px solid #6ee7b7; border-right: 2px solid #6ee7b7; }
        .corner-bl { bottom: 16mm; left: 16mm; border-bottom: 2px solid #6ee7b7; border-left: 2px solid #6ee7b7; }
        .corner-br { bottom: 16mm; right: 16mm; border-bottom: 2px solid #6ee7b7; border-right: 2px solid #6ee7b7; }
    </style>
</head>
<body>
    <div class="outer-border"></div>
    <div class="inner-border"></div>
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>

    <div class="header-band">
        <div class="org-name">Ibn Taimiyah Foundation Academy</div>
        <div class="org-sub">أكاديمية مؤسسة ابن تيمية التعليمية</div>
    </div>

    <div class="body-area">
        <div class="cert-title">Certificate</div>
        <div class="cert-subtitle">of Completion</div>
        <div class="awarded-to">This certificate is proudly awarded to</div>
        <div class="student-name">{{ $certificate->user->display_name ?? $certificate->user->name }}</div>
        <div class="completion-text">
            for successfully completing the course
        </div>
        <div class="course-name">{{ $certificate->course->title ?? 'Course Title' }}</div>
        @if($certificate->course->title_ar)
        <div style="font-size:12px; color:#065f46; text-align:center; margin-bottom:2mm;" dir="rtl">{{ $certificate->course->title_ar }}</div>
        @endif

        <div class="details-row">
            <div class="detail-item">
                <div class="detail-label">Date Issued</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($certificate->issued_at)->format('d F Y') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Certificate No.</div>
                <div class="detail-value">{{ $certificate->certificate_number }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Instructor</div>
                <div class="detail-value">{{ $certificate->course->teacher->name ?? 'Instructor' }}</div>
            </div>
        </div>
    </div>

    <div class="seal">
        <div class="seal-text">ITFA<br>OFFICIAL<br>SEAL</div>
    </div>
</body>
</html>
