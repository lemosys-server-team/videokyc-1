<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\ProductCategory;
use DataTables;
use Form;


class ProductCategories extends Controller
{
     /**
     * __construct
     */
    public function __construct(){
     
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
       return view('admin.product_categories.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductCategories(Request $request){        
        $categories = ProductCategory::query();
        
         return DataTables::of($categories)
            ->editColumn('parent.title', function ($category) {
                return isset($category->parent)?$category->parent->title:'';
            })
            ->filterColumn('parent.title', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                $query->whereHas('parent', function($query) use ($keyword){
                    $query->whereRaw("LOWER(title) like ?", ["%$keyword%"]);
                });
            })
            ->editColumn('is_active', function ($category) {
                if($category->is_active == TRUE ){
                    return "<a href='".route('admin.product_categories.status',[$category->id])."'><span class='badge badge-success'>Active</span></a>";
                }else{
                    return "<a href='".route('admin.product_categories.status',[$category->id])."'><span class='badge badge-danger'>Inactive</span></a>";
                }
            })
            ->addColumn('action', function ($category) {
                return
                    // edit
                    '<a href="'.route('admin.product_categories.edit',[$category->id]).'" class="btn btn-success btn-circle btn-sm"><i class="fas fa-edit"></i></a> '.
                    // Delete
                    Form::open(array(
                        'style' => 'display: inline-block;',
                        'method' => 'DELETE',
                        'onsubmit'=>"return confirm('Do you really want to delete?')",
                        'url' => route('admin.product_categories.destroy', [$category->id]))).
                        
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
       
        $categories = ProductCategory::where(['is_active'=>TRUE])->pluck('title', 'id');
        return view('admin.product_categories.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $rules = [
            'title'=>['required', Rule::unique(with(new ProductCategory)->getTable(), 'title')],
        ];

        $request->validate($rules);

        $data = $request->all();

        ProductCategory::create($data);
        $request->session()->flash('success',__('global.messages.add'));
        return redirect()->route('admin.product_categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $productcategory){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $productcategory = ProductCategory::findOrFail($id);
        return view('admin.product_categories.form', compact('productcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $productcategory = ProductCategory::findOrFail($id);
        $rules = [
            'title'=>['required',Rule::unique(with(new ProductCategory)->getTable(), 'title')->ignore($productcategory->getKey())],
        ];

        $request->validate($rules);
        $data = $request->all();
        $productcategory->update($data);

        $request->session()->flash('success',__('global.messages.update'));
        return redirect()->route('admin.product_categories.index');
    }

    /**
     * Change status the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, $id=null){
        $productcategory = ProductCategory::findOrFail($id);
        if (isset($productcategory->is_active) && $productcategory->is_active==FALSE) {
            $productcategory->update(['is_active'=>TRUE]);
            $request->session()->flash('success',__('global.messages.activate'));
        }else{
            $productcategory->update(['is_active'=>FALSE]);
            $request->session()->flash('danger',__('global.messages.deactivate'));
        }
        return redirect()->route('admin.product_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id){
        $productcategory = ProductCategory::findOrFail($id);
        if($productcategory->id == config('constants.CATEGORY_TYPE_QURAN_ID')){
            $request->session()->flash('danger',__('global.messages.default_message_category'));
            return redirect()->route('admin.product_categories.index'); 
        }else{
           $productcategory->delete();
           $request->session()->flash('danger',__('global.messages.delete'));
           return redirect()->route('admin.product_categories.index'); 
        }

       
        
    }
}
