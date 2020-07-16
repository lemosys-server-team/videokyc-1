<?php

namespace App\Http\Controllers\Admin;

use App\State;
use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Form;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        return view('admin.states.index');
    }


    public function getStates(Request $request){
        $states = State::with('country')->groupBy('id')->get(); 
        return DataTables::of($states)  
            ->editColumn('is_active', function ($state) {
                if($state->is_active == TRUE ){
                    return "<a href='".route('admin.states.status',$state->id)."'><span class='badge badge-success'>Active</span></a>";
                }else{
                    return "<a href='".route('admin.states.status',$state->id)."'><span class='badge badge-danger'>Inactive</span></a>";
                }
            })              
             
            ->addColumn('action', function ($state) {
                return
                    // edit
                    '<a href="'.route('admin.states.edit',[$state->id]).'" class="btn btn-primary btn-circle btn-sm"><i class="fas fa-edit"></i></a> '.
                    // Delete
                    Form::open(array(
                        'style' => 'display: inline-block;',
                        'method' => 'DELETE',
                        'onsubmit'=>"return confirm('Do you really want to delete?')",
                        'route' => ['admin.states.destroy', $state->id])).
                    ' <button type="submit" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i></button>'.
                    Form::close();
            })

            ->rawColumns(['is_active','action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::pluck('title','id')->all();
        $timezones=array(); 
        return view('admin.states.form',compact('countries','timezones'));
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
            'country_id'=>'required',
            'title'=>'required|unique:'.with(new State)->getTable(),       
        ];

        $request->validate($rules);

        $data = $request->all();

        $city = State::create($data);

        $request->session()->flash('success',__('global.messages.add'));
        return redirect()->route('admin.states.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$state = State::find($id);
        $state = State::with('getCountry')->where(array('id'=>$id))->get();
        return view('admin.state.show',compact('state'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  
        $countries = Country::pluck('title','id')->all();
        $timezones=array(); 
        $state = State::findOrFail($id);
        return view('admin.states.form',compact('countries','timezones','state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        
        $rules = [      
            'country_id'=>'required',      
            'title'=>'required|unique:'.with(new State)->getTable().',title,'.$state->getKey(),
        ];

        $request->validate($rules);

        $data = $request->all();

        $state->update($data);   

        $request->session()->flash('success',__('global.messages.update'));
        return redirect()->route('admin.states.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request , State $state )
    {
        $state->delete();
        $request->session()->flash('danger',__('global.messages.delete'));
        return redirect()->route('admin.states.index');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, $id=null)
    {   
        $state = State::find($id);
        if($state->is_active==TRUE){
            $input['is_active'] = FALSE;
            $state->update($input);
            $request->session()->flash('success',__('global.messages.activate'));
        }else{
            $input['is_active'] = TRUE;
            $state->update($input);
            $request->session()->flash('danger',__('global.messages.deactivate'));
        }
        return redirect()->route('admin.states.index');
    }
}
