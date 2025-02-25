<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use App\Models\Device;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    // Mendapatkan data terbaru dari device tertentu untuk Dashboard
    public function index(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id'
        ]);

        $sensorData = SensorData::where('device_id', $request->device_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

            return response()->json($sensorData->map(function ($data) {
                return [
                    'mq4_value' => $data->mq4_value,
                    'mq6_value' => $data->mq6_value,
                    'mq8_value' => $data->mq8_value,
                    'created_at' => $data->created_at ? $data->created_at->format('Y-m-d H:i:s') : null, // Format tanggal
                ];
            }));

    }

    // Menampilkan semua data sensor untuk halaman Data Sensor
    public function getAllSensorData(Request $request)
    {
        $deviceId = $request->input('device_id');
        $date = $request->input('date');

        // Query sensor data dengan filter perangkat dan tanggal
        $query = SensorData::with('device');

        if ($deviceId) {
            $query->where('device_id', $deviceId);
        }

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        // Menggunakan pagination dengan 10 data per halaman
        $sensorData = $query->orderBy('created_at', 'desc')->paginate(10);
        $devices = Device::all();

        return view('sensor_data', compact('sensorData', 'devices'));
    }

    // Menyimpan data sensor ke dalam database
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string|exists:devices,token',
            'gas_value_mq4' => 'required|numeric',
            'gas_value_mq6' => 'required|numeric',
            'gas_value_mq8' => 'required|numeric',
        ]);

        $device = Device::where('token', $request->token)->first();

        if (!$device) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        SensorData::create([
            'device_id' => $device->id,
            'mq4_value' => $request->gas_value_mq4,
            'mq6_value' => $request->gas_value_mq6,
            'mq8_value' => $request->gas_value_mq8,
            'created_at' => now(), // Tambahkan ini agar Laravel tidak menggunakan default '1970-01-01'
        ]);

        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }
}
