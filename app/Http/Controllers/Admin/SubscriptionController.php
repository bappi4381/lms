<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subscription::query()->with(['user', 'plan']);

        if ($search = $request->input('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $subscriptions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->pluck('name', 'id');
        $plans = SubscriptionPlan::orderBy('name')->pluck('name', 'id');

        return view('admin.subscriptions.create', compact('users', 'plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateSubscription($request);

        Subscription::create($data);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription created successfully!');
    }

    public function edit(Subscription $subscription): View
    {
        $users = User::orderBy('name')->pluck('name', 'id');
        $plans = SubscriptionPlan::orderBy('name')->pluck('name', 'id');

        return view('admin.subscriptions.edit', [
            'subscription' => $subscription->load(['user', 'plan']),
            'users'         => $users,
            'plans'         => $plans,
        ]);
    }

    public function update(Request $request, Subscription $subscription): RedirectResponse
    {
        $data = $this->validateSubscription($request);

        $subscription->update($data);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription updated successfully!');
    }

    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription deleted successfully!');
    }

    private function validateSubscription(Request $request): array
    {
        $data = $request->validate([
            'user_id'               => 'required|exists:users,id',
            'subscription_plan_id'  => 'required|exists:subscription_plans,id',
            'status'                => 'required|in:active,expired,cancelled',
            'starts_at'             => 'required|date',
            'ends_at'               => 'required|date|after_or_equal:starts_at',
            'auto_renew'            => 'boolean',
        ]);

        $data['auto_renew'] = $request->boolean('auto_renew');

        return $data;
    }
}
