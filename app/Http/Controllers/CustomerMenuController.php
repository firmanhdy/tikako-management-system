<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMenuController extends Controller
{
    /**
     * Display the customer homepage.
     */
    public function index(Request $request)
    {
        // Store table number if present in URL (QR Scan)
        $this->handleTableSession($request);

        // Retrieve recommended menus
        $recommendations = Menu::where('is_tersedia', true)
            ->where('is_rekomendasi', true)
            ->get();

        // Retrieve other menus (limit 6 for initial view)
        $otherMenus = Menu::where('is_tersedia', true)
            ->where('is_rekomendasi', false)
            ->latest()
            ->take(6)
            ->get();

        return view('pelanggan.index', [
            'data_rekomendasi' => $recommendations,
            'data_menu_lainnya' => $otherMenus
        ]);
    }

    /**
     * Display the complete menu list page.
     */
    public function menuPage(Request $request)
    {
        $this->handleTableSession($request);

        // Base Query: Only show available items & prioritize recommendations
        $query = Menu::where('is_tersedia', true)
            ->orderByDesc('is_rekomendasi')
            ->latest();

        // Mode 1: Filter by Category
        if ($request->filled('kategori')) {
            $menus = $query->where('kategori', $request->kategori)->paginate(10);

            return view('pelanggan.menu', [
                'menus' => $menus,
                'kategori_aktif' => $request->kategori,
                'mode_tampilan' => 'list'
            ]);
        }

        // Mode 2: Default View (Grouped by Category)
        $menusGrouped = $query->get()->groupBy('kategori');

        return view('pelanggan.menu', [
            'menus_grouped' => $menusGrouped,
            'kategori_aktif' => '',
            'mode_tampilan' => 'group'
        ]);
    }

    /**
     * Display menu details.
     */
    public function show(Menu $menu)
    {
        // If menu is unavailable, show 404
        if (!$menu->is_tersedia) {
            abort(404);
        }

        return view('pelanggan.show', compact('menu'));
    }

    public function about()
    {
        return view('pelanggan.tentang');
    }

    public function contact()
    {
        return view('pelanggan.kontak');
    }

    /**
     * Store customer feedback.
     */
    public function storeFeedback(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'min:5'],
        ]);

        Feedback::create([
            'name' => $validated['name'],
            'email' => Auth::user()->email, // Get email from logged-in user
            'rating' => $validated['rating'],
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Thank you! Your feedback is highly appreciated.');
    }

    /* |--------------------------------------------------------------------------
    | Private Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Handle table session logic from QR Code.
     */
    private function handleTableSession(Request $request)
    {
        if ($request->filled('meja')) {
            session(['nomor_meja_otomatis' => $request->meja]);
        }
    }
}