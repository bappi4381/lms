<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(): View
    {
        $tickets = auth()->user()->supportTickets()->latest()->get();

        return view('support.index', compact('tickets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:technical,payment,course,other'],
            'message' => ['required', 'string'],
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $data['subject'],
            'category' => $data['category'],
            'status' => 'open',
            'priority' => 'medium',
        ]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $data['message'],
            'is_staff_reply' => false,
        ]);

        return redirect()->route('support.show', $ticket)
            ->with('status', 'আপনার সাপোর্ট টিকেট সফলভাবে জমা হয়েছে।');
    }

    public function show(SupportTicket $ticket): View
    {
        abort_unless($ticket->user_id === auth()->id(), 403);

        $ticket->load('replies.user');

        return view('support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        abort_unless($ticket->user_id === auth()->id(), 403);

        $data = $request->validate(['message' => ['required', 'string']]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => $data['message'],
            'is_staff_reply' => false,
        ]);

        if ($ticket->status === 'resolved' || $ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('status', 'রিপ্লাই পাঠানো হয়েছে।');
    }
}
