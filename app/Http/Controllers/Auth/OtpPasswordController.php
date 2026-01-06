<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpPasswordController extends Controller
{
    // Show form to request OTP
    public function showRequestForm()
    {
        return view('auth.forgot-password-otp');
    }

    // Handle sending OTP to email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $request->input('email');

        // Throttle: only allow a new OTP every 60 seconds
        $existing = DB::table('password_reset_tokens')->where('email', $email)->first();
        if ($existing && Carbon::parse($existing->created_at)->gt(now()->subSeconds(60))) {
            return back()->withErrors(['email' => 'Please wait a minute before requesting another OTP.'])->withInput();
        }

        $otp = random_int(100000, 999999);
        $hashed = Hash::make((string) $otp);

        // Upsert into password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $hashed, 'created_at' => now()]
        );

        // Send email
        Mail::send('emails.password_otp', [
            'otp' => $otp,
            'expiresMinutes' => 10,
        ], function ($message) use ($email) {
            $message->to($email)
                    ->subject(config('app.name') . ' Password Reset OTP');
        });

        return redirect()->route('password.otp.reset.form')->with([
            'status' => 'We have sent a 6-digit OTP to your email. It is valid for 10 minutes.',
            'prefill_email' => $email,
        ]);
    }

    // Show form to enter OTP and new password
    public function showResetForm(Request $request)
    {
        $email = session('prefill_email', $request->query('email'));
        return view('auth.reset-password-otp', compact('email'));
    }

    // Handle resetting password with OTP
    public function resetWithOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (! $record) {
            return back()->withErrors(['email' => 'No OTP request found for this email. Please request a new OTP.'])->withInput();
        }

        // Check expiry (10 minutes)
        if (Carbon::parse($record->created_at)->lt(now()->subMinutes(10))) {
            // Expired - delete and ask retry
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['otp' => 'This OTP has expired. Please request a new OTP.'])->withInput();
        }

        // Verify OTP
        if (! Hash::check($request->otp, $record->token)) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please check the code and try again.'])->withInput();
        }

        // Update password
        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        // Cleanup
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset successfully. You can now log in.');
    }
}
