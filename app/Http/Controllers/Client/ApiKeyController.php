<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function index()
    {
        $apiKeys = auth()->user()->apiKeys()->latest()->get();

        return view('client.api-keys.index', compact('apiKeys'));
    }

    public function create()
    {
        return view('client.api-keys.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $key = ApiKey::generate();

        auth()->user()->apiKeys()->create([
            'name'       => $validated['name'],
            'key'        => $key,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active'  => true,
        ]);

        return redirect()->route('client.api-keys.index')
            ->with('success', 'Clé API créée.')
            ->with('new_key', $key);
    }

    public function show(ApiKey $apiKey)
    {
        $this->authorize('view', $apiKey);

        return view('client.api-keys.show', compact('apiKey'));
    }

    public function edit(ApiKey $apiKey)
    {
        $this->authorize('update', $apiKey);

        return view('client.api-keys.edit', compact('apiKey'));
    }

    public function update(Request $request, ApiKey $apiKey)
    {
        $this->authorize('update', $apiKey);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $apiKey->update($validated);

        return redirect()->route('client.api-keys.index')
            ->with('success', 'Clé API mise à jour.');
    }

    public function destroy(ApiKey $apiKey)
    {
        $this->authorize('delete', $apiKey);

        $apiKey->delete();

        return redirect()->route('client.api-keys.index')
            ->with('success', 'Clé API supprimée.');
    }
}
