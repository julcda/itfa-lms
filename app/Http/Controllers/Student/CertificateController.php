<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::where('user_id', auth()->id())
            ->with('course')
            ->latest()
            ->paginate(12);
        return view('student.certificates.index', compact('certificates'));
    }

    public function download(Certificate $certificate)
    {
        abort_if($certificate->user_id !== auth()->id(), 403);
        if (!$certificate->file_path || !Storage::disk('public')->exists($certificate->file_path)) {
            return back()->with('error', 'Certificate file not available.');
        }
        return Storage::disk('public')->download(
            $certificate->file_path,
            'certificate_' . $certificate->certificate_number . '.pdf'
        );
    }
}
