<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\Menu;
use App\Models\Admin\MenuPermission;
use App\Models\Admin\Role;
use App\Models\Master\Wilayah;
use App\Models\Admin\Organisation;
use App\Models\Admin\OrganisationType;
use Log,Auth,Session,File;
class ApiLogController extends Controller
{
    public function ReturnResult($status = null,$message = null,$data = null,$error = null){

        $datas = json_encode([
            'url' => url()->full(),
            'auth' => Auth::user() ?: NULL,
            'status'=>$status,
            'message'=>$message,
            'data'=>$data,
            'error'=>$error
        ]);
        if($status == 200){
            Log::info($datas);
        }else{
            Log::error($datas);
        }
        return response()->json([
            'status'=>$status,
            'message'=>$message,
            'data'=>$data,
            'error'=>$error
        ]);
    }
    public function fallback()
    {
        Session::flash('warning','Route '.str_replace('dashboard/','',request()->path()).' Not Found');
        return redirect('/dashboard');
    }
    public function MoveFile($file)
    {
        $filename = time().'.'.$file->getClientOriginalExtension();
        $location = 'userPict';
        $file->move($location,$filename);
        return $filename;
    }
    public function DeleteFile($file)
    {
        $path = public_path('userPict/'.$file);
        if(File::exists($path)){
            File::delete($path);
        }
        return true;
    }
    public function getRoleList()
    {
        return Role::select('id','name')->get();
    }
    public function getWilayahList()
    {
        return Wilayah::select(['id','kelurahan','rt','rw'])->get();
    }
    public function getOrganisationType()
    {
        return OrganisationType::get();
    }

    public function getOrganisationList()
    {
        $data;
        if(!Auth::user()->role()->first()->name == 'superadmin'){
            $data = Organisation::where('id',Auth::user()->organisation_id)->orWhere('parent_id',Auth::user()->organisation_id)->get();
        }else{
            $data = Organisation::get();
        }
        return $data;
    }
    public function getMenu()
    {
        $menus = [];
        if(Auth::user()->role()->first()->name == 'superadmin'){
            // Get Grouped Menu
            $groups = Menu::getUniqueGroup();
            foreach($groups as $group){
                $head = Menu::with('childs')
                ->where('menu_group',$group)
                ->nullParent()
                ->active()
                ->orderBy('sort','ASC')
                ->select('id','name','link','icons','sort')
                ->get();
                $menus[$group] = $head;
            }

            // Get Other Group
            $headOther = Menu::with('childs')
            ->nullParent()
            ->nullGroup()
            ->active()
            ->orderBy('sort','ASC')
            ->select('id','name','link','icons','sort')
            ->get();
            $menus['Other'] = $headOther;
        }else{
            // Get Grouped Menu
            $groups = Menu::getUniqueGroup();
            foreach($groups as $group){
                $head = Menu::with(['childs'=>function($query){
                    $query->getChildsPermission(Auth::user()->role_id);
                }])
                ->validateMenu(Auth::user()->role_id)
                ->active()
                ->nullParent()
                ->where('menu_group',$group)
                ->orderBy('sort','ASC')
                ->select('id','name','link','icons','sort')
                ->get();
                $menus[$group] = $head;
            }

            // Get Other Group
            $headOther = Menu::with(['childs'=>function($query){
                $query->getChildsPermission(Auth::user()->role_id);
            }])
            ->validateMenu(Auth::user()->role_id)
            ->nullGroup()
            ->active()
            ->orderBy('sort','ASC')
            ->select('id','name','link','icons','sort')
            ->get();
            $menus['Other'] = $headOther;
        }
        return $menus;
    }
    public function getLastesUpdateMenu()
    {
        $data = Menu::orderBy('updated_at','DESC')->pluck('updated_at')->first();
        return date($data);
    }
    public function checkUserMenuPermission()
    {
        if(Auth::user()->role()->first()->name <> 'superadmin'){
            $url = request()->path();
            $url = explode('/',$url);
            if($url[0] == 'dashboard' && !isset($url[1])){
                return true;
            }
            if($url[1]){
                $check = Menu::with('childs')->where('link',$url[1])->first();
                if($check){
                    if($url[2]){
                        $check2 = Menu::where(['link'=>$url[2],'parent'=>$check->id])->first();
                        if($check2){
                            $attemp = MenuPermission::where(['menu_id'=>$check2->id,'role_id'=>Auth::user()->role_id])->first();
                            if($attemp){
                                return true;
                            }else{
                                Session::flash('warning',"You Doesn't Have Permisison to This Route, Please Contact Administrator");
                                return false;
                            }
                        }
                    }else{
                        $attemp = MenuPermisison::where(['menu_id'=>$check->id,'role_id'=>Auth::user()->role_id])->first();
                        if($attemp){
                                return true;
                        }else{
                                Session::flash('warning',"You Doesn't Have Permisison to This Route, Please Contact Administrator");
                            return false;
                        }
                    }
                }else{
                    Session::flash('warning',"Route Doesn't exists, Please Contact Administrator");
                    return false;
                }
            }
            Session::flash('warning','Error Unknown');
            return true;
        }
        return true;
    }
}
