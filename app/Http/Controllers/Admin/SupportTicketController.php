<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    private const STATUSES = [
        'open'     => 'Open',
        'pending'  => 'Pending',
        'resolved' => 'Resolved',
        'closed'   => 'Closed',
    ];

    private const PRIORITIES = [
        'low'    => 'Low',
        'medium' => 'Medium',
        'high'   => 'High',
    ];

    public function index(Request $request): View
    {
        $query = SupportTicket::query()->with('user');

        if ($search = $request->input('search')) {
            $query->where('subject', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $tickets = $query->latest()->paginate(20)->withQueryString();

        return view('admin.support-tickets.index', [
            'tickets'  => $tickets,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(SupportTicket $supportTicket): View
    {
        return view('admin.support-tickets.edit', [
            'ticket'     => $supportTicket->load(['user', 'replies.user']),
            'statuses'   => self::STATUSES,
            'priorities' => self::PRIORITIES,
        ]);
    }

    public function update(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $data = $request->validate([
            'status'   => 'required|in:open,pending,resolved,closed',
            'priority' => 'required|in:low,medium,high',
        ]);

        $supportTicket->update($data);

        return redirect()->route('admin.support-tickets.edit', $supportTicket)
            ->with('success', 'Ticket updated successfully!');
    }

    public function reply(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $data = $request->validate([
            'message' => 'required|string',
        ]);

        // Mirrors RepliesRelationManager's mutateFormDataUsing: staff replies
        // are always attributed to the current admin and flagged as staff.
        $supportTicket->replies()->create([
            'user_id'        => auth()->id(),
            'message'        => $data['message'],
            'is_staff_reply' => true,
        ]);

        return redirect()->route('admin.support-tickets.edit', $supportTicket)
            ->with('success', 'Reply sent successfully!');
    }

    public function destroy(SupportTicket $supportTicket): RedirectResponse
    {
        $supportTicket->delete();

        return redirect()->route('admin.support-tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }
}
