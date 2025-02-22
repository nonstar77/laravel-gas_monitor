<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    // Menampilkan halaman daftar perangkat
    public function index()
    {
        $devices = Device::all();
        return view('devices', compact('devices')); // Mengarah ke devices.blade.php
    }


    // Menyimpan perangkat baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sensors' => 'array', // Pastikan sensors dikirim sebagai array
            'sensors.*' => 'string', // Setiap sensor harus string
        ]);

        $device = Device::create([
            'name' => $request->name,
            'sensors' => json_encode($request->sensors), // Simpan sebagai JSON
            'token' => Str::random(32),
        ]);

        return redirect()->back()->with('success', 'Perangkat berhasil didaftarkan!');
    }


    public function showAllDevices()
    {
        $devices = Device::all();
        return response()->json($devices);
    }

}
