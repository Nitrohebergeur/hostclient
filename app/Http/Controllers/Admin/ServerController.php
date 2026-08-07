<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::withCount('services')
            ->orderBy('name')
            ->get();

        return view('admin.servers.index', compact('servers'));
    }

    public function create()
    {
        $types = [
            'pterodactyl' => 'Pterodactyl Panel',
            'cpanel' => 'cPanel/WHM',
            'plesk' => 'Plesk',
            'proxmox' => 'Proxmox VE',
            'docker' => 'Docker',
            'directadmin' => 'DirectAdmin',
            'virtualizor' => 'Virtualizor',
            'solusvm' => 'SolusVM',
            'custom' => 'Custom Module',
        ];

        return view('admin.servers.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'hostname' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'use_ssl' => 'boolean',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'config' => 'nullable|array',
            'is_active' => 'boolean',
            'max_accounts' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        Server::create($validated);

        return redirect()
            ->route('admin.servers.index')
            ->with('success', 'Server created successfully.');
    }

    public function edit(Server $server)
    {
        $types = [
            'pterodactyl' => 'Pterodactyl Panel',
            'cpanel' => 'cPanel/WHM',
            'plesk' => 'Plesk',
            'proxmox' => 'Proxmox VE',
            'docker' => 'Docker',
            'directadmin' => 'DirectAdmin',
            'virtualizor' => 'Virtualizor',
            'solusvm' => 'SolusVM',
            'custom' => 'Custom Module',
        ];

        return view('admin.servers.edit', compact('server', 'types'));
    }

    public function update(Request $request, Server $server)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'hostname' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'use_ssl' => 'boolean',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'config' => 'nullable|array',
            'is_active' => 'boolean',
            'max_accounts' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        // Ne mettre à jour que les champs non vides pour les credentials
        if (empty($validated['api_key'])) {
            unset($validated['api_key']);
        }
        if (empty($validated['api_secret'])) {
            unset($validated['api_secret']);
        }
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $server->update($validated);

        return redirect()
            ->route('admin.servers.index')
            ->with('success', 'Server updated successfully.');
    }

    public function destroy(Server $server)
    {
        if ($server->services()->whereIn('status', ['active', 'pending'])->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete server with active services.');
        }

        $server->delete();

        return redirect()
            ->route('admin.servers.index')
            ->with('success', 'Server deleted successfully.');
    }

    public function testConnection(Server $server)
    {
        try {
            $result = $this->performConnectionTest($server);
            
            $server->update([
                'status' => $result['success'] ? 'online' : 'offline',
                'last_checked_at' => now(),
                'last_check_data' => $result,
            ]);

            return redirect()
                ->back()
                ->with(
                    $result['success'] ? 'success' : 'error',
                    $result['message']
                );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Connection test failed: ' . $e->getMessage());
        }
    }

    protected function performConnectionTest(Server $server): array
    {
        $url = $server->full_url;

        try {
            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful',
                    'status_code' => $response->status(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Connection failed with status: ' . $response->status(),
                'status_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
