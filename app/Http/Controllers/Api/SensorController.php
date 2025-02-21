<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index()
{
    $sensorData = SensorData::latest()->take(5)->get();

    SensorData::whereNotIn('id', $sensorData->pluck('id'))->delete();

    return response()->json($sensorData);
}

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'gas_value_mq4' => 'required|numeric',
            'gas_value_mq6' => 'required|numeric',
            'gas_value_mq8' => 'required|numeric',
        ]);

        // Menyimpan data ke dalam database
        $sensor = new SensorData();
        $sensor->gas_value_mq4 = $request->gas_value_mq4;
        $sensor->gas_value_mq6 = $request->gas_value_mq6;
        $sensor->gas_value_mq8 = $request->gas_value_mq8;
        $sensor->save();

        // Mengembalikan response sukses
        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }


}

