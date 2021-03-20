@extends('layouts.app')

@section('content')
  <div class="row">
        <div class="col-lg-12 pull-right">
            <a href="{{ route('institutes.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Institutes')}}</a>
        </div>
    </div>
<div class="row">
	<!-- form class="form form-horizontal mar-top" action="{{ route('customercategories.store') }}" method="POST" enctype="multipart/form-data" id="choice_form" -->
		@csrf
		
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Institutes')}}</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
                <thead>
					<tr>
						<th>{{_('S.No')}}</th>
						<th>{{_('Name')}}</th>
						
					</tr>
                </thead>
                <tbody>
					
                @foreach($institutes as $key => $institute)
                <tr>
				<td>{{$key+1}}</td>
                <td>{{ $institute->name }}</td>
                
                <td>
                <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('institutes.edit', encrypt($institute->id))}}">{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('institutes.destroy', $institute->id)}}');">{{__('Delete')}}</a></li>
                                </ul>
                            </div>
                </td>
                </tr>
                @endforeach
                </tbody>
                
                </table>
                
			</div>
			{!! $institutes->links() !!}
		</div>

</div>


@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection


