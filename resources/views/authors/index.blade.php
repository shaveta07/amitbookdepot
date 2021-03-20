@extends('layouts.app')

@section('content')
  <div class="row">
        <div class="col-lg-12 pull-right">
            <a href="{{ route('authors.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Publisher')}}</a>
        </div>
    </div>
<div class="row">
	
		@csrf
		
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Publisher')}}</h3>
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
					
                @foreach($authors as $key => $author)
                <tr>
				<td>{{$key+1}}</td>
                <td>{{ $author->name }}</td>
                
                <td>
                <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('authors.edit', encrypt($author->id))}}">{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('authors.destroy', $author->id)}}');">{{__('Delete')}}</a></li>
                                </ul>
                            </div>
                </td>
                </tr>
                @endforeach
                </tbody>
                
                </table>
                
			</div>
			{!! $authors->links() !!}
		</div>

</div>


@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection


