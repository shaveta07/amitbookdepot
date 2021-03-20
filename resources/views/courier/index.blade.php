@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('couriers.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Courier')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Couriers')}}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_couriers" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder=" Type name & Enter">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Courier Name')}}</th>
                    <th>{{__('Link')}}</th>
                    <th>{{__('Created_at')}}</th>
                    <th>{{__('Created_by')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($couriers as $key => $courier)
                    <tr>
                        <td>{{ ($key+1) + ($couriers->currentPage() - 1)*$couriers->perPage() }}</td>
                        <td>{{$courier->courier_name}}</td>
                        <td>{{$courier->link}}</td>
                        <td> {{$courier->created_at}}</td>
                        <td> {{$courier->createdby}}</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('couriers.edit', encrypt($courier->id))}}">{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('couriers.destroy', $courier->id)}}');">{{__('Delete')}}</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $couriers->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script type="text/javascript">
        function sort_couriers(el){
            $('#sort_couriers').submit();
        }
    </script>
@endsection
