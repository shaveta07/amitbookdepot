@extends('layouts.app')

@section('content')

<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Take Action Leave')}}</h3><?php if(Auth::user()->user_type !="admin" )die("Unauthorised Access !!");?>
        </div><?php echo $msg; ?> 
                        <form method="post" class="form-horizontal" action="{{ route('attendance.takeactionleaveSubmit')}}">
                        @csrf
                        <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="name">{{__('Select Status')}}</label>
                            <div class="col-sm-10">
                            <select required="required" name="status" id="status" class="form-control">
                            <option value="">Select</option>
                            <option value="A">Approve</option>
                            <option value="R">Reject</option>
                            </select>
                            </div>
                        </div>
                       
                        <input type="hidden" value="{{$id}}" name="id">
                        <button type="submit" name="save" value="save" class="btn btn-primary">Save</button>
                        </form> 
                   
                 </div>
            </div>
           

@endsection

@section('script')
<script type="text/javascript">

</script>


@endsection