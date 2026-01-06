<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InquiryResponse;

class AdminInquiryController extends Controller
{
    /**
     * Display a listing of the inquiries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Inquiry::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $inquiries = $query->latest()->paginate(15);
        
        // For AJAX requests, return JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.inquiries.partials.inquiry_rows', compact('inquiries'))->render(),
                'pagination' => $inquiries->withQueryString()->links()->toHtml()
            ]);
        }

        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Display the specified inquiry.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Show the form for responding to the specified inquiry.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function respond($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('admin.inquiries.respond', compact('inquiry'));
    }

    /**
     * Send a response to the inquiry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendResponse(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string|min:10',
        ]);

        $inquiry = Inquiry::findOrFail($id);
        $inquiry->response = $request->response;
        $inquiry->is_responded = true;
        $inquiry->responded_at = now();
        $inquiry->save();

        // Send email response
        try {
            Mail::to($inquiry->email)->send(new InquiryResponse($inquiry));
            return redirect()->route('admin.inquiries.index')
                ->with('success', 'Response sent successfully to ' . $inquiry->email);
        } catch (\Exception $e) {
            return redirect()->route('admin.inquiries.index')
                ->with('error', 'Response saved but email could not be sent: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified inquiry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,spam',
            'admin_notes' => 'nullable|string',
        ]);

        $inquiry = Inquiry::findOrFail($id);
        $inquiry->status = $request->status;
        $inquiry->admin_notes = $request->admin_notes;
        $inquiry->save();

        return redirect()->route('admin.inquiries.show', $inquiry->id)
            ->with('success', 'Inquiry updated successfully');
    }

    /**
     * Mark an inquiry as read.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->is_read = true;
        $inquiry->save();

        return response()->json(['success' => true]);
    }

    /**
     * Mark an inquiry as unread.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsUnread($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->is_read = false;
        $inquiry->save();

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified inquiry from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted successfully');
    }

    /**
     * Process bulk actions on inquiries
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark_read,mark_unread,change_status',
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:inquiries,id',
            'status' => 'required_if:action,change_status|in:pending,in_progress,resolved,spam'
        ]);

        $count = 0;
        
        switch ($request->action) {
            case 'delete':
                Inquiry::whereIn('id', $request->selected_ids)->delete();
                $count = count($request->selected_ids);
                $message = "{$count} " . str_plural('inquiry', $count) . " deleted successfully";
                break;
                
            case 'mark_read':
                $count = Inquiry::whereIn('id', $request->selected_ids)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
                $message = "Marked {$count} " . str_plural('inquiry', $count) . " as read";
                break;
                
            case 'mark_unread':
                $count = Inquiry::whereIn('id', $request->selected_ids)
                    ->where('is_read', true)
                    ->update(['is_read' => false]);
                $message = "Marked {$count} " . str_plural('inquiry', $count) . " as unread";
                break;
                
            case 'change_status':
                $count = Inquiry::whereIn('id', $request->selected_ids)
                    ->where('status', '!=', $request->status)
                    ->update(['status' => $request->status]);
                $status = str_replace('_', ' ', $request->status);
                $message = "Updated status for {$count} " . str_plural('inquiry', $count) . " to {$status}";
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count
        ]);
    }

    /**
     * Get response templates for AJAX
     */
    public function getResponseTemplates()
    {
        $templates = [
            'thank_you' => [
                'subject' => 'Thank you for your inquiry',
                'body' => "Dear [Customer Name],\n\nThank you for contacting us. We have received your inquiry and our team will get back to you within 24-48 hours.\n\nBest regards,\n[Your Company Name]"
            ],
            'more_info' => [
                'subject' => 'Additional information required',
                'body' => "Dear [Customer Name],\n\nThank you for your inquiry. To better assist you, we need some additional information:\n\n1. [Specific information needed]\n2. [Any relevant details]\n\nOnce we receive this information, we'll be happy to help you further.\n\nBest regards,\n[Your Company Name]"
            ],
            'resolved' => [
                'subject' => 'Your inquiry has been resolved',
                'body' => "Dear [Customer Name],\n\nWe're glad to inform you that your inquiry has been resolved. If you have any further questions, please don't hesitate to contact us.\n\nThank you for your patience and for choosing our services.\n\nBest regards,\n[Your Company Name]"
            ]
        ];

        return response()->json($templates);
    }
}
