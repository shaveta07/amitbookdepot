@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Add Task')}}</h3>
        </div>  <?php if(isset($sts) && $sts == 'success'){ ?>
						<div class="alert alert-success">
  <strong>Success!</strong> Task Added Successfully.
</div>
<?php } ?>
 <?php if(isset($sts) && $sts == 'overdue'){ ?>
						<div class="alert alert-danger">
  <strong>Error!</strong> Due Date will Always greater then start date.
</div>
<?php } 
$store_id = Auth::user()->store_id;?>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('Task.store')}}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Title(*)')}}</label>
                    <div class="col-sm-10">
					<input required type="text" name="title" id="title" class="form-control">
                    </div>
                </div>
              
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Task Description(*)')}}</label>
                    <div class="col-sm-10">
                    <textarea rows="4" cols="20" name="description" class="editor" data-buttons='bold,underline,italic,hr,|,ul,ol,|,align,paragraph,|,image,table'></textarea>
                   
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-sm-2 control-label">{{__('File')}}</label>
                    <div class="col-sm-10">
                        <input type="file" name="images[]" id="images"  class="form-control" multiple="">       		
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Is Recursive Task')}}</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="recursive" id="recursive">
									<option value="no">No</option>
									<option value="yes">Yes</option>
									
								</select>
                    </div>
                </div>
				<input type="hidden" id="hstatus" name="status" value="Y"  />
				<div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Recursive Interval')}}</label>
                    <div class="col-sm-10">
                    <select class="form-control" name="recursivetype" id="recursivetype">
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
									<option value="25">25</option>
									<option value="26">26</option>
									<option value="27">27</option>
									<option value="28">28</option>
									<option value="29">29</option>
									<option value="30">30</option>
									
								</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Recursive Date')}}</label>
                    <div class="col-sm-10">
					    <input type="date" class="form-control" name="rdate" id="rdate" />
                    </div>
                </div>
              
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Status')}}</label>
                    <div class="col-sm-10">
                    <select name="status" class="form-control" id="status">
								<option value="open">Open</option>
								<option value="completed">Completed</option>
								<option value="close">Close</option>
								<option value="cancel">Cancel</option>
								<option value="pending" selected>Pending</option>
					</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Start Date(*)')}}</label>
                    <div class="col-sm-10">
                    <input type="text" required readonly data-provide="datepicker" value="<?php echo date('Y-m-d H:i:s'); ?>" name="startdate" id="startdate" class="form-control"> 
                    </div>
                </div>
              
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Due Date(*)')}}</label>
                    <div class="col-sm-10">
                        <input type="date" required  name="duedate" id="duedate" class="form-control">
                    </div>
                </div>
                <?php
                        $rrr = DB::table('users')->get();                       
                       if(Auth::user()->store_id != 1){
                        $rrr = DB::table('users')->where('store_id',$store_id)->get();     
							
							}
						?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Assign To(*)')}}</label>
                    <div class="col-sm-10">
                    <select name="assignto" required class="form-control" id="assignto">
									<option value="">Select Employee</option>
									<?php 
									foreach($rrr as $r):
									?>
								<option value="<?php echo $r->id; ?>" ><?php echo $r->email; ?></option>
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