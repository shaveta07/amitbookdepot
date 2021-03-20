@extends('layouts.app')

@section('content')

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Courier Services')}}</h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('couriers.store') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Courier Name')}}</label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="{{__('Courier Name')}}" id="couriername" name="courier_name" class="form-control" required>
                    </div>
                </div>
               
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Link')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="link" placeholder="{{__('link')}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Description')}}</label>
                    <div class="col-sm-10">
                        <textarea name="description" rows="8" class="form-control"></textarea>
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
