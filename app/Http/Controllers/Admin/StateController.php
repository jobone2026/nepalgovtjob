<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StateController extends Controller
{
    public function index()
    {
        $states = State::withCount('posts')->paginate(20);

        return view('admin.states.index', compact('states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:states',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        State::create($validated);
        Cache::forget('states_list');

        return redirect()->route('admin.states.index')
            ->with('success', 'State created successfully');
    }

    public function update(Request $request, State $state)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:states,name,' . $state->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $state->update($validated);
        Cache::forget('states_list');

        return redirect()->route('admin.states.index')
            ->with('success', 'State updated successfully');
    }

    public function destroy(State $state)
    {
        $state->delete();
        Cache::forget('states_list');

        return redirect()->route('admin.states.index')
            ->with('success', 'State deleted successfully');
    }
}
