<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AdminInquiryNotification;
use App\Mail\CustomerInquiryConfirmation;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns',
            'message' => 'required|string|min:10|max:2000',
        ]);

        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();

        $inquiry = Inquiry::create($validated);

        try {
            Mail::to($inquiry->email)->send(new CustomerInquiryConfirmation($inquiry));

            Mail::to(config('mail.admin_address', 'admin@eternaldiamonds.com'))
                ->send(new AdminInquiryNotification($inquiry));

            return back()->with('success', 'Thank you! Your inquiry has been submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Mail sending failed', ['error' => $e->getMessage()]);
            return back()->with('success', 'Your inquiry was received, but email notification failed.');
        }
    }
}
