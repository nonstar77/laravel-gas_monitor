<?php

// app/Models/SensorData.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SensorData extends Model
{
    use SoftDeletes;
    use HasFactory;

    // Tambahkan kolom yang sesuai dengan data yang akan disimpan
    protected $fillable = [
        'gas_value_mq4',
        'gas_value_mq6',
        'gas_value_mq8',
    ];
}

