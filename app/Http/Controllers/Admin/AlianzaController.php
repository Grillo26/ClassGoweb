<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alianza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlianzaController extends Controller
{
    public function index()
    {
        $alianzas = Alianza::orderBy('orden')->get();
        return view('admin.alianzas.index', compact('alianzas'));
    }

    public function create()
    {
        return view('admin.alianzas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagen' => 'required|image|max:2048',
            'enlace' => 'nullable|url|max:255',
            'orden' => 'nullable|integer|min:0'
        ]);

        $imagen = $request->file('imagen');
        $path = $imagen->store('optionbuilder/uploads', 'public');

        Alianza::create([
            'titulo' => $request->titulo,
            'imagen' => $path,
            'enlace' => $request->enlace,
            'orden' => $request->orden ?? 0
        ]);

        return redirect()->route('admin.alianzas.index')
            ->with('success', 'Alianza creada exitosamente');
    }

    public function edit(Alianza $alianza)
    {
        return view('admin.alianzas.edit', compact('alianza'));
    }

    public function update(Request $request, Alianza $alianza)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagen' => 'nullable|image|max:2048',
            'enlace' => 'nullable|url|max:255',
            'orden' => 'nullable|integer|min:0'
        ]);

        $data = [
            'titulo' => $request->titulo,
            'enlace' => $request->enlace,
            'orden' => $request->orden ?? $alianza->orden
        ];

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($alianza->imagen) {
                Storage::disk('public')->delete($alianza->imagen);
            }
            // Guardar nueva imagen
            $imagen = $request->file('imagen');
            $data['imagen'] = $imagen->store('optionbuilder/uploads', 'public');
        }

        $alianza->update($data);

        return redirect()->route('admin.alianzas.index')
            ->with('success', 'Alianza actualizada exitosamente');
    }

    public function destroy(Alianza $alianza)
    {
        if ($alianza->imagen) {
            Storage::disk('public')->delete($alianza->imagen);
        }
        
        $alianza->delete();

        return redirect()->route('admin.alianzas.index')
            ->with('success', 'Alianza eliminada exitosamente');
    }

    public function toggleStatus(Alianza $alianza)
    {
        $alianza->update(['activo' => !$alianza->activo]);
        return redirect()->route('admin.alianzas.index')
            ->with('success', 'Estado de la alianza actualizado');
    }
} 