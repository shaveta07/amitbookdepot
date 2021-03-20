<?php

namespace App\Http\Controllers;

use App\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $shippings = Shipping::orderBy('created_at', 'desc')->paginate(15);
        return view('shippings.index', compact('shippings'));
        /*
                $shippings = Shipping::latest()->paginate(5);

  

        return view('shippings.index',compact('shipping'))

            ->with('i', (request()->input('page', 1) - 1) * 5);
            */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
       return view('shippings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		/*
                $request->validate([

            'startpin' => 'required',

            'endping' => 'required',
            'price' => 'required',
            'iscod' => 'required',
            'codprice' => 'required'

        ]);
        */
        //pr($request);die;
        
		$shipping = new Shipping;
		$shipping->startpin = $request->startpin;
		$shipping->endpin = $request->endpin;
		$shipping->price = $request->price;
		$shipping->iscod = $request->iscod;
		$shipping->codprice = $request->codprice;
		$shipping->save();

        //Shipping::create($request->all());
		//flash(__('Shipping has been inserted successfully'))->success();
        
   

        return redirect()->route('shippings.index')

                        ->with('success','Shippings created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function show(Shipping $shipping)
    {
        return view('shippings.show',compact('shipping'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    //public function edit(Shipping $shipping)
    public function edit(Request $request, $id)
    {
        //return view('shippings.edit',compact('shipping'));
        $shipping = Shipping::findOrFail(decrypt($id));
        //print_r($shipping);die;
        return view('shippings.edit',compact('shipping'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
	
                $request->validate([

            'startpin' => 'required',

            'endpin' => 'required',

        ]);

/*
		$shipping = Shipping::findOrFail($id);
		
		$shipping->startpin = $request->startpin;
		$shipping->endpin = $request->endpin;
		$shipping->price = $request->price;
		$shipping->iscod = $request->iscod;
		$shipping->codprice = $request->codprice;
		//$shipping->save();

       

	if($shipping->save()){
                flash(__('Shpping has been updated successfully'))->success();
                return redirect()->route('shippings.index');
            }
*/
		$shipping = Shipping::findOrFail($id);
		 $shipping->update($request->all());
        return redirect()->route('shippings.index')

                        ->with('success','Shipping updated successfully');
                        
                   
        //flash(__('Something went wrong..'))->error();
        //return back(); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipping $shipping)
    {
                $shipping->delete();

  

        return redirect()->route('shipping.index')

                        ->with('success','Shipping deleted successfully');
    }
}
