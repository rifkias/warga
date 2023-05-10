<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;
    protected $table = 'wilayah';

    public function getWilayahNameAttribute()
    {
        return "{$this->kelurahan} - {$this->rt}/{$this->rw}";
    }
}
