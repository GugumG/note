<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Show the theme customization page.
     */
    public function theme()
    {
        // Ambil warna saat ini atau gunakan default
        $colors = [
            'theme_primary'   => Setting::get('theme_primary', '#213448'),
            'theme_secondary' => Setting::get('theme_secondary', '#547792'),
            'theme_accent'    => Setting::get('theme_accent', '#94B4C1'),
            'theme_bg'        => Setting::get('theme_bg', '#F3F4F4'),
            'theme_navbar'    => Setting::get('theme_navbar', '#EAE0CFec'),
        ];

        return view('settings.theme', compact('colors'));
    }

    /**
     * Update the theme colors in the database.
     */
    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme_primary'   => 'required|string|size:7', // Format #HEX
            'theme_secondary' => 'required|string|size:7',
            'theme_accent'    => 'required|string|size:7',
            'theme_bg'        => 'required|string|size:7',
            'theme_navbar'    => 'required|string|min:7|max:9', // Bisa #HEX atau #HEX+Alpha
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Tema berhasil diperbarui!');
    }
}
