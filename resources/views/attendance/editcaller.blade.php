@extends('layouts.app')

@section('content')
<style>
input,select,select2 select2-container{border: 1px solid #999 !important;
border-radius: 0 !important; height:30px !important}
.form-group {
	line-height: 5px !important;
	padding-bottom: 0px;
	margin-bottom: 5px;
}
textarea{border: 1px solid #999 !important;
border-radius: 0 !important; height:50px !important}
.select2-container--default .select2-selection--single{border: 1px solid #999 !important;
border-radius: 0 !important; height:30px !important}
</style>
<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Edit Caller</h1>
        </div>
</div>
@if($msg != Null)
			<div class="alert alert-danger">{{ $msg }}</div>
            @endif
            <form class="form-horizontal" action="{{ route('attendance.UpdateCallerSave') }}"  method="POST">
            @csrf
			  <?php
              
 if(Auth::user()->user_type == 'admin'){
 ?>   
			  <div class="col-sm-12" >
              <div class="panel-body">
                <div class="form-group">
                    <label class="control-label" for="type">{{__('Store Name')}}</label>
                    <select class="form-control demo-select2-placeholder" name="store" id="Store" required>
                         <option value="1">Amit Book Depot</option>
                    </select>
                </div>
			  </div>
	<?php }else{ ?>		
	
	<input type="hidden" name="store_id" value="1" />
	<?php } ?>  
			   <div class="col-sm-12">
				   
				   <div class="form-group col-sm-3">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Customer Mobile1 * :</label>
								<input type='text' name="mobile" id="mobile1"  class="form-control" value="{{ $calleruser->mobile1}}" required="required"  maxlength="10" />
							</div>
							</div>
						</div>
						
						
					 <div class="form-group col-sm-3">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Customer Name* :</label>
								<input type='text' name="c_name" value="{{ $calleruser->name}}"  id="cust_name"  class="form-control" required="required" /-->
								
								<input type='hidden' name="callerid"  value="{{$id}}" class="form-control" required="required" /-->
							</div>
							</div>
						</div>
				   
				   
				   
				   
				   	<div class="form-group col-sm-3">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Category :</label>
								
								<select name="category" id="category"  class="form-control">
								$calleruser->category
								<option value="">{{__('Select Category')}}</option>
                            @foreach(\App\CustomerCategory::all() as $category)
								<option value="{{$category->id}}" <?php if($calleruser->category == $category->id ) { echo 'selected';  } ?>>{{__($category->name)}}</option>
							@endforeach
								</select>
								
								
							</div>
							</div>
						</div>
	 	<div class="form-group col-sm-3">
							<div class="row">
							<div class="form-group col-sm-12">
							<label>Institutes :</label>						
						<select required name="institute" id="institute" class="form-control">
							<option value="">Select Institutes</option>
                            @foreach(\App\Institute::all() as $institute)
								<option value="{{$institute->id}}"<?php if($calleruser->institute == $institute->id ) { echo 'selected';  } ?>>{{__($institute->name)}}</option>
							@endforeach
						</select>

		</div>
							</div>
						</div>
											
						
				   </div>
				   <div class="col-sm-12">
				   
				   <div class="form-group col-sm-4">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Mobile2 :</label>
								<input type='text' value="{{ $calleruser->mobile2 }}"  id="mobile2" name="mobile2" class="form-control" />
							</div>
							</div>
						</div>
						
						
						<div class="form-group col-sm-4">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Email :</label>
								<input type='email' id="email" value="{{ $calleruser->email}}"  name="email" class="form-control" />
							</div>
							</div>
						</div>
				     
	
						
						
					<div class="form-group col-sm-4">
							<div class="row">
							<div class="form-group col-sm-12">
								<div class="form-group col-sm-12">
								<label>City :</label>
								<input type='text' name="city" value="{{ $calleruser->city}}"  id="city" class="form-control" />
							</div>
							</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12">
					
						<div class="form-group col-sm-4">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Zip Code :</label>
								<input type='text' name="zipcode" value="{{ $calleruser->zipcode}}"  id="zipcode" class="form-control" />
							</div>
							</div>
						</div>
						
						<div class="form-group col-sm-4">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>State :</label>
								<select name="state" id="state" class="form-control" >
                                    <option value="">{{__('State')}}</option>
                                    @foreach(\App\State::all() as $state)
                                        <option value="{{$state->name}}"<?php if($calleruser->state == $state->name ) { echo 'selected';  } ?>>{{__($state->name)}}</option>
                                    @endforeach
                                </select>
							</div>
							</div>
						</div>
						
							  <div class="form-group col-sm-3">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Address1 :</label>
								<input type='text' id="address1" value="{{ $calleruser->address}}"  name="address" class="form-control" />
							</div>
							</div>
						</div>
						
		</div>
		<div class="col-sm-12">
		<div class="form-group col-sm-12">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Comments :</label>
								<textarea name="comment" id="comment" value="{{ $calleruser->comment}}"  class="form-control ckeditor">{{ $calleruser->comment}}</textarea>
								
							</div>
							</div>
						</div>	
					<?php
		
		//print_r($allopen); InvoiceLookupType
		?>	
						
				<div class="form-group col-sm-12">
							<div class="row">
							<div class="form-group col-sm-12">
								<input type="submit" class="btn btn-primary" value="Save and Next" name="save" />
								
							</div>
							</div>
						</div>	
									

</div>
	  </form>	
	  
	  
 @endsection

@section('script')

<script type="text/javascript">

</script>

@endsection