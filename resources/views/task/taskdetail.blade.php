@extends('layouts.app')

@section('content') 
<?php
$assign = DB::table('taskissues')->select('assignfrom','assignto')->where('taskid',$id)->orderBy('id','desc') ->skip(0)->take(1)->first();
if($assign)
{
    $assignfrom = $assign->assignfrom;
    $assignto = $assign->assignto;
}
?>
<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Task detail - <?php echo $task->title; 
                    $date1=date_create($task->startdate);
					$date2=date_create($task->duedate);
					$diff=date_diff($date1,$date2);
					if($diff->format("%R%a") > 0){
					echo " &nbsp;&nbsp;".$diff->format("%R%a days left");
					}else{
					echo " &nbsp;&nbsp;<span style='color:red'>".$diff->format("%R%a days (Task overdue)")."</span>";
						}
                     ?></h1>
                    <?php if(isset($sts) && $sts == 'success'){ ?>
						<div  class="alert alert-success">
					  <strong>Success!</strong> Task Updated Successfully.
					</div>
					<?php }elseif(isset($sts) && $sts == 'unauth'){
						?>
						<div  class="alert alert-danger">
					  <strong>Error!</strong> You are unauthorise to update this Task.
					</div>
						
					<?php	} ?>

                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div style="clear:both;"></div>
            <?php
            //list($title,$smsT,$emailT,$status,$default_active) = get_query_list($con,"SELECT title,sms_template,email_template,status,default_active FROM `settings` where id = 1");
            $r = DB::table('tasks')->where('id',$id)->first();
            $id = $id;
           
            ?>
            <div class="taskDetail">
           
               <div class="col-sm-12" style="border-bottom:2px solid #ccc">
				
				<table  class="table table-hover  table-sm table-inverse">
				<tr style="background-color:#BDE5F8"><td class=""><label>Title:</label></td><td class=""><?php echo $r->title; ?></td></tr>
				<tr><td><label>Description:</label></td><td><?php echo $r->description; ?></td></tr>
				<tr style="background-color:#BDE5F8"><td><label>Files:</label></td><td>
				<div class="row" style="padding-left:15px;">
								
                            <?php $files = DB::table('tasks_attachment')->where('taskid',$id)->get();
                           
							if(count($files)):
							
							foreach($files as $f):
							 $fl = json_decode($f->filename);
							foreach($fl as $fe):
							//	echo $fe;
								?>
							
							<span class="files"><a target="_blank" href="<?php echo url('/public/task/'.$fe); ?>"><?php echo $fe; ?></a></span><br/>
							<?php

							endforeach;
							
							endforeach;
							endif;
							?>
							</div>
				</td></tr>
				<tr><td><label>Status:</label></td><td><?php echo $r->status; ?></td></tr>
				<?php
                 $assign = DB::table('taskissues')->select('assignfrom','assignto')->where('taskid',$r->id)->orderBy('id','desc') ->skip(0)->take(1)->first();
                 $assignfrom = $assign->assignfrom;
                 $assignto = $assign->assignto;
                 $assignfromEmail = DB::table('users')->select('email')->where('id',$assignfrom)->first();
                $assignfromEmail->email;
                $assigntoEmail = DB::table('users')->select('email')->where('id',$assignto)->first();
                $assigntoEmail->email;
                $createdby = DB::table('users')->select('email')->where('id',$r->createdby)->first();
				
				?>
				<tr style="background-color:#BDE5F8"><td><label>Assigned From:</label></td><td><?php echo $assignfromEmail->email; ?></td></tr>
				<tr><td><label>Assign To:</label></td><td><?php echo $assigntoEmail->email; ?></td></tr>
				<tr style="background-color:#BDE5F8"><td><label>Created Date:</label></td><td><?php echo date_format(date_create($r->createddate),"d-M-Y H:i:s"); ?></td></tr>
				<tr><td><label>Start Date:</label></td><td><?php echo date_format(date_create($r->startdate),"d-M-Y H:i:s"); ?></td></tr>
				<tr style="background-color:#BDE5F8"><td><label>Due Date:</label></td><td><?php echo date_format(date_create($r->duedate),"d-M-Y"); ?></td></tr>
				<tr><td><label>Created By:</label></td><td><?php echo $createdby->email; ?></td></tr>
				
				</table>
				
            </div>
            <div style="clear:both;"></div>
            <!----------------- Comments Listing ------------------------------------------------------------>
            <div class="commentlisting" style="border-bottom:2px solid #ccc">
				<?php
                $comments = DB::table('taskscomment')->where('taskid',$id)->get();
               
				if(sizeof($comments) > 0):
				echo '<h2 style="margin-top:20px; margin-bottom:20px; color:#ec8080">Task Comments</h2>';
				foreach($comments as $comment):
				?>
				
				<div class="row"style="border-bottom:1px solid #ec8080">
					<table class="table">
                        <tr><td><label>Commented By:</label></td><td><?php 
                        $commentedBy = DB::table('users')->where('id',$comment->commentedby)->first();
						echo $commentedBy->email; ?></td></tr>
						<tr><td><label>Commented Date:</label></td><td><?php 
						$date=date_create($comment->commenteddate);
						echo date_format($date,"d-M-Y H:i:s");
						
						 ?></td></tr>
						<tr><td><label>Commentes</label></td><td><?php echo $comment->comments; ?></td></tr>
						<?php
                        $files = DB::table('taskscomment_attachment')->where('commentsid',$comment->id)->get();
                       
						if(count($files)>0){
						?>
						<tr><td><label>Files:</label></td><td>
						<?php
						
						foreach($files as $file):
							$fl = json_decode($file->attachment);
							foreach($fl as $fe):
						?>
						<span class="files"><a target="_blank" href="<?php echo url('/public/task/'.$fe) ?>"><?php echo $fe; ?></a></span><br/>
						<?php
						 endforeach;
					 endforeach; ?>
						</td></tr>
						<?php } ?>
					</table>
				</div>
				<?php endforeach;
				endif; ?>
			</div>
            
            <!----------------- End Comments Listing ------------------------------------------------------------>
			<div style="clear:both;"></div>
			<h2 style="margin-top:20px; margin-bottom:20px; color:#ec8080">Update Task Status</h2>
			<?php
			$url = '/task/taskdetail/'.$id.'';
			?>
			 <form method="post" action="{{route('Task.subform')}}" enctype="multipart/form-data">
             @csrf
               <div class="col-sm-12">
						<div class="form-group col-sm-12">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Add Comments:</label>
								<textarea required name="comment" id="comment"  class="form-control"></textarea>
								<input type="hidden" value="{{$id}}" name="id">
							</div>
							</div>
						</div>
						<?php /* ?>
							<div class="form-group col-sm-12">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Assign To:</label>
								<?php
						$rrr = get_all_array($con,"select * from Users where Active='Y'");
						?>
								<select name="assignto" class="form-control" id="assignto">
									<?php 
									list($assignfrom,$assignto) = get_query_list($con,"select assignfrom,assignto from taskissues where taskid = '".$_GET['id']."' order by id desc limit 0,1");
	
									foreach($rrr as $r1):
									?>
								<option value="<?php echo $r1['RowId']; ?>"  <?php if($r1['RowId'] == $assignto) { echo "selected";} ?> ><?php echo $r1['Email']; ?></option>
								<?php endforeach; ?>
								</select>
								
								
								
							</div>
							</div>
						</div>
						<?php */ ?>
						<div class="form-group col-sm-12">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Change Status:</label>
								<select name="status" class="form-control" id="status">
								<option value="open" <?php if($r->status == "open"){echo "selected"; } ?>>Open</option>
								<option value="completed" <?php if($r->status == "completed"){echo "selected"; } ?>>Completed</option>
								<option value="close" <?php if($r->status == "close"){echo "selected"; } ?>>Close</option>
								<option value="cancel" <?php if($r->status == "cancel"){echo "selected"; } ?>>Cancel</option>
								</select>
								
							</div>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<div class="row">
							<div class="form-group col-sm-12">
								<label>Add Files:</label>
								<input type="file" name="filetoupload[]" id="filetoupload"  multiple="" class="form-control" />
								
							</div>
							</div>
						</div>
						
						<div class="form-group col-sm-12">
							<div class="row">
							<div class="form-group col-sm-12">
						<button name="subform" value="Submit" type="submit" class="btn btn-lg btn-info">Submit Comments</button>
							</div>
							</div>
						</div>	
						
					</div>
				</form>
					
			
			
		</div>
@endsection

@section('script')
<script type="text/javascript">
function deleteLeave(id){
	if(confirm('Are you sure to delete this record !')){
		location.href="{{url('/admin/attendance/delete')}}/"+id;
		}else{
		return false;	
		}
    }
//     $('#startdate').datepicker({ dateFormat: 'yy-mm-dd' });
// $('#enddate').datepicker({ dateFormat: 'yy-mm-dd' });
//$('#example').DataTable();
</script>


@endsection