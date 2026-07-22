<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('price')->get();
        $activeSubscription = auth()->check() ? auth()->user()->activeSubscription() : null;

        return view('subscriptions.index', compact('plans', 'activeSubscription'));
    }
}
