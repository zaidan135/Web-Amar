<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PrintSettingController extends Controller
{
    public function save(Request $request): RedirectResponse
    {
        $request->validate([
            'print_mode'   => 'required|in:pdf,direct',
            'printer_name' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $user->print_mode   = $request->print_mode;

        $user->printer_name = $request->print_mode == 'direct' ? $request->printer_name : null;
        $user->save();

        return back()->with('success', 'Pengaturan cetak berhasil disimpan.');
    }
}