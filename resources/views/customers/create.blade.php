@extends('layouts.app')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Customer Information')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
				
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Name')}}</label>
                    <div class="col-sm-4">
                        <input type="text" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" required>
                    </div>
                
                    <label class="col-sm-2 control-label" for="email">{{__('Email Address')}}</label>
                    <div class="col-sm-4">
                        <input type="text" placeholder="{{__('Email Address')}}" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="password">{{__('Password')}}</label>
                    <div class="col-sm-4">
                        <input type="password" placeholder="{{__('Password')}}" id="password" name="password" class="form-control" required>
                    </div>

                    <label class="col-sm-2 control-label" for="phone">{{__('Phone')}}</label>
                    <div class="col-sm-4">
                        <input type="text" placeholder="{{__('Phone')}}" id="phone" name="phone" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="user_type">{{__('User Type')}}</label>
                    <div class="col-sm-4">
						<select name="user_type" id="user_type" class="form-control" required>
							<option value="customer">Customer</option>
							<option value="institute">Institute</option>
							<option value="wholeseller">Wholeseller</option>
						</select>
                        
                    </div>
                
                    <label class="col-sm-2 control-label" for="city">{{__('City')}}</label>
                    <div class="col-sm-4">
                        <input type="text" placeholder="{{__('City')}}" id="city" name="city" class="form-control" required>
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="postal_code">{{__('Postal Code')}}</label>
                    <div class="col-sm-4">
                        <input type="text" placeholder="{{__('Postal Code')}}" id="postal_code" name="postal_code" class="form-control" required>
                    </div>
                
                    <label class="col-sm-2 control-label" for="address">{{__('Address')}}</label>
                    <div class="col-sm-4">
                        <input type="text" placeholder="{{__('Address')}}" id="address" name="address" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                     <label class="col-sm-2 control-label" for="state">{{__('State')}}</label>
                    <div class="col-sm-4">
						<select id="state" name="state" class="form-control" required>
						<?php foreach($states as $state): ?>
						<option value="{{ $state->id }}">{{ $state->name }}</option>
						<?php endforeach; ?>
						</select>
                        
                    </div>
                
                    <label class="col-sm-2 control-label" for="institute">{{__('Institute')}}</label>
                    <div class="col-sm-4">
						<select id="institute" name="institute_id" class="form-control">
						<?php foreach($instititutes as $instititute): ?>
							<option value="{{ $instititute->id }}">{{ $instititute->name }}</option>
						<?php endforeach; ?>
						</select>
                        
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="category">{{__('Category')}}</label>
                    <div class="col-sm-4">
						<select id="category_id" name="category_id" class="form-control">
						<?php foreach($customerCategories as $customerCategory): ?>
						<option value="{{ $customerCategory->id }}">{{ $customerCategory->name }}</option>
						<?php endforeach; ?>
						</select>
                        
                    </div>
                
                    <label class="col-sm-2 control-label" for="gstin">{{__('GSTIN')}}</label>
                    <div class="col-sm-4">
                        <input type="text" placeholder="{{__('GSTIN')}}" id="gstin" name="gstin" class="form-control" >
                    </div>
                </div>
                <?php $type = isset($_GET['type'])?$_GET['type']:''; ?>
                <input type="hidden" name="formtype" value="<?= $type ?>" />
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
