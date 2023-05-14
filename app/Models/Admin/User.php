<?php

namespace App\Models\Admin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Admin\Organisation;
use Hash,Log;
use App\Http\Controllers\ApiLogController as ApiLog;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','status','role_id','organisation_id','picture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
        static::deleted(function($model){
            $ApiLog = New ApiLog;
            if($model->picture){
                $ApiLog->DeleteFile($model->picture);
            }
        });
    }
    public function setPasswordAttribute($value)
    {
        if($value){
            return $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
        }
    }
    public function setPictureAttribute($value)
    {
        $ApiLog = New ApiLog;
        return $this->attributes['picture'] = $ApiLog->MoveFile($value);
    }
    public function role()
    {
        return $this->hasOne(Role::class,'id','role_id');
    }
    public function organisation()
    {
        return $this->hasOne(Organisation::class,'id','organisation_id');
    }

    public function getRoles()
    {
        return User::role()->first()->name;
    }
    public function getOrganisationList()
    {
        $organisation = Organisation::with('childs')->find(auth()->user()->organisation_id);
        $organisationIds = $organisation->childs->pluck("id")->push($organisation->id);
        return $organisationIds;
    }
}
