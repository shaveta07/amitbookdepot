<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\PaytmController;
use Auth;
use Session;
use App\Wallet;
use App\User;

class WalletController extends Controller
{
    public function index()
    {
        
        $wallets = Wallet::where('user_id', Auth::user()->id)->paginate(9);
        return view('frontend.wallet', compact('wallets'));
    }

    public function walletIndexAdmin(Request $request )
    {
        //$sort_search = null;
        $approved = null;
        $wallets = Wallet::where('payment_method', 'manual_admin')->orderBy('created_at', 'desc');
        // if ($request->has('search')){
        //     $sort_search = $request->search;
        //     $user_ids = User::where(function($user) use ($sort_search){
        //         $user->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%');
        //     })->pluck('id')->toArray();
        //     $wallets = $wallets->where(function($wallet) use ($user_ids){
        //         $wallet->whereIn('user_id', $user_ids);
        //     });
        // }
        if ($request->approved_status != null) {
            $approved = $request->approved_status;
            $wallets = $wallets->where('approval', $approved);
        }
        $wallets = $wallets->paginate(15);
        return view('frontend.wallet.index', compact('wallets', 'approved'));
       
    }

    public function walletView()
    {
       // $wallets = Wallet::where('user_id', Auth::user()->id)->paginate(9);
        return view('frontend.wallet.create');
    }

    public function walletRecharge(Request $request)
    {
       
        $user_id = $request->user_id;
        $user_exist = Wallet::where('user_id', $user_id)->where('payment_method','manual_admin')->first();
        if(Auth::user()->user_type == 'admin')
        {
            $added_by = Auth::user()->email; 
           // print_r($added_by); die();
        }
       
        if($user_exist)
        {
            $exist_amount=$user_exist->amount;
            $wallet = Wallet::where('user_id',$user_id)->where('payment_method','manual_admin')->update([
                'user_id' => $request->user_id,
                'amount' => $exist_amount + $request->amount,
                'added_by' => $added_by,
                'approval' => 1,
                'offline_payment' => 1
             ]);
             
        }
        else
        {
            $wallet = new Wallet;
            $wallet->user_id = $user_id;
            $wallet->amount = $request->amount;
            $wallet->payment_method = 'manual_admin';
            $wallet->added_by = $added_by;
            $wallet->approval = 1;
            $wallet->offline_payment = 1;
            $wallet->save();
       
        }
       
        if($wallet)
        {
        
           $user = User::where('id',$user_id)->first();
           $user->balance = $user->balance + $request->amount;
           $user->save();
           flash(__('Wallet Recharge has been done.'))->success();
          return redirect()->route('wallet.admin.index');
        }
        
  }

  public function showEditWalletRecharge($id)
  {
    $wallet = Wallet::findOrFail($id);
    return view('frontend.wallet.Edit', compact('wallet'));
    
  }
  public function getEditWalletRecharge(Request $request)
  {
        $wallet_id = Wallet::findOrFail($request->id);
        $wallet = Wallet::where('id',$wallet_id)->update([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'approval' => 1,
            'offline_payment' => 1
         ]);
         if($wallet)
        {
        
           $user = User::where('id',$wallet->user_id)->first();
           $user->balance = $user->balance + $request->amount;
           $user->save();
           flash(__('Wallet Recharge has been done.'))->success();
           return redirect()->route('wallet.admin.index');
        }

    }
        public function destroy($id)
        {
            $wallet = Wallet::findOrFail($id);
           
            User::destroy($seller->user->id);
            if(Wallet::destroy($id)){
                flash(__('Wallet Recharge has been deleted successfully'))->success();
                return redirect()->route('wallet.admin.index');
            }
            else {
                flash(__('Something went wrong'))->error();
                return back();
            }
        }
    
  

    public function recharge(Request $request)
    {
        $data['amount'] = $request->amount;
        $data['payment_method'] = $request->payment_option;

        // dd($data);

        $request->session()->put('payment_type', 'wallet_payment');
        $request->session()->put('payment_data', $data);

        if($request->payment_option == 'paypal'){
            $paypal = new PaypalController;
            return $paypal->getCheckout();
        }
        elseif ($request->payment_option == 'stripe') {
            $stripe = new StripePaymentController;
            return $stripe->stripe();
        }
        elseif ($request->payment_option == 'sslcommerz') {
            $sslcommerz = new PublicSslCommerzPaymentController;
            return $sslcommerz->index($request);
        }
        elseif ($request->payment_option == 'instamojo') {
            $instamojo = new InstamojoController;
            return $instamojo->pay($request);
        }
        elseif ($request->payment_option == 'razorpay') {
            $razorpay = new RazorpayController;
            return $razorpay->payWithRazorpay($request);
        }
        elseif ($request->payment_option == 'paystack') {
            $paystack = new PaystackController;
            return $paystack->redirectToGateway($request);
        }
        elseif ($request->payment_option == 'voguepay') {
            $voguepay = new VoguePayController;
            return $voguepay->customer_showForm();
        }
        elseif ($request->payment_option == 'paytm') {
            $paytm = new PaytmController;
            return $paytm->index();
        }
    }

    public function wallet_payment_done($payment_data, $payment_details){
        $user = Auth::user();
        $user->balance = $user->balance + $payment_data['amount'];
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $payment_data['amount'];
        $wallet->payment_method = $payment_data['payment_method'];
        $wallet->payment_details = $payment_details;
        $wallet->save();

        Session::forget('payment_data');
        Session::forget('payment_type');

        flash(__('Payment completed'))->success();
        return redirect()->route('wallet.index');
    }

    public function offline_recharge(Request $request){
        $wallet = new Wallet;
        $wallet->user_id = Auth::user()->id;
        $wallet->amount = $request->amount;
        $wallet->payment_method = $request->payment_option;
        $wallet->payment_details = $request->trx_id;
        $wallet->approval = 0;
        $wallet->offline_payment = 1;
        if($request->hasFile('photo')){
            $wallet->reciept = $request->file('photo')->store('uploads/wallet_recharge_reciept');
        }
        $wallet->save();
        flash(__('Offline Recharge has been done. Please wait for response.'))->success();
        return redirect()->route('wallet.index');
    }

    public function offline_recharge_request()
    {
        $wallets = Wallet::where('offline_payment', 1)->paginate(10);
        return view('manual_payment_methods.wallet_request', compact('wallets'));
    }

    public function updateApproved(Request $request)
    {
        $wallet = Wallet::findOrFail($request->id);
        $wallet->approval = $request->status;
        if ($request->status == 1) {
            $user = $wallet->user;
            $user->balance = $user->balance + $wallet->amount;
            $user->save();
        }
        else {
            $user = $wallet->user;
            $user->balance = $user->balance - $wallet->amount;
            $user->save();
        }
        if($wallet->save()){
            return 1;
        }
        return 0;
    }
}
