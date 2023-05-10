<?php

namespace App\Models\MasterWilayah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'city_id','district_name'
    ];
    public function city()
    {
        return $this->hasOne(City::class,'id','city_id');
    }
}
