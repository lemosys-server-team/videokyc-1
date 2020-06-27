<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;


class ComponentController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:components');
         $this->middleware('permission:component-create', ['only' => ['create','store']]);
         $this->middleware('permission:component-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:component-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $components = Permission::all();
        return view('admin.component.index',compact('components'));
           
        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.component.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|unique:'.with(new Permission)->getTable(),
        ]);

        Permission::create($request->all());
        
        return redirect()->route('admin.components.index')
                        ->with('success','Component created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return view('admin.component.show',compact('permission'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $component = Permission::find($id);
        return view('admin.component.edit',compact('component'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {  
        request()->validate([
            'name' => 'required',
        ]);
        $Permission = Permission::find($id);
        $input=$request->all();
        $Permission->update($input);
        return redirect()->route('admin.components.index')
                        ->with('success','Component updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        Permission::find($id)->delete();
        return redirect()->route('admin.components.index')
                        ->with('success','Component deleted successfully');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {   
        $permission = Permission::find($id);
        if($permission->is_active==TRUE){
            $input['is_active'] = FALSE;
            $permission->update($input);
        }else{
            $input['is_active'] = TRUE;
            $permission->update($input);
        }
        return redirect()->route('admin.components.index')
                        ->with('success','Status updated successfully');
    }
    
}