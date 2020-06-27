<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Form;
use App\Country;

class Countries extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.countries.index');
    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountries(Request $request){
        $countries = Country::query(); 
        $countries->where('id', '!=', config('constants.COUNTRY_OTHER_ID'))->select([\DB::raw(with(new Country)->getTable().'.*')])->groupBy('id');          

        return DataTables::of($countries)               

            ->editColumn('is_active', function ($country) {
                if($country->is_active == TRUE ){
                    return "<a href='".route('admin.countries.status',$country->id)."'><span class='badge badge-success'>Active</span></a>";
                }else{
                    return "<a href='".route('admin.countries.status',$country->id)."'><span class='badge badge-danger'>Inactive</span></a>";
                }
            })   
           
             
            ->addColumn('action', function ($country) {
                return
                    // edit
                    '<a href="'.route('admin.countries.edit',[$country->id]).'" class="btn btn-success btn-circle btn-sm"><i class="fas fa-edit"></i></a> '.
                    // Delete
                    Form::open(array(
                        'style' => 'display: inline-block;',
                        'method' => 'DELETE',
                        'onsubmit'=>"return confirm('Do you really want to delete?')",
                        'route' => ['admin.countries.destroy', $country->id])).
                    ' <button type="submit" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></button>'.
                    Form::close();
            })
            ->rawColumns(['media.name','is_active','action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title'=>'required|unique:'.with(new Country)->getTable()
        ];

        $request->validate($rules);

        $data = $request->all();

        $country = Country::create($data);

        $request->session()->flash('success',__('global.messages.add'));
        return redirect()->route('admin.countries.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        return view('admin.countries.form',compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $rules = [            
            'title'=>'required|unique:'.with(new Country)->getTable().',title,'.$country->getKey()
        ];

        $request->validate($rules);

        $data = $request->all();

        $country->update($data);   

        $request->session()->flash('success',__('global.messages.update'));
        return redirect()->route('admin.countries.index');
    }

    /**
     * Change status the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
    */
    public function status(Request $request, $country_id=null){
        $country = Country::findOrFail($country_id);
        if (isset($country->is_active) && $country->is_active==FALSE) {
            $country->update(['is_active'=>TRUE]);
            $request->session()->flash('success',__('global.messages.activate'));
        }else{
            $country->update(['is_active'=>FALSE]);
            $request->session()->flash('danger',__('global.messages.deactivate'));
        }
        return redirect()->route('admin.countries.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy(Request $request,Country $country)
     {
            $country->delete();
            $request->session()->flash('danger',__('global.messages.delete'));
            return redirect()->route('admin.countries.index');
     }
}
