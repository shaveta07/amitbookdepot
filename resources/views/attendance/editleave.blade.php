@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Edit Leave')}}</h3>
        </div><?php echo $msg; ?> 

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('attendance.EditLeaveSubmit')}}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
              
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Description')}}</label>
                    <div class="col-sm-10">
					<textarea class="form-control" row='8' required name="desc" id="description"><?php echo $arr->Description; ?></textarea>
                    </div>
                </div>
				<input type="hidden" id="hstatus" name="status" value="Y"  />
				<div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Document')}}</label>
                    <div class="col-sm-10">
					<div id="photos">
					@if(is_array(json_decode($arr->document)))
					@foreach (json_decode($arr->document) as $key => $photo)
						<div class="col-md-4 col-sm-4 col-xs-6">
							<div class="img-upload-preview">
								<img loading="lazy"  src="{{ asset($photo) }}" alt="" class="img-responsive">
								<input type="hidden" name="previous_photos[]" value="{{ $photo }}">
								<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
							</div>
						</div>
					@endforeach
				@endif
				</div>
                    </div>
                </div>
            </div>
			<input type="hidden" class="form-control"  name="rowid" id="rowid" value="{{ $arr->RowID}}">
			<input type="hidden" id="hstatus" name="status" value="Y"  />
            <div class="panel-footer text-right">
                <button class="btn btn-purple" name="punch" type="submit">{{__('Update')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

@endsection

@section('script')

<script type="text/javascript">
$("#photos").spartanMultiImagePicker({
			fieldName:        'photos[]',
			maxCount:         10,
			rowHeight:        '100px',
			groupClassName:   'col-md-8 col-sm-8 col-xs-6',
			maxFileSize:      '',
			dropFileLabel : "Drop Here",
			onExtensionErr : function(index, file){
				console.log(index, file,  'extension err');
				alert('Please only input png or jpg type file')
			},
			onSizeErr : function(index, file){
				console.log(index, file,  'file size too big');
				alert('File size too big');
			}
		});
		</script>

@endsection