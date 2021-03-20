@extends('layouts.app')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
	<form class="form form-horizontal mar-top" action="{{ route('shippings.update', $shipping->id) }}" method="POST" enctype="multipart/form-data" id="choice_form">
		@csrf
		
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Shipping Information')}}</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-2 control-label">{{__('Start PIN')}}</label>
					<div class="col-lg-2">
						<input type="text" class="form-control" value="{{$shipping->startpin}}" name="startpin" placeholder="{{__('Start PIN')}}" required>
					</div>
					
					<label class="col-lg-2 control-label">{{__('End PIN')}}</label>
					<div class="col-lg-2">
						<input type="text" class="form-control" value="{{$shipping->endpin}}"  name="endpin" placeholder="{{__('End PIN')}}" required>
					</div>

					<label class="col-lg-2 control-label">{{__('COD Price')}}</label>
					<div class="col-lg-2">
						<input type="text" class="form-control" value="{{$shipping->codprice}}" name="codprice" placeholder="{{__('COD Price')}}" required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-2 control-label">{{__('Shipping Price')}}</label>
					<div class="col-lg-2">
						<input type="text" class="form-control" value="{{$shipping->price}}"  name="price" placeholder="{{__('Shipping Price')}}" required>
					</div>

					<!-- <label class="col-lg-2 control-label">{{__('Shipping Type')}}</label>
					<div class="col-lg-2">
						<select class="form-control" name="shipping_type"  placeholder="{{__('Shipping Type')}}" required>
							<option <?php if($shipping->shipping_type == 'product'){ echo "selected"; } ?> value="product">Product</option>
							<option <?php if($shipping->shipping_type == 'order'){ echo "selected"; } ?> value="order">Order</option>
						</select>
					</div> -->
					
					<label class="col-lg-2 control-label">{{__('Is COD')}}</label>
					<div class="col-lg-2">
						<select class="form-control" name="iscod"  placeholder="{{__('Is COD')}}" required>
							<option <?php if($shipping->iscod == 'no'){ echo "selected"; } ?> value="no">No</option>
							<option <?php if($shipping->iscod == 'yes'){ echo "selected"; } ?> value="yes">Yes</option>
						</select>
					</div>
					
				</div>
			</div>
			<div class="panel-footer text-right">
				<button type="submit" name="button" class="btn btn-info">{{ __('Save') }}</button>
			</div>
		</div>
	</form>
</div>


@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection

