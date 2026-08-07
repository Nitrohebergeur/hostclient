<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExtensionController extends Controller
{
    public function index(Request $request)
    {
        $query = Extension::with('uploader')->orderBy('type')->orderBy('name');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $extensions = $query->get()->groupBy('type');
        $types = Extension::$types;

        return view('admin.extensions.index', compact('extensions', 'types'));
    }

    public function create()
    {
        $types = Extension::$types;
        return view('admin.extensions.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'version'     => 'nullable|string|max:50',
            'author'      => 'nullable|string|max:255',
            'author_url'  => 'nullable|url',
            'type'        => 'required|string',
            'file'        => 'required|file|max:102400|mimes:zip,tar,gz',
        ]);

        $file = $request->file('file');
        $slug = Str::slug($validated['name']) . '-' . time();

        $path = $file->storeAs(
            'extensions',
            $slug . '.' . $file->getClientOriginalExtension(),
            'local'
        );

        // Lire le manifeste si c'est un ZIP
        $manifest = $this->readManifest($file->getPathname());

        Extension::create([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'version'     => $validated['version'] ?? ($manifest['version'] ?? '1.0.0'),
            'description' => $validated['description'] ?? ($manifest['description'] ?? null),
            'author'      => $validated['author'] ?? ($manifest['author'] ?? null),
            'author_url'  => $validated['author_url'] ?? null,
            'type'        => $validated['type'],
            'file_path'   => $path,
            'file_name'   => $file->getClientOriginalName(),
            'file_size'   => $file->getSize(),
            'file_hash'   => md5_file($file->getPathname()),
            'manifest'    => $manifest,
            'config_schema' => $manifest['config'] ?? null,
            'is_active'   => false,
            'is_built_in' => false,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.extensions.index')
            ->with('success', "Extension \"{$validated['name']}\" uploadée avec succès.");
    }

    public function show(Extension $extension)
    {
        return view('admin.extensions.show', compact('extension'));
    }

    public function edit(Extension $extension)
    {
        $types = Extension::$types;
        return view('admin.extensions.edit', compact('extension', 'types'));
    }

    public function update(Request $request, Extension $extension)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'version'     => 'nullable|string|max:50',
            'author'      => 'nullable|string|max:255',
            'author_url'  => 'nullable|url',
            'type'        => 'required|string',
            'file'        => 'nullable|file|max:102400|mimes:zip,tar,gz',
            'config_values' => 'nullable|array',
        ]);

        $updateData = [
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? null,
            'version'       => $validated['version'] ?? $extension->version,
            'author'        => $validated['author'] ?? null,
            'author_url'    => $validated['author_url'] ?? null,
            'type'          => $validated['type'],
            'config_values' => $validated['config_values'] ?? $extension->config_values,
        ];

        if ($request->hasFile('file')) {
            $extension->deleteFile();
            $file = $request->file('file');
            $slug = Str::slug($validated['name']) . '-' . time();
            $path = $file->storeAs('extensions', $slug . '.' . $file->getClientOriginalExtension(), 'local');
            $updateData['file_path'] = $path;
            $updateData['file_name'] = $file->getClientOriginalName();
            $updateData['file_size'] = $file->getSize();
            $updateData['file_hash'] = md5_file($file->getPathname());
            $updateData['manifest']  = $this->readManifest($file->getPathname());
        }

        $extension->update($updateData);

        return redirect()
            ->route('admin.extensions.index')
            ->with('success', 'Extension mise à jour.');
    }

    public function destroy(Extension $extension)
    {
        if ($extension->is_built_in) {
            return redirect()->back()->with('error', 'Impossible de supprimer une extension intégrée.');
        }

        $extension->deleteFile();
        $extension->delete();

        return redirect()
            ->route('admin.extensions.index')
            ->with('success', 'Extension supprimée.');
    }

    public function toggle(Extension $extension)
    {
        if ($extension->is_active) {
            $extension->deactivate();
            $msg = "Extension \"{$extension->name}\" désactivée.";
        } else {
            $extension->activate();
            $msg = "Extension \"{$extension->name}\" activée.";
        }

        return redirect()->back()->with('success', $msg);
    }

    protected function readManifest(string $filePath): array
    {
        try {
            $zip = new \ZipArchive();
            if ($zip->open($filePath) !== true) return [];

            $manifest = null;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (basename($name) === 'manifest.json' || basename($name) === 'composer.json') {
                    $manifest = json_decode($zip->getFromIndex($i), true);
                    break;
                }
            }
            $zip->close();

            return $manifest ?? [];
        } catch (\Exception) {
            return [];
        }
    }
}
