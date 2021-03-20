@extends('layouts.app')

@section('content')

<div class="col-lg-10 col-lg-offset-1">
	<form class="form form-horizontal mar-top" action="{{ route('institutes.update', $institutes->id) }}" method="POST" enctype="multipart/form-data" id="choice_form">
		@csrf
		
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Institute')}}</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-lg-2 control-label">{{__('Institute')}}</label>
					<div class="col-lg-6">
						<input type="text" class="form-control" value="{{ $institutes->name}}" name="name" placeholder="{{__('Institute')}}" required>
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

