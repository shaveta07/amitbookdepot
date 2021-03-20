@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Create Warning Message')}}</h3>
        </div>  <?php if(isset($sts) && $sts == 'success'){ ?>
						<div class="alert alert-success">
  <strong>Success!</strong> Warning Message added Successfully.
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
        <form class="form-horizontal" action="{{ route('Task.wrnuptstore')}}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
              
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Warning Message(*)')}}</label>
                    <div class="col-sm-10">
                    <textarea rows="4" cols="20" name="warnmsg" class="editor" data-buttons='bold,underline,italic,hr,|,ul,ol,|,align,paragraph,|,image,table'><?= $r->Warning_msg ?></textarea>
                   <input type="hidden" value="{{$r->id}}" name="id">
                    </div>
                </div>
				
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Status')}}</label>
                    <div class="col-sm-10">
                    <select name="status" class="form-control" id="status">
                    <option value="publish" <?php if($r->status == 'publish'){ echo "selected"; } ?>>Publish</option>
								<option value="unpublish" <?php if($r->status == 'publish'){ echo "selected"; } ?>>Unpublish</option>
					</select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Publish date(*)')}}</label>
                    <div class="col-sm-10">
                    <input type="date" required value="<?= $r->publishdate ?>"    name="publishdate" id="publishdate" class="form-control"> 
                    </div>
                </div>
              
              
                <?php
                        $rrr = DB::table('users')->get();                       
                       if(Auth::user()->store_id != 1){
                        $rrr = DB::table('users')->where('store_id',$store_id)->get();     
							
							}
						?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Assign To')}}</label>
                    <div class="col-sm-10">
                    <select name="assignto" class="form-control" id="assignto">
									<option value="">Select Employee</option>
									<?php 
									foreach($rrr as $r1):
									?>
								<option value="<?php echo $r1->id; ?>" <?php if($r->assignto == $r1->id ){echo "selected"; } ?> ><?php echo $r1->email; ?></option>
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