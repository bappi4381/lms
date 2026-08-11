<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        $query = Coupon::query();

        if ($search = $request->input('search')) {
            $query->where('code', 'like', "%{$search}%");
        }

        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $coupons = $query->latest()->paginate(20)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        $courses = Course::orderBy('title_en')->pluck('title', 'id');

        return view('admin.coupons.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateCoupon($request);

        $data['code'] = strtoupper($data['code']);

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Coupon '{$data['code']}' created successfully!");
    }

    public function edit(Coupon $coupon): View
    {
        $courses = Course::orderBy('title_en')->pluck('title', 'id');

        return view('admin.coupons.edit', compact('coupon', 'courses'));
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $data = $this->validateCoupon($request, $coupon);

        $data['code'] = strtoupper($data['code']);

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Coupon '{$data['code']}' updated successfully!");
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully!');
    }

    private function validateCoupon(Request $request, ?Coupon $coupon = null): array
    {
        $codeRule = $coupon
            ? "required|string|max:50|alpha_dash|unique:coupons,code,{$coupon->id}"
            : 'required|string|max:50|alpha_dash|unique:coupons,code';

        $data = $request->validate([
            'code'              => $codeRule,
            'type'              => 'required|in:percent,fixed',
            'value'             => 'required|numeric|min:0',
            'max_uses'          => 'nullable|integer|min:1',
            'min_order_amount'  => 'nullable|numeric|min:0',
            'applicable_to'     => 'required|in:all,specific_courses',
            'course_ids'        => 'nullable|array',
            'course_ids.*'      => 'exists:courses,id',
            'starts_at'         => 'nullable|date',
            'expires_at'        => 'nullable|date|after_or_equal:starts_at',
            'is_active'         => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($data['applicable_to'] !== 'specific_courses') {
            $data['course_ids'] = null;
        }

        return $data;
    }
}
