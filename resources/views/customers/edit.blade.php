@extends('layouts.app')

@section('content')

<div class="col-lg-8 col-lg-offset-2">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Seller Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" value="{{$customer->user->name}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="email">{{__('Email Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('Email Address')}}" id="email" name="email" class="form-control" value="{{$customer->user->email}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="password">{{__('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="{{__('Password')}}" id="password" name="password" class="form-control">
                    </div>
                </div>
                
                 <div class="form-group">
                    <label class="col-sm-3 control-label" for="phone">{{__('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" value="{{$customer->user->phone}}" placeholder="{{__('Phone')}}" id="phone" name="phone" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="user_type">{{__('User Type')}}</label>
                    <div class="col-sm-9">
						<select name="user_type" id="user_type" class="form-control" required>
							<option <?php if($customer->user->user_type == 'customer'){ echo "selected"; } ?> value="customer">Customer</option>
							<option <?php if($customer->user->user_type == 'institute'){ echo "selected"; } ?> value="institute">Institute</option>
							<option <?php if($customer->user->user_type == 'wholeseller'){ echo "selected"; } ?> value="wholeseller">Wholeseller</option>
						</select>
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="city">{{__('City')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('City')}}" id="city"  value="{{$customer->user->city}}" name="city" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="postal_code">{{__('Postal Code')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('Postal Code')}}"  value="{{$customer->user->postal_code}}" id="postal_code" name="postal_code" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="address">{{__('Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('Address')}}" id="address"  value="{{$customer->user->address}}" name="address" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                     <label class="col-sm-3 control-label" for="state">{{__('State')}}</label>
                    <div class="col-sm-9">
						<select id="state" name="state" class="form-control" required>
						<?php foreach($states as $state): ?>
						<option <?php if($state->id == $customer->user->state){echo "selected"; } ?> value="{{ $state->id }}">{{ $state->name }}</option>
						<?php endforeach; ?>
						</select>
                        
                    </div>
                </div>

               
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="institute">{{__('Institute')}}</label>
                    <div class="col-sm-9">
						<select id="institute" name="institute_id" class="form-control">
						<?php foreach($instititutes as $instititute): ?>
							<option <?php if($instititute->id == $customer->user->institute_id){echo "selected"; } ?> value="{{ $instititute->id }}">{{ $instititute->name }}</option>
						<?php endforeach; ?>
						</select>
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="category">{{__('Category')}}</label>
                    <div class="col-sm-9">
						<select id="category" name="category_id" class="form-control">
						<?php foreach($customerCategories as $customerCategory): ?>
						<option <?php if($customerCategory->id == $customer->user->category_id){echo "selected"; } ?> value="{{ $customerCategory->id }}">{{ $customerCategory->name }}</option>
						<?php endforeach; ?>
						</select>
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="gstin">{{__('GSTIN')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{__('GSTIN')}}" value="{{$customer->user->gstin}}" id="gstin" name="gstin" class="form-control" >
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
