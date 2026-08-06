<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::withCount('usages')->latest()->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'               => 'required|string|unique:coupons|max:50',
            'description'        => 'nullable|string',
            'type'               => 'required|in:percentage,fixed,free_setup',
            'value'              => 'required|numeric|min:0',
            'max_uses'           => 'nullable|integer|min:1',
            'max_uses_per_user'  => 'integer|min:1',
            'minimum_order'      => 'nullable|numeric|min:0',
            'starts_at'          => 'nullable|date',
            'expires_at'         => 'nullable|date|after_or_equal:starts_at',
            'is_active'          => 'boolean',
            'apply_to_setup_fee' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon créé.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'description'        => 'nullable|string',
            'type'               => 'required|in:percentage,fixed,free_setup',
            'value'              => 'required|numeric|min:0',
            'max_uses'           => 'nullable|integer|min:1',
            'max_uses_per_user'  => 'integer|min:1',
            'minimum_order'      => 'nullable|numeric|min:0',
            'starts_at'          => 'nullable|date',
            'expires_at'         => 'nullable|date',
            'is_active'          => 'boolean',
            'apply_to_setup_fee' => 'boolean',
        ]);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon mis à jour.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon supprimé.');
    }
}
