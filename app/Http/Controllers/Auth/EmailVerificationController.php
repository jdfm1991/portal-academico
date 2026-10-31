<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailVerificationController extends Controller
{
    // Muestra la página "revisa tu correo"
    public function notice()
    {
        return Inertia::render('Auth/VerifyEmail');
    }

    // Procesa el clic en el enlace del email
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/mi-cuenta/cursos');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended('/mi-cuenta/cursos')
            ->with('status', 'email-verified');
    }

    // Reenvía el email de verificación
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/mi-cuenta/cursos');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
