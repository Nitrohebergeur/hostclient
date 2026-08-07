<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    public function index()
    {
        $themes = Theme::with('uploader')->orderBy('type')->orderBy('name')->get();
        return view('admin.themes.index', compact('themes'));
    }

    public function create()
    {
        return view('admin.themes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'version'       => 'nullable|string|max:50',
            'author'        => 'nullable|string|max:255',
            'type'          => 'required|in:client,admin,both',
            'file'          => 'required|file|max:51200|mimes:zip',
            'preview_image' => 'nullable|image|max:2048',
        ]);

        $file = $request->file('file');
        $slug = Str::slug($validated['name']) . '-' . time();

        $path = $file->storeAs('themes', $slug . '.zip', 'local');

        $previewPath = null;
        if ($request->hasFile('preview_image')) {
            $previewPath = $request->file('preview_image')->store('themes/previews', 'public');
        }

        Theme::create([
            'name'          => $validated['name'],
            'slug'          => $slug,
            'version'       => $validated['version'] ?? '1.0.0',
            'description'   => $validated['description'] ?? null,
            'author'        => $validated['author'] ?? null,
            'type'          => $validated['type'],
            'file_path'     => $path,
            'preview_image' => $previewPath,
            'is_active'     => false,
            'is_built_in'   => false,
            'uploaded_by'   => auth()->id(),
        ]);

        return redirect()
            ->route('admin.themes.index')
            ->with('success', "Thème \"{$validated['name']}\" installé avec succès.");
    }

    public function activate(Theme $theme)
    {
        $theme->activate();

        return redirect()
            ->route('admin.themes.index')
            ->with('success', "Thème \"{$theme->name}\" activé.");
    }

    public function destroy(Theme $theme)
    {
        if ($theme->is_built_in) {
            return redirect()->back()->with('error', 'Impossible de supprimer un thème intégré.');
        }
        if ($theme->is_active) {
            return redirect()->back()->with('error', 'Désactivez le thème avant de le supprimer.');
        }

        $theme->deleteFile();
        $theme->delete();

        return redirect()
            ->route('admin.themes.index')
            ->with('success', 'Thème supprimé.');
    }
}
