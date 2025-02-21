<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use App\Models\SensorData;
use App\Models\User;


class DashboardController extends Controller
{
    public function index(){
        $data = SensorData::latest()->get();
        return view('dashboard', compact('data'));
    }    
    public function checkGasLevel()
{
    $latestSensorData = SensorData::latest()->first();
    if (!$latestSensorData) {
        return response()->json(['error' => 'Tidak ada data sensor']);
    }

    $gasLevel = $latestSensorData->gas_value;
    $gasThreshold = 100; // Batas gas berbahaya

    if ($gasLevel >= $gasThreshold) {
        $whatsappService = new WhatsAppService();
        $whatsappService->sendGasAlert($gasLevel);
    }

    return response()->json(['gasLevel' => $gasLevel]);
}
}
