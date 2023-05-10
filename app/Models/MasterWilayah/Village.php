<?php

namespace App\Models\MasterWilayah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'district_id','village_name'
    ];
    public function district()
    {
        return $this->hasOne(District::class,'id','district_id');
    }
}
