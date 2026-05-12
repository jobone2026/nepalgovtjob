<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::paginate(20);

        return view('admin.ads.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:header,sidebar,after_post,footer',
            'type' => 'required|in:adsense,custom',
            'code' => 'nullable|string',
            'adsense_slot_ids' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        Ad::create($validated);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad created successfully');
    }

    public function update(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:header,sidebar,after_post,footer',
            'type' => 'required|in:adsense,custom',
            'code' => 'nullable|string',
            'adsense_slot_ids' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        $ad->update($validated);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad updated successfully');
    }

    public function destroy(Ad $ad)
    {
        $ad->delete();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad deleted successfully');
    }

    public function toggle(Ad $ad)
    {
        $ad->update(['is_active' => !$ad->is_active]);

        return response()->json(['success' => true]);
    }
}
