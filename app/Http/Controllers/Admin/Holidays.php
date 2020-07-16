<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Holiday;
use Validator;
use Auth;
use DataTables;
use Config;
use Form;
use DB;

class Holidays extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){  
    	return view('admin.holiday.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHolidays(Request $request){
       
         $holidays=Holiday::select([\DB::raw(with(new Holiday)->getTable().'.*')])->groupBy('id');
         
        return DataTables::of($holidays)
           ->addColumn('day', function($holiday){
 				 return date('l', strtotime($holiday->date));
            })
            ->addColumn('month', function($holiday){
              return date('F', strtotime($holiday->date));
            })
            ->editColumn('date', function($holiday){
                 return date('d', strtotime($holiday->date)).'th';
            })
            ->editColumn('is_active', function ($holiday) {
                if($holiday->is_active == TRUE )
                {
                    return "<a href='".route('admin.holidays.status',$holiday->id)."'><span class='badge badge-success'>Active</span></a>";
                }else{
                    return "<a href='".route('admin.holidays.status',$holiday->id)."'><span class='badge badge-danger'>Inactive</span></a>";
                }
            })
            ->addColumn('action', function ($holiday) {
            	/*'<a href="'.route('admin.time.show',[$holiday->id]).'" class="btn btn-success btn-circle btn-sm"><i class="fa fa-eye"></i></a>'*/
            	  // Delete
                     
                return '<a href="'.route('admin.holidays.edit',[$holiday->id]).'" class="btn btn-primary btn-circle btn-sm"><i class="fas fa-edit"></i></a> '.

                Form::open(array(
                                  'style' => 'display: inline-block;',
                                  'method' => 'DELETE',
                                   'onsubmit'=>"return confirm('Do you really want to delete?')",
                                  'route' => ['admin.holidays.destroy', $holiday->id])).
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
    public function create(){
       return view('admin.holiday.form');
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = [
            'title'  => 'required|unique:'.with(new Holiday)->getTable().',title',
            'date'  => 'required', 
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()) {
            $data = $request->all();
            $date=isset($request->date)?$request->date:'';
            $data['date']=date(config('constants.MYSQL_STORE_DATE_FORMAT'),strtotime($date));
            Holiday::create($data);
            $request->session()->flash('success',__('global.messages.add'));
            return redirect()->route('admin.holidays.index');
        }else{
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($holiday_id){
    	$holiday = Holiday::with('user','sale')->where('id',$holiday_id)->first();
    	return view('admin.holiday.show', compact('holiday'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($holiday_id){
    	$holiday = Holiday::findOrFail($holiday_id);
        return view('admin.holiday.form', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $holiday_id){
        $holiday = Holiday::findOrFail($holiday_id);
        $rules = [
            'title'     => 'required|unique:'.with(new Holiday)->getTable().',title,'.$holiday->getKey(),
            'date'  => 'required', 
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $request->all();
            $date=isset($request->date)?$request->date:'';
            $data['date']=date(config('constants.MYSQL_STORE_DATE_FORMAT'),strtotime($date));
            $holiday->update($data);
            $request->session()->flash('success',__('global.messages.update'));
            return redirect()->route('admin.holidays.index');
        }else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

     /**
     * Change status the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function status($holiday_id){
      $holiday = Holiday::findOrFail($holiday_id);
      if(isset($holiday->is_active) && $holiday->is_active==FALSE){
          $holiday->update(['is_active'=>TRUE]);
          session()->flash('success',__('global.messages.activate'));
      }else{
          $holiday->update(['is_active'=>FALSE]);
          session()->flash('danger',__('global.messages.deactivate'));
      }
      return redirect()->route('admin.holidays.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($holiday_id){
      $holiday = Holiday::findOrFail($holiday_id);
      $holiday->delete();
      session()->flash('danger',__('global.messages.delete'));
      return redirect()->route('admin.holidays.index');
    }
}
