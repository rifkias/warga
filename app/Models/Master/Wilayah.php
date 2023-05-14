<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Organisation;
class Wilayah extends Model
{
    use HasFactory;
    protected $table = 'wilayah';
    protected $fillable = [
        'provinsi', 'negara', 'kabupaten','kecamatan','kelurahan','rw','rt','kode_pos'
    ];
    public function organisation()
    {
        return $this->hasMany(Organisation::class,'wilayah_id','id');
    }
    public function getWilayahNameAttribute()
    {
        if($this->rt || $this->rt <> ''){
            return "{$this->kelurahan} - Rt:{$this->rt}/ Rw:{$this->rw}";
        }else{
            return "{$this->kelurahan} - Rw:{$this->rw}";
        }
    }

    public function scopeGetCurrentOrganisation($query)
    {
        $query->with('organisation')
            ->whereHas('organisation',function($query){
                $query->where('wilayah_id',auth()->user()->organisation()->first()->wilayah_id);
            });
    }
}
