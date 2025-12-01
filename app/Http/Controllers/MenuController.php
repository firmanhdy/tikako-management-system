<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Menampilkan daftar menu.
     */
    public function index()
    {
        $menus = Menu::latest()->paginate(10);
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Menampilkan form tambah menu.
     */
    public function create()
    {
        return view('admin.menu.create');
    }

    /**
     * Menyimpan menu baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        // Handle Upload Foto
        if ($path = $this->handleFileUpload($request, 'foto')) {
            $validated['foto'] = $path;
        }

        Menu::create($validated);

        return to_route('menu.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit menu.
     */
    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    /**
     * Mengupdate data menu.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate($this->validationRules());

        // Handle Upload Foto (Hapus yang lama jika ada yang baru)
        if ($path = $this->handleFileUpload($request, 'foto', $menu->foto)) {
            $validated['foto'] = $path;
        }

        $menu->update($validated);

        return to_route('menu.index')->with('success', 'Menu berhasil di-update!');
    }

    /**
     * Menghapus menu beserta fotonya.
     */
    public function destroy(Menu $menu)
    {
        $this->deleteFileIfExists($menu->foto);
        $menu->delete();

        return to_route('menu.index')->with('success', 'Menu berhasil dihapus!');
    }

    /**
     * Mengubah status ketersediaan menu via AJAX/Toggle.
     */
    public function toggleStatus(Request $request, Menu $menu)
    {
        $newStatus = $request->boolean('status'); // Helper Laravel untuk konversi ke boolean
        
        $menu->update(['is_tersedia' => $newStatus]);

        return response()->json([
            'success' => true,
            'new_status' => $menu->is_tersedia,
            'message' => 'Status berhasil diubah menjadi ' . ($newStatus ? 'Tersedia' : 'Habis')
        ]);
    }

    /* |--------------------------------------------------------------------------
    | Private Helpers (Encapsulation)
    |--------------------------------------------------------------------------
    */

    /**
     * Aturan validasi dipisah agar tidak ditulis ulang (DRY).
     */
    private function validationRules()
    {
        return [
            'nama_menu' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'kategori' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'is_tersedia' => ['required', 'boolean'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_rekomendasi' => ['required', 'boolean']
        ];
    }

    /**
     * Menangani upload file & penghapusan file lama otomatis.
     */
    private function handleFileUpload(Request $request, $key, $oldFile = null)
    {
        if ($request->hasFile($key)) {
            // Jika ada file lama, hapus dulu
            $this->deleteFileIfExists($oldFile);
            
            // Simpan file baru
            return $request->file($key)->store('menu_fotos', 'public');
        }

        return null;
    }

    /**
     * Hapus file dari storage jika ada.
     */
    private function deleteFileIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}