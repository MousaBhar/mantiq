<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function index(Request $request): View
    {
        return view('certificates.index', [
            'certificates' => $request->user()
                ->certificates()
                ->with('content:id,title')
                ->latest('issued_at')
                ->get(),
        ]);
    }

    public function show(Request $request, Certificate $certificate): View
    {
        abort_unless(
            $certificate->user_id === $request->user()->id || $request->user()->isAdmin(),
            403
        );

        $certificate->load(['user:id,name', 'content:id,title']);

        return view('certificates.show', ['certificate' => $certificate]);
    }

    /**
     * Public verification page: confirms a certificate code is genuine.
     */
    public function verify(string $code): View
    {
        $certificate = Certificate::with(['user:id,name', 'content:id,title'])
            ->where('code', strtoupper(trim($code)))
            ->first();

        return view('certificates.verify', [
            'certificate' => $certificate,
            'code' => $code,
        ]);
    }
}
