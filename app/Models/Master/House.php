<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Organisation;
class House extends Model
{
    use HasFactory;
    protected $table = 'rumah';
    protected $fillable = [
        'house_number','house_type','organisation_id'
    ];
    public function organisation()
    {
        return $this->hasOne(Organisation::class,'id','organisation_id');
    }
}
