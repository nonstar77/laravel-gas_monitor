<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use App\Models\Device;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    // Mendapatkan data terbaru dari device tertentu
    public function index(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id'
        ]);

        // Cari data sensor berdasarkan device_id
        $sensorData = SensorData::where('device_id', $request->device_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json($sensorData);
    }




    // Menyimpan data sensor ke dalam database
    public function store(Request $request)
    {
        // Validasi data dan token
        $request->validate([
            'token' => 'required|string|exists:devices,token',
            'gas_value_mq4' => 'required|numeric',
            'gas_value_mq6' => 'required|numeric',
            'gas_value_mq8' => 'required|numeric',
        ]);

        // Cari device berdasarkan token
        $device = Device::where('token', $request->token)->first();

        if (!$device) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Simpan data sensor untuk device terkait
        SensorData::create([
            'device_id' => $device->id,
            'mq4_value' => $request->gas_value_mq4,
            'mq6_value' => $request->gas_value_mq6,
            'mq8_value' => $request->gas_value_mq8,
        ]);

        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }
}
