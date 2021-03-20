<?php

namespace App\Http\Controllers;

use App\Institute;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institutes = Institute::orderBy('created_at', 'desc')->paginate(15);
        return view('institutes.index', compact('institutes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('institutes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $institute = new Institute;
		$institute->name = $request->name;
		
		$institute->save();


        return redirect()->route('institutes.index')

                        ->with('success','Institute created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Institute  $institute
     * @return \Illuminate\Http\Response
     */
    public function show(Institute $institute)
    {
        //return view('institutes.show',compact('institute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Institute  $institute
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $institutes = Institute::findOrFail(decrypt($id));
        //print_r($shipping);die;
        return view('institutes.edit',compact('institutes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Institute  $institute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $institutes = Institute::findOrFail($id);
        //print_r($institutes);die;
        $institutes->name = $request->name;
        $institutes->save();
		//$institutes->update($request->all());
        return redirect()->route('institutes.index')

                        ->with('success','Institute has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Institute  $institute
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$institute = Institute::findOrFail($id);
        $institute->delete();
		return redirect()->route('institutes.index')

                        ->with('success','Institute deleted successfully');
                        
    }
}
