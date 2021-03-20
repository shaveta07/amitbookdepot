<?php

namespace App\Http\Controllers;

use App\CustomerCategory;
use Illuminate\Http\Request;

class CustomerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $customerCategories = CustomerCategory::orderBy('created_at', 'desc')->Where('isdeleted','N')->paginate(15);
        return view('customercategories.index', compact('customerCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customercategories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $CustomerCategory = new CustomerCategory;
		$CustomerCategory->name = $request->name;
		$CustomerCategory->customertype = $request->customertype;
		$CustomerCategory->save();
		 return redirect()->route('customercategories.index')

                        ->with('success','Customer Category has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCategory $customerCategory)
    {
        return view('customercategories.show',compact('customerCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $customercategories = CustomerCategory::findOrFail(decrypt($id));
        return view('customercategories.edit',compact('customercategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $CustomerCategory = CustomerCategory::findOrFail($id);
       
		$CustomerCategory->name = $request->name;
		$CustomerCategory->customertype = $request->customertype;
		$CustomerCategory->isdeleted = $request->isdeleted;
		$CustomerCategory->save();
		 //$CustomerCategory->update($request->all());
        return redirect()->route('customercategories.index')

                        ->with('success','Customer categories updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$customerCategory = CustomerCategory::findOrFail($id);
        $customerCategory->delete();

  

        return redirect()->route('customercategories.index')

                        ->with('success','Customer Category deleted successfully');
    }
}
