@extends('layouts.app')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
	<form class="form form-horizontal mar-top" action="{{ route('customercategories.update', $customercategories->id) }}" method="POST" enctype="multipart/form-data" id="choice_form">
		@csrf
		
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Shipping Information')}}</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-2 control-label">{{__('Customer Category')}}</label>
					<div class="col-lg-6">
						<input type="text" class="form-control" value="{{ $customercategories->name}}" name="name" placeholder="{{__('Customer Category')}}" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">{{__('Customer Type')}}</label>
					<div class="col-lg-6">
						
						<select class="form-control" name="customertype">
							<option <?php if($customercategories->customertype == 'C'){echo "selected"; } ?> value="C">Credit</option>
							<option <?php if($customercategories->customertype == 'S'){echo "selected"; } ?> value="S">Standard</option>
							
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-2 control-label">{{__('Is Deleted')}}</label>
					<div class="col-lg-6">
						<select class="form-control" name="isdeleted">
							<option <?php if($customercategories->isdeleted == 'N'){echo "selected"; } ?> value="N">No</option>
							<option <?php if($customercategories->isdeleted == 'Y'){echo "selected"; } ?> value="Y">Yes</option>
							
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

