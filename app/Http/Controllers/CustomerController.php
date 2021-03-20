<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Institute;
use App\CustomerCategory;
use App\User;
use App\Order;
use App\State;
use Illuminate\Support\Facades\Hash;
use Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $customers = Customer::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $user_ids = User::where('user_type', 'customer')->where(function($user) use ($sort_search){
                $user->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%');
            })->pluck('id')->toArray();
            $customers = $customers->where(function($customer) use ($user_ids){
                $customer->whereIn('user_id', $user_ids);
            });
        }
        $customers = $customers->paginate(15);
        
        return view('customers.index', compact('customers', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function login(){
	
        if(Auth::attempt(['email' => $_REQUEST['email'], 'password' => $_REQUEST['password']])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $instititutes = Institute::all()->where('isdeleted','N');
        $customerCategories = CustomerCategory::all()->where('isdeleted','N');
        $states = State::all()->where('isactive','yes');
        return view('customers.create',compact('instititutes', 'customerCategories','states'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request)
    {
        //
         if(User::where('email', $request->email)->first() != null){
            flash(__('Email already exists!'))->error();
            return back();
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->user_type = $request->user_type;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->state = $request->state;
        $user->institute_id = $request->institute_id;
        $user->category_id = $request->category_id;
        $user->gstin = $request->gstin;
        
        
        
        $user->password = Hash::make($request->password);
        if($user->save()){
            $customer = new Customer;
            $customer->user_id = $user->id;
            if($customer->save()){
				flash(__('Customer has been inserted successfully'))->success();
				if($request->formtype == 'AR'){
					return redirect()->route('customers.index');
					}else{
                return redirect()->route('customers.index');
			}
				/*
                $shop = new Shop;
                $shop->user_id = $user->id;
                $shop->slug = 'demo-shop-'.$user->id;
                $shop->save();
                flash(__('Seller has been inserted successfully'))->success();
                return redirect()->route('sellers.index');
                */ 
            }
        }

        flash(__('Something went wrong'))->error();
        return back();
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
		
         $customer = Customer::findOrFail(decrypt($id));
         //$inst = new Institute; //Institute::orderBy('created_at', 'desc')->limit(1);
         //$instititutes = Institute::findOrFail(1);
         $instititutes = Institute::all()->where('isdeleted','N');
         $customerCategories = CustomerCategory::all()->where('isdeleted','N');
         $states = State::all();
         //$instititutes = Institute::where('isdeleted','N');
         
        return view('customers.edit', compact('customer','instititutes','customerCategories','states'));
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
        $customer = Customer::findOrFail($id);
        $user = $customer->user;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->user_type = $request->user_type;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->state = $request->state;
        $user->institute_id = $request->institute_id;
        $user->category_id = $request->category_id;
        $user->gstin = $request->gstin;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        if($user->save()){
            
                flash(__('Customer has been updated successfully'))->success();
                return redirect()->route('customers.index');
           
        }

        flash(__('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::where('user_id', Customer::findOrFail($id)->user->id)->delete();
        User::destroy(Customer::findOrFail($id)->user->id);
        if(Customer::destroy($id)){
            flash(__('Customer has been deleted successfully'))->success();
            return redirect()->route('customers.index');
        }

        flash(__('Something went wrong'))->error();
        return back();
    }
}
