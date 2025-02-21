<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Models\SensorData;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/sensor', function (Request $request) {
    // Validasi data yang diterima
    $validated = $request->validate([
        'gas_value_mq4' => 'required|numeric',
        'gas_value_mq6' => 'required|numeric',
        'gas_value_mq8' => 'required|numeric',
    ]);

    // Simpan data ke database
    $sensorData = SensorData::create([
        'gas_value_mq4' => $validated['gas_value_mq4'],
        'gas_value_mq6' => $validated['gas_value_mq6'],
        'gas_value_mq8' => $validated['gas_value_mq8'],
    ]);

    // Kembali dengan respons JSON
    return response()->json(['message' => 'Data berhasil diterima', 'data' => $sensorData], 200);
});


// routes/api.php
Route::get('/sensor', function () {
    $sensorData = SensorData::latest()->get();  // Mengambil semua data sensor terbaru
    return response()->json($sensorData);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
