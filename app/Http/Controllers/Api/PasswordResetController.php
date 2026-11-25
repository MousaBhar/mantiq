<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Step 1: Request a password reset OTP.
     */
    public function requestOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => true,
                'message' => 'If the email exists, a verification code will be sent.',
            ], 200);
        }

        // Generate a secure 6-digit OTP.
        $otp = (string) random_int(100000, 999999);

        // Store the hashed OTP and reset previous verification data.
        $user->passwordResetOtp()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp_hash' => Hash::make($otp),
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
                'consumed_at' => null,
                'verified_at' => null,
                'reset_token_hash' => null,
                'reset_token_expires_at' => null,
            ]
        );

        // Send the OTP to the user's email.
        Mail::raw(
            "Your password reset verification code is: {$otp}",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Password Reset Verification Code');
            }
        );

        return response()->json([
            'success' => true,
            'message' => 'If the email exists, a verification code will be sent.',
        ], 200);
    }

    /**
     * Step 2: Verify the OTP and generate a reset token.
     */
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'otp' => ['required', 'string', 'digits:6'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        $otpRecord = $user?->passwordResetOtp;

        if (
            !$otpRecord ||
            $otpRecord->consumed_at !== null ||
            $otpRecord->expires_at->isPast()
        ) {
            throw ValidationException::withMessages([
                'otp' => ['The verification code is invalid or expired.'],
            ]);
        }

        if ($otpRecord->attempts >= 5) {
            throw ValidationException::withMessages([
                'otp' => ['Too many attempts. Request a new verification code.'],
            ]);
        }

        // Check whether the submitted OTP matches the stored hash.
        if (!Hash::check($validated['otp'], $otpRecord->otp_hash)) {
            $otpRecord->increment('attempts');

            if ($otpRecord->attempts >= 5) {
                throw ValidationException::withMessages([
                    'otp' => ['Too many attempts. Request a new verification code.'],
                ]);
            }

            throw ValidationException::withMessages([
                'otp' => ['The verification code is incorrect.'],
            ]);
        }

        // Generate a secure temporary reset token.
        $resetToken = Str::random(64);

        // Mark the OTP as consumed and store the hashed reset token.
        $otpRecord->update([
            'verified_at' => now(),
            'consumed_at' => now(),
            'reset_token_hash' => Hash::make($resetToken),
            'reset_token_expires_at' => now()->addMinutes(10),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Verification successful.',
            'data' => [
                'reset_token' => $resetToken,
            ],
        ], 200);
    }

    /**
     * Step 3: Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'reset_token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        $otpRecord = $user?->passwordResetOtp;

        if (
            !$otpRecord ||
            !$otpRecord->verified_at ||
            !$otpRecord->consumed_at ||
            !$otpRecord->reset_token_hash ||
            !$otpRecord->reset_token_expires_at ||
            $otpRecord->reset_token_expires_at->isPast() ||
            !Hash::check(
                $validated['reset_token'],
                $otpRecord->reset_token_hash
            )
        ) {
            throw ValidationException::withMessages([
                'reset_token' => ['The reset token is invalid or expired.'],
            ]);
        }

        // Update the password and delete the used reset record atomically.
        DB::transaction(function () use ($user, $otpRecord, $validated) {
            $user->password = Hash::make($validated['password']);
            $user->save();

            $otpRecord->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
        ], 200);
    }
}
