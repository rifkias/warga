<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Wilayah;
class Organisation extends Model
{
    use HasFactory;
    protected $fillable = [
        'organisation_type_id','wilayah_id','parent_id'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function($model){
            if(!$model->isDirty('created_by')){
                $model->created_by = auth()->user()->id;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });
    }

    public function wilayah()
    {
        return $this->hasOne(Wilayah::class,'id','wilayah_id');
    }

    public function type()
    {
        return $this->hasOne(OrganisationType::class,'id','organisation_type_id');
    }

    public function parent()
    {
        return $this->belongsTo(Organisation::class, 'parent_id','id');
    }

    public function childs()
    {
        return $this->hasMany(Organisation::class, 'parent_id','id');
    }

    public function scopeNullParent($query){
        $query->whereNull('parent_id')
        ->orWhere('parent_id','=','');
    }
}
