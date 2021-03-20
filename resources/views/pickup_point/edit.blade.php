@extends('layouts.app')

@section('content')

    <div class="col-lg-8 col-lg-offset-2">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('Update Pickup Point Information')}}</h3>
            </div>

            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="{{ route('pick_up_points.update',$pickup_point->id) }}" method="POST" enctype="multipart/form-data">
            	<input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{__('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Name')}}" id="name" name="name" value="{{ $pickup_point->name }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="address">{{__('Location')}}</label>
                        <div class="col-sm-9">
                            <textarea name="address" rows="8" class="form-control" required>{{ $pickup_point->address }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="address">{{__('State')}}</label>
                        <div class="col-sm-9">
                        <select onChange="getdistrict(this.value);" class="form-control custome-control" data-live-search="true" id="state" name="state" required>
                                        <option value="">Select State</option>
                                        @foreach (\App\State::where('isactive', 'yes')->get() as $key => $state)
                                        <option value="{{ $state->id }}" @if ($pickup_point->state == $state->name) selected @endif>{{ $state->name }}</option>
                                        @endforeach
                         </select>
                        </div>
                    </div>
                    <div class="form-group">
                    <label class="col-sm-3 control-label" for="address">{{__('District')}}</label>
                        <div class="col-sm-9">
                        <select name="district" id="district-list" class="form-control" required>
                                         
                                            @foreach (\App\District::get() as $key => $district)
                                            <option value="{{ $district->id }}" {{ $pickup_point->district == $district->DistrictName ? 'selected="selected"' : '' }}>{{ $district->DistrictName }}</option>
                                            
                                            @endforeach
                                         
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="phone">{{__('Phone')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Phone')}}" id="phone" name="phone" value="{{ $pickup_point->phone }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{__('Pickup Point Status')}}</label>
                        <div class="col-sm-3">
                            <label class="switch" style="margin-top:5px;">
                            		<input value="1" type="checkbox" name="pick_up_status"@if ($pickup_point->pick_up_status == 1) checked @endif>
                            		<span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="name">{{__('Pick-up Point Manager')}}</label>
                        <div class="col-sm-9">
                            <select name="staff_id" required class="form-control demo-select2-placeholder">
                                @foreach(\App\Staff::all() as $staff)
                                    <option value="{{$staff->id}}" @if ($pickup_point->staff_id == $staff->id) selected @endif>{{$staff->user->name}}</option>
                                @endforeach
                            </select>
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

function getdistrict(val) {
    $.post( "{{ route('checkout.getdistrict') }}",{ state_id: val,_token: "{{ csrf_token() }}" }, function( data ) {
       data = JSON.parse(data);
       // console.log(data.data);
       var formoption = "";
        $.each( data.data, function( key, value ) {
            //console.log(value.id + ": " + value.name );
            formoption += "<option value='" + value.id + "'>" + value.name+ "</option>";
          
        });
        $("#district-list").html(formoption);
	
	});
}
</script>
@endsection
