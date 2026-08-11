<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request): View
    {
        $query = SubscriptionPlan::query()->withCount('subscriptions');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $plans = $query->latest()->paginate(20)->withQueryString();

        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.subscription-plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePlan($request);

        SubscriptionPlan::create($data);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', "Subscription plan '{$data['name']}' created successfully!");
    }

    public function edit(SubscriptionPlan $subscriptionPlan): View
    {
        return view('admin.subscription-plans.edit', ['plan' => $subscriptionPlan]);
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $data = $this->validatePlan($request, $subscriptionPlan);

        $subscriptionPlan->update($data);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', "Subscription plan '{$data['name']}' updated successfully!");
    }

    public function destroy(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        if ($subscriptionPlan->subscriptions()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete a plan with existing subscribers!');
        }

        $subscriptionPlan->delete();

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully!');
    }

    private function validatePlan(Request $request, ?SubscriptionPlan $plan = null): array
    {
        $slugRule = $plan
            ? "required|string|max:255|unique:subscription_plans,slug,{$plan->id}"
            : 'required|string|max:255|unique:subscription_plans,slug';

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => $slugRule,
            'price'       => 'required|numeric|min:0',
            'interval'    => 'required|in:monthly,yearly',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
