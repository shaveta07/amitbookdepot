<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PickupPoint;
use App\District;
use App\State;

class PickupPointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pickup_points = PickupPoint::paginate(15);
        return view('pickup_point.index', compact('pickup_points'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pickup_point.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->state)
        {
            $state=State::where('id',$request->state)->first();
            $statename=$state->name;

        }
        if($request->district)
        {
            $district=District::where('id',$request->district)->first();
            $districtname=$district->DistrictName;

        }
        $pickup_point = new PickupPoint;
        $pickup_point->name = $request->name;
        $pickup_point->address = $request->address;
        $pickup_point->state = $statename;
        $pickup_point->district = $districtname;
        $pickup_point->phone = $request->phone;
        $pickup_point->pick_up_status = $request->pick_up_status;
        //$pickup_point->cash_on_pickup_status = $request->cash_on_pickup_status;
        $pickup_point->staff_id = $request->staff_id;
        if ($pickup_point->save()) {
            flash(__('PicupPoint has been inserted successfully'))->success();
            return redirect()->route('pick_up_points.index');
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
        $pickup_point = PickupPoint::findOrFail(decrypt($id));
        return view('pickup_point.edit', compact('pickup_point'));
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
        if($request->state)
        {
            $state=State::where('id',$request->state)->first();
            $statename=$state->name;

        }
        if($request->district)
        {
            $district=District::where('id',$request->district)->first();
            $districtname=$district->DistrictName;

        }
        $pickup_point = PickupPoint::findOrFail($id);
        $pickup_point->name = $request->name;
        $pickup_point->address = $request->address;
        $pickup_point->state = $statename;
        $pickup_point->district = $districtname;
        $pickup_point->phone = $request->phone;
        $pickup_point->pick_up_status = $request->pick_up_status;
        //$pickup_point->cash_on_pickup_status = $request->cash_on_pickup_status;
        $pickup_point->staff_id = $request->staff_id;
        if ($pickup_point->save()) {
            flash(__('PicupPoint has been updated successfully'))->success();
            return redirect()->route('pick_up_points.index');
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
        $pickup_point = PickupPoint::findOrFail($id);
        if(PickupPoint::destroy($id)){
            flash(__('PicupPoint has been deleted successfully'))->success();
            return redirect()->route('pick_up_points.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }
}
