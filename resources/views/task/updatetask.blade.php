@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Update Task')}}</h3>
        </div>   <?php if(isset($_GET['sts']) && $_GET['sts'] == 'success'){ ?>
						<div class="alert alert-success">
  <strong>Success!</strong> Task Updated Successfully.
</div>
<?php } ?>
 <?php if(isset($_GET['sts']) && $_GET['sts'] == 'unauth'){ ?>
						<div class="alert alert-danger">
  <strong>Warning!</strong> You are not authorise user to update this task.
</div>
<?php } ?>
 <?php if(isset($_GET['sts']) && $_GET['sts'] == 'overdue'){ ?>
						<div class="alert alert-danger">
  <strong>Error!</strong> Due Date will Always greater then start date.
</div>
<?php } 
$store_id = Auth::user()->store_id;?>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('Task.update')}}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Title(*)')}}</label>
                    <div class="col-sm-10">
					<input required type="text" value="<?php echo $r->title; ?>" name="title" id="title" class="form-control">
                    </div>
                </div>
              
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Task Description(*)')}}</label>
                    <div class="col-sm-10">
                    <textarea rows="4" cols="20" name="description" class="editor" data-buttons='bold,underline,italic,hr,|,ul,ol,|,align,paragraph,|,image,table'>
                    <?php echo $r->description; ?>
                    </textarea>
                   
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-sm-2 control-label">{{__('File')}}</label>
                    <div class="col-sm-10">
                    <input type="hidden" name="previous_photos[]" value="{{ $r->images }}">
                        <input type="file" name="images[]" id="images"  class="form-control" multiple="">       		
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Is Recursive Task')}}</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="recursive" id="recursive">
									<option value="no" <?php if($r->isrecursive == 'no'){ echo "selected"; } ?>>No</option>
									<option value="yes" <?php if($r->isrecursive == 'yes'){ echo "selected"; } ?>>Yes</option>
									
								</select>
                    </div>
                </div>
                <input type="hidden" id="id" name="id" value="{{$id}}"  />
				<input type="hidden" id="hstatus" name="status" value="Y"  />
				<div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Recursive Interval')}}</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="recursivetype" id="recursivetype">
                    <?php for($i = 1; $i<=30;$i++): ?>
                        <option value="<?= $i ?>" <?php if($r->recursivetype == $i){ echo "selected"; } ?>><?= $i ?></option>
					<?php endfor; ?>
									
								</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Recursive Date')}}</label>
                    <div class="col-sm-10">
					    <input type="date" value="<?= $r->rdate ?>" class="form-control" name="rdate" id="rdate" />
                    </div>
                </div>
              
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Status')}}</label>
                    <div class="col-sm-10">
                    <select name="status" class="form-control" id="status">
                    <option value="open" <?php if($r->status == "open"){echo "selected"; } ?>>Open</option>
								<option value="completed" <?php if($r->status == "completed"){echo "selected"; } ?>>Completed</option>
								<option value="close" <?php if($r->status == "close"){echo "selected"; } ?>>Close</option>
								<option value="cancel" <?php if($r->status == "cancel"){echo "selected"; } ?>>Cancel</option>
								<option value="pending" <?php if($r->status == "pending"){echo "selected"; } ?>>Pending</option>
					</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Start Date(*)')}}</label>
                    <div class="col-sm-10">
                    <input type="text" required readonly data-provide="datepicker" value="<?php echo $r->startdate; ?>" name="startdate" id="startdate" class="form-control"> 
                    </div>
                </div>
              
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Due Date(*)')}}</label>
                    <div class="col-sm-10">
                        <input type="text" required readonly data-provide="datepicker" value="<?php echo $r->duedate; ?>" name="duedate" id="duedate" class="form-control">
                    	<input type="hidden" name="createdby" value="<?php echo $r->createdby; ?>" />
                    </div>
                </div>
                <?php
                        $rrra = DB::table('users')->get();                       
                       if(Auth::user()->store_id != 1){
                        $rrra = DB::table('users')->where('store_id',$store_id)->get();     
							
							}
						?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Assign To')}}</label>
                    <div class="col-sm-10">
                    <select name="assignto" class="form-control" id="assignto">
									<option value="">Select Employee</option>
									<?php 
									foreach($rrra as $r1):
									?>
								<option value="<?php echo $r1->id; ?>" <?php if($r1->id == $r->assignto) { echo "selected";} ?> ><?php echo $r1->email; ?></option>
								<?php endforeach; ?>
								</select>
                    </div>
                </div>
              
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" name="subform" type="submit">{{__('Submit')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>


@endsection

@section('script')

<script type="text/javascript">

$('#recursive').change(function(e){
	//alert('testt');
	if($(this).val() == 'yes'){
		$('.recursivetype').show();
		$('.rdate').show();
		}else{
		$('.recursivetype').hide();	
		$('.rdate').hide();
		}
});
var input = document.getElementById('images');
var list = document.getElementById('fileList');

//empty list for now...
while (list.hasChildNodes()) {
	list.removeChild(ul.firstChild);
}

//for every file...
for (var x = 0; x < input.files.length; x++) {
	//add to list
	var li = document.createElement('li');
	li.innerHTML = 'File ' + (x + 1) + ':  ' + input.files[x].name;
	list.append(li);
}

</script>

@endsection