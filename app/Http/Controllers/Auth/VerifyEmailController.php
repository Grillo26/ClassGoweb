<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->to($request->user()->redirect_after_login.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            // Si el usuario es tutor, asignar todos los cursos existentes
            $user = $request->user();
            if ($user->roles()->where('name', 'tutor')->exists()) {
                $courses = \App\Models\CompanyCourse::all();
                foreach ($courses as $course) {
                    // Solo crear la relación si no existe
                    if (!$course->users()->where('user_id', $user->id)->exists()) {
                        $course->users()->attach($user->id, ['status' => 'pending']);
                    }
                }
            }
        }

        return redirect()->to($request->user()->redirect_after_login.'?verified=1');
    }
}
