@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('shippings.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Pin Range')}}</a>
    </div>
</div>
<div class="row">
	<form class="form form-horizontal mar-top" action="{{ route('shippings.store') }}" method="POST" enctype="multipart/form-data" id="choice_form">
		@csrf
		
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Shipping Information')}}</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
                <thead>
					<tr>
						<th>{{_('S.No')}}</th>
						<th>{{_('Start PIN')}}</th>
						<th>{{_('End PIN')}}</th>
						<th>{{_('Shipping Price')}}</th>
						<th>{{_('Is COD')}}</th>
						<th>{{_('COD Price')}}</th>
						<th>{{_('Action')}}</th>
					
					</tr>
                </thead>
                <tbody>
					
                @foreach($shippings as $key => $shipping)
                <tr>
				<td>{{$key+1}}</td>
                <td>{{ $shipping->startpin }}</td>
                <td>{{ $shipping->endpin }}</td>
                <td>{{ $shipping->price }}</td>
                <td>{{ ucfirst($shipping->iscod) }}</td>
                <td>{{ $shipping->codprice }}</td>
                <td>
                <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('shippings.edit', encrypt($shipping->id))}}">{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('shippings.destroy', $shipping->id)}}');">{{__('Delete')}}</a></li>
                                </ul>
                            </div>
                </td>
                </tr>
                @endforeach
                </tbody>
                </table>
			</div>
			
		</div>
	</form>
</div>


@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection


