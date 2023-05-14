<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master\House;
use App\Models\Admin\Organisation;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\HouseRequest;
use App\Http\Controllers\ApiLogController as ApiLog;
use Illuminate\Validation\ValidationException;
use DataTables,Session;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['wilayah'] = Organisation::with(['wilayah','type'])->whereHas('type',function($query){
            $query->where('name','Pengurus Warga');
        })->get();
        return view('main.master.data.house')->with($this->data);
    }
    public function dataTables(Request $request)
    {
        if(auth()->user()->role()->first()->name == 'superadmin'){
            $datas = House::with('organisation','organisation.wilayah');
        }else{
            $organisation = Organisation::with('childs')->find(auth()->user()->organisation_id);
            $organisationIds = $organisation->childs->pluck("id")->push($organisation->id);
            $datas = House::with('organisation','organisation.wilayah')->whereIn('organisation_id',$organisationIds);
        }
        return DataTables::of($datas)
        ->addIndexColumn()
        ->removeColumn('id')
        ->addColumn('wilayahName',function($data){
            return $data->organisation->wilayah->wilayah_name;
        })
        ->addColumn('action',function($data){
            $button = "
            <button type='button' class='btn btn-danger btn-icon' onclick="."Delete(".$data->id.")".">
                <i class='mdi mdi-delete'></i>
            </button>";
            return $button;
        })
        ->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HouseRequest $request)
    {
        $check = House::where(['house_number'=>$request->house_number,'organisation_id'=>$request->organisation_id])->first();
        if($check){
            throw ValidationException::withMessages(['house_number' => 'The House Number Was Exists']);
        }else{
            House::create($request->except(['_token']));
            Session::flash('success','Data Berhasil Ditambahkan');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\House  $house
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master\House  $house
     * @return \Illuminate\Http\Response
     */
    public function edit(House $house)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master\House  $house
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, House $house)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\House  $house
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house)
    {
        //
    }
}
