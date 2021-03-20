<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Courier;
use Auth;

class CouriersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $sort_search =null;
        $couriers = Courier::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $brands = $couriers->where('courier_name', 'like', '%'.$sort_search.'%');
        }
        $couriers = $couriers->paginate(15);
        return view('courier.index', compact('couriers', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('courier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $courier = new Courier;
        $courier->courier_name = $request->courier_name;
        $courier->link = $request->link;
        $courier->description = $request->description;
        //$courier->updated_by = Auth::user()->id;
        $courier->createdby = Auth::user()->id;

        if($courier->save()){
            flash(__('Courier has been inserted successfully'))->success();
            return redirect()->route('couriers.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $courier = Courier::findOrFail(decrypt($id));
        return view('courier.edit', compact('courier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $courier = Courier::findOrFail($id);
        $courier->courier_name = $request->courier_name;
        $courier->link = $request->link;
        $courier->description = $request->description;
        $courier->updatedby = Auth::user()->id;
        //$courier->created_by = Auth::user()->id;
        if($courier->save()){
            flash(__('Courier has been updated successfully'))->success();
            return redirect()->route('couriers.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $courier = Courier::findOrFail($id);
        $courier->delete();
        if(Courier::destroy($id)){
           
            flash(__('Courier has been deleted successfully'))->success();
            return redirect()->route('couriers.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
   
    }
}
