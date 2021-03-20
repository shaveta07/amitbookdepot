@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Edit Recharge Wallet')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('get.wallet.edit',$wallet->id) }}" method="POST" >
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="type">{{__('Name')}}</label>
                    <div class="col-sm-10">
                            <!-- <input type="text" class="form-control" name="name" value="" placeholder="Name" required> -->
                        <select class="form-control demo-select2-placeholder" name="user_id" id="user" required>
                           @foreach(\App\User::all() as $user)
                           <option value="{{$user->id}}" <?php if($wallet->user_id == $user->id){ echo ' selected="selected"';} ?> >{{$user->name}}</option>
                           @endforeach
                           
                            
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Amount')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="amount" value="{{$wallet->amount}}" placeholder="Amount" required>
                    </div>
                </div>
               
                
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

       
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">

       

    </script>
@endsection
