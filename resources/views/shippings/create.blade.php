@extends('layouts.app')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
	<form class="form form-horizontal mar-top" action="{{ route('shippings.store') }}" method="POST" enctype="multipart/form-data" id="choice_form">
		@csrf
		
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Shipping Information')}}</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-2 control-label">{{__('Start PIN')}}</label>
					<div class="col-lg-3">
						<input type="text" class="form-control" name="startpin" placeholder="{{__('Start PIN')}}" required>
					</div>
					
					<label class="col-lg-3 control-label">{{__('End PIN')}}</label>
					<div class="col-lg-4">
						<input type="text" class="form-control" name="endpin" placeholder="{{__('End PIN')}}" required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-2 control-label">{{__('Shipping Price')}}</label>
					<div class="col-lg-2">
						<input type="text" class="form-control" name="price" placeholder="{{__('Shipping Price')}}" required>
					</div>
					
					<label class="col-lg-2 control-label">{{__('Is COD')}}</label>
					<div class="col-lg-2">
						<select class="form-control" name="iscod" placeholder="{{__('Is COD')}}" required>
							<option value="no">No</option>
							<option value="yes">Yes</option>
						</select>
					</div>
					
					<label class="col-lg-2 control-label">{{__('COD Price')}}</label>
					<div class="col-lg-2">
						<input type="text" class="form-control" name="codprice" placeholder="{{__('COD Price')}}" required>
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

