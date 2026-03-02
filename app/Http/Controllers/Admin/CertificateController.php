<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $certificates = Certificate::with(['user', 'course'])
            ->latest()->paginate(15)->withQueryString();
        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        $courses = Course::where('status', 'published')->get();
        $students = User::role('student')->get();
        return view('admin.certificates.create', compact('courses', 'students'));
    }

    public function store(Request $request)
    {
        return $this->generate($request, User::findOrFail($request->user_id), Course::findOrFail($request->course_id));
    }

    public function generate(Request $request, User $user, Course $course)
    {
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)->first();

        if (!$enrollment) {
            return back()->with('error', 'User is not enrolled in this course.');
        }

        $certificate = Certificate::updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['certificate_number' => Certificate::generateNumber(), 'issued_at' => now()]
        );

        $pdf = Pdf::loadView('admin.certificates.pdf', compact('certificate', 'user', 'course'));
        $filename = 'certificates/certificate_' . $certificate->certificate_number . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());
        $certificate->update(['file_path' => $filename]);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate generated successfully.');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load('user', 'course');
        return view('admin.certificates.show', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        if (!$certificate->file_path || !Storage::disk('public')->exists($certificate->file_path)) {
            return back()->with('error', 'Certificate file not found.');
        }
        return Storage::disk('public')->download($certificate->file_path,
            'certificate_' . $certificate->certificate_number . '.pdf');
    }

    public function destroy(Certificate $certificate)
    {
        if ($certificate->file_path) {
            Storage::disk('public')->delete($certificate->file_path);
        }
        $certificate->delete();
        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate deleted.');
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.certificates.show', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        return back();
    }
}
