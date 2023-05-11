<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu';
    protected $fillable = [
        'name', 'description', 'parent','icons','sort','status','created_by','updated_by','link','menu_group'
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

    // Table Relations
    public function childs()
    {
        return $this->hasMany(Menu::class,'parent','id')->select('id','name','link','icons','parent','sort')->orderBy('sort','ASC');
    }
    public function head()
    {
        return $this->belongsTo(Menu::class, 'parent');
    }
    public function menuPermissions()
    {
        return $this->hasMany(MenuPermission::class,'menu_id','id')->select('id','menu_id','role_id');
    }
    public function childWithPermission()
    {
        return $this->hasMany(Menu::class,'parent','id')->with('menuPermissions')->whereHas('menuPermissions',function($query){
            $query->where('role_id',Auth::user()->role_id);
        })->select('id','name','link','icons','parent');
    }

    // Scopes
    public function scopeHasGroup($query)
    {
        $query->whereNotNull('menu_group')
        ->orWhere('menu_group','<>','');
    }
    public function scopeHasParent($query)
    {
        $query->whereNotNull('parent')
        ->orWhere('parent','<>','');
    }
    public function scopeOrHasParent($query)
    {
        $query->orWhereNotNull('parent')
        ->orWhere('parent','<>','');
    }
    public function scopeNullGroup($query)
    {
        $query->whereNull('menu_group')
        ->orWhere('menu_group','=','');
    }
    public function scopeNullParent($query)
    {
        $query->whereNull('parent')
        ->orWhere('parent','=','');
    }
    public function scopeOrNullParent($query)
    {
        $query->orWhereNull('parent')
        ->orWhere('parent','=','');
    }
    public function scopeActive($query)
    {
        $query->where('status','active');
    }
    public function scopeNullChild($query)
    {
        $query->whereRaw("
        (SELECT
            COUNT(*)
        FROM menu AS menu1
        WHERE menu1.parent = menu.id) <= 0");
    }
    public function scopeValidateChilds($query,$role)
    {
        $query->whereRaw("
        (SELECT
            COUNT(*)
        FROM menu as menu1
        LEFT JOIN menu as childs ON childs.parent = menu1.id
        LEFT JOIN menu_permission ON childs.id = menu_permission.menu_id
        WHERE
        menu1.id = menu.id
        AND menu_permission.role_id = '".$role."') >= 1");
    }
    public function scopeValidateMenu($query,$role)
    {
        $query->nullChild()
        ->orwhere(function($query) use($role){
            $query->validateChilds($role);
        });
    }
    public function scopeUserRole($query,$role)
    {
        $query->whereHas('permission',function($query) use($role){
            $query->where('menu_permission.role_id',$role);
        });
    }
    public function scopeSingleMenuOrChilds($query, $role_id)
    {
        return $query->where(function ($query) use ($role_id) {
                $query->whereHas('menuPermissions', function ($query) use ($role_id) {
                    $query->where('role_id', $role_id);
                })->orWhere('parent', 0);
            })
            ->with(['childs' => function ($query) use ($role_id) {
                $query->whereHas('menuPermissions', function ($query) use ($role_id) {
                    $query->where('role_id', $role_id);
                });
            }]);
    }
    public function scopeGetChildsPermission($query,$role)
    {
        $query->with('menuPermissions')
            ->whereHas('menuPermissions',function($query) use($role){
                $query->where('role_id',$role);
            })
            ->select('id','name','link','icons','parent','sort')
            ->orderBy('sort','ASC');
    }

    // Custom Function
    public function getUniqueGroup()
    {
        return Menu::hasGroup()
            ->nullParent()
            ->orderBy('sort','ASC')
            ->get(['sort','menu_group'])
            ->unique('menu_group')
            ->pluck('menu_group');
    }
}
