<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\Customer;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Hash;
use App\Category;
use App\FlashDeal;
use App\Brand;
use App\SubCategory;
use App\SubSubCategory;
use App\Product;
use App\PickupPoint;
use App\CustomerPackage;
use App\CustomerProduct;
use App\Seller;
use App\Shop;
use App\Color;
use App\Order;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie;

class ApiController extends Controller 
{
public $successStatus = 200;/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            //$success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['status'=>'success','code'=>'200','msg'=>'Login successfully','data' => $user], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['status'=>'Unauthorised','code'=>'201','msg'=>'Unauthorised access','data'=>array()], 401); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
		 $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'phone' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
            'state' => 'required'
            //'c_password' => 'required|same:password', 
        ]);
		if($validator->fails()) { 
            
            return response()->json(['status'=>'error','code'=>'201','msg'=>$validator->errors(),'data' => $validator->errors()], 403);        
        }
        
		 if(User::where('email', $request->email)->first() != null){
            return response()->json(['status'=>'error','code'=>'201','msg'=>'user already exist','data' => array()], 403);
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->user_type = 'customer';
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->state = $request->state;
        $user->institute_id = '1';
        $user->category_id = '1';
       
        
        
        $user->password = Hash::make($request->password);
        if($user->save()){
            $customer = new Customer;
            $customer->user_id = $user->id;
            if($customer->save()){
				return response()->json(['status'=>'success','code'=>'200','msg'=>'registered successfully','data' => $user], $this-> successStatus); 
            }
        }

        return response()->json(['status'=>'error','code'=>'201','msg'=>'user can not registered','data' => array()], 403);
		/*
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
$input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['name'] =  $user->name;
return response()->json(['success'=>$success], $this-> successStatus); 
*/ 
    }
/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function getUserById(Request $request) 
    { 
		/*
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus);
        */
        $customer = User::findOrFail($request->userid);
		if($customer == false){
			 return response()->json(['status'=>'success','code'=>'201','msg'=>'No User found','data' => $customer], 403); 
			 } 
         return response()->json(['status'=>'success','code'=>'200','msg'=>'User Detail','data' => $customer], $this-> successStatus); 
    } 
    
        public function getUserByEmail(Request $request) 
    { 
		
        $customer = User::all()->where('email',$request->email)->first();
         if($customer == false){
			 return response()->json(['status'=>'success','code'=>'201','msg'=>'No User found','data' => array()], 403); 
			 } 
         return response()->json(['status'=>'success','code'=>'200','msg'=>'User Detail','data' => $customer], $this-> successStatus); 
    } 
}
