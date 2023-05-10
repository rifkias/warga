<?php

namespace App\Models\MasterWilayah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'city_name','province_id'
    ];
    public function province()
    {
        return $this->hasOne(Province::class,'id','province_id');
    }
}
