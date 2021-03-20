@extends('layouts.app')

@section('content')
<style>
input,select,select2 select2-container{border: 1px solid #999 !important;
border-radius: 0 !important; height:30px !important}
.form-group {
	line-height: 5px !important;
	padding-bottom: 0px;
	margin-bottom: 5px;
}
textarea{border: 1px solid #999 !important;
border-radius: 0 !important; height:50px !important}
.select2-container--default .select2-selection--single{border: 1px solid #999 !important;
border-radius: 0 !important; height:30px !important}
fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
}

legend.scheduler-border {
    width:inherit; /* Or auto */
    padding:0 10px; /* To give a bit of padding on the left and right */
    border-bottom:none;
}

</style>
<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Calling</h1>
        </div>
 </div><?php
 if(Auth::user()->user_type == 'admin'){ 
			  
            ?>
			@if($msg != Null)
			<div class="alert alert-danger">{{ $msg }}</div>
            @endif
			
              <div class="col-sm-12">
               <form method="post" class="form-inline" action="{{route('attendance.repeatCategory')}}">
                 @csrf
				 <div class="form-group">
                   <label for="categoryagain">Categories for Repetation:</label>
                   <select required name="category" id="categoryagain"  class="form-control">
                               <option value="">Select Categories</option>
                               <?php 
                               for($i=0;$i<count($category_data);$i++)
                               {
                               ?>
                               <option data-type="<?php echo $category_data[$i]['customer_type']; ?>"  value="<?php echo $category_data[$i]['CategoryId']; ?>"><?php echo $category_data[$i]['Name']; ?></option>
                               <?php
                               }
                               ?>
                               </select>
                 </div>
                 
                 <button type="submit" name="repeat" class="btn btn-danger">Repeat again</button>
               </form> 
              </div>
              <div class="clear clearfix"></div><br/><br/>
              <?php
              }
           ?>
		   
		   <div class="panel-body" style="background: whitesmoke;">
            <form method="post"  action="{{route('attendance.callersave')}}" >
 			@csrf
			<div class="col-sm-12">
				<div class="form-group col-sm-4">
					<div class="row">
					<div class="form-group col-sm-12">
						<label>Category :</label>
						
						<select required name="category" id="category"  class="form-control" onchange="getcaller()">
						<option value="">Select Categories</option>
						<?php 
						for($i=0;$i<count($category_data);$i++)
						{
						?>
						<option data-type="<?php echo $category_data[$i]['customer_type']; ?>" value="<?php echo $category_data[$i]['CategoryId']; ?>"><?php echo $category_data[$i]['Name']; ?></option>
						<?php
						}
						?>
						</select>
						
						
					</div>
					</div>
				</div>
				<div class="form-group col-sm-3">
					<div class="row">
						<div class="form-group col-sm-12">
							<label>Institutes :</label>						
						<select name="institute" id="institute" class="form-control" onchange="getcaller()">
							<option value="">Select Institutes</option>
								<?php $institutes = DB::table('institutes')->get();
								foreach($institutes as $institute):
								?>
								<option value="<?= $institute->id ?>"><?= $institute->name ?></option>
								<?php endforeach; ?>
						</select>

						</div>
					</div>
				</div>
                              
				<div class="form-group col-sm-4">
					<div class="row">
						<div class="form-group col-sm-12">
								<label>Caller :</label>	
								<select required name="caller" id="caller" class="form-control" onchange="getCallerName()">
								<!-- option data-name='' value=''> Select Caller</option -->
								<?php
		$servername = env('DB_HOST');//$conf->host;
				$username = env('DB_USERNAME');//$conf->user;
				$password = env('DB_PASSWORD');//$conf->password;
				$dbname = env('DB_DATABASE');//$conf->db;
				$con = mysqli_connect($servername, $username, $password, $dbname);
				if (!$con) {
					die("Connection failed: " . mysqli_connect_error());
				}else{
					//echo "connected";
				}

				function get_all_array($conn,$selectquery)
				{
				$rrr=executequery($conn,$selectquery);
				$result=array();
				
					// return multi dimensions array  .. ie. [0][array] ...[1][array] ...
					while($m=fetchrecord($rrr))
					{
						$result[]=$m;
					}
				
				return $result;
				}	

				function executequery($conn,$string,$debug=0)
				{
							if ($debug == 1)
							print $string;
							if ($debug == 2)
							error_log($string);
							$result = mysqli_query($conn,$string);
							if (!$result) {
								printf("Error: %s\n", mysqli_error($conn));
								exit();
							}
							if ($result == false)
							{
									error_log( "SQL error: reading database");
							}
							return $result;
				}

				function fetchrecord($queryresult_string)
				{
					return mysqli_fetch_array($queryresult_string,MYSQLI_ASSOC);
				}
				
			function get_query_list($conn,$sql, $debug=0)
			{
			//echo $sql."<br/>";
					if ($debug == 1)
					echo $sql;
					if ($debug == 2)
					error_log($sql);
					//print_r($conn);
					$result = mysqli_query($conn,$sql);
					if ($result == false)
					{
							//--error_log( "SQL error: ".mysqli_error(). "nnOriginal query: $stringn");
							// Remove following line from production servers
							//-- die( "SQL error: ".mysqli_error(). "b<br>n<br>Original query: $string n<br>n<br>");
					}
					if (!$result) {
						printf("Error: %s\n", mysqli_error($conn));
						exit();
					}
					
					if($lst = mysqli_fetch_row($result))
					{
							mysqli_free_result($result);
							return $lst;/// returns records in rows
					}
					mysqli_free_result($result);
					return false;
			}


		?>
								<?php
								
								if(isset(Auth::user()->category_id)){
									$sql1 = "select * from calling_user where 1=1 and cancall='yes' ";
									$sql2 = "SELECT e.id as custid, u.name, u.email, u.phone,u.category_id,u.institute_id,u.id as uerid FROM `customers` AS e INNER JOIN `users` AS u ON e.user_id = u.id WHERE e.cancall = 'yes'";
									$where1 = '';$where2 = '';
									$category = Auth::user()->category_id;
									$institute = Auth::user()->institute_id;
									if($category == '' && $institute != ''){
									//$rrr = get_all_array($con,"select * from calling");	
									$where1 = " and institute = '$institute' ";	
									$where2 = " and u.institute_id = '$institute' ";	

										}
									if($institute == '' && $category != ''){
									$where1 = " and category = '$category' ";
									$where2 = " and u.category_id = '$category' ";
										}
										
									if($institute != '' && $category != ''){
										$where1 = " and category = '$category' and institute = '$institute' ";
										$where2 = " and u.category_id = '$category' and u.institute_id = '$institute' ";
										
									}

									$callingmobiles = get_all_array($con,"select mobile from calling");
									$mobiles = array();
									foreach($callingmobiles as $mo):
									$mobiles[] = $mo['mobile'];
									endforeach;
									if(count($mobiles) > 0){
									$where1 .= " and mobile1 NOT IN (".implode(',',$mobiles).")";
									$where2 .= " and u.phone NOT IN (".implode(',',$mobiles).")";
									}

									$sql1 = $sql1.$where1.' limit 0,1';
									$sql2 = $sql2.$where2.' limit 0,1';

									$data1 = get_all_array($con,$sql1);
									$data2 = get_all_array($con,$sql2);
									//print_r($data2);
									$callid = $calltype = '';
									$mobile=array();$i=0;
									foreach($data1 as $d1):
									$mobile[$i]['name'] = $d1['name'];
									$mobile[$i]['mobile1'] = $d1['mobile1'];
									$mobile[$i]['type'] = 'caller';
									$mobile[$i]['id'] = $d1['id'];
									$i++;
									endforeach;

									foreach($data2 as $d2):
									$mobile[$i]['name'] = $d2['name'];
									$mobile[$i]['mobile1'] = $d2['phone'];
									$mobile[$i]['type'] = 'customers';
									$mobile[$i]['id'] = $d2['custid'];
									$i++;
									endforeach;
									//echo "<option data-name='' value=''> Select Caller</option>";
									foreach($mobile as $mob):
									$callid = $mob['id'];
									$calltype = $mob['type'];
									echo "<option data-type='".$mob['type']."' data-id='".$mob['id']."' data-name='".$mob['name']."' value='".$mob['mobile1']."'>".$mob['mobile1']." - ".$mob['name']."</option>";
									break;
									endforeach;
									
								}
								?>
								
								</select>
								
						</div>
					</div>
				</div>
        	</div>  

			<div class="col-sm-12">
				<div class="form-group col-sm-4">
					<div class="row">
						<div class="form-group col-sm-12">
							<label>Name :</label>
							<input required type="text" name="name" class="form-control" id="name" />
						</div>
					</div>
				</div>
				<div class="form-group col-sm-4">
					<div class="row">
						<div class="form-group col-sm-12">
						<label>Status :</label>
							<select required name="status" id="status" class="form-control">
								<option value="unknown">Unknown</option>
								<option value="Do not pick">Do not pick</option>
								<option value="Switched Off">Switched Off</option>
								<option value="out of covarage area">Out of covarage area</option>
								<option value="call later">Call later</option>
								
								<option value="do not desturb">Do not desturb</option>
								<option value="done">Done</option>
								<option value="busy">Busy</option>
								<option value="wrong number">Wrong number</option>
								<option value="not interested">Not Interested</option>
								<option value="interested"> Interested</option>
								<!--
								//'Do not pick','Switched Off','out of covarage area','call later','unknown','do not desturb'
								-->
							
							</select>
						</div>
					</div>
				</div>
								
				<div class="form-group col-sm-4">
					<div class="row">
						<div class="form-group col-sm-12">
						<label>Reminder :</label>
								<input type="date" required name="reminder" class="form-control" id="reminder" />
								
						</div>
					</div>
				</div>
                  
			</div>
     
     
			<div class="col-sm-12">
				<div class="form-group col-sm-12">
              		<div class="row">
						<div class="form-group col-sm-12">
							<label>Comments :</label>
							<textarea name="comment"  required id="comment"  class="form-control ckeditor"></textarea>
							
						</div>
              		</div>
   				 </div>	
			</div>
      <?php

//print_r($allopen); InvoiceLookupType
?>	
          
  			<div class="form-group col-sm-12">
              	<div class="row">
					<div class="form-group col-sm-12">
						<input type="submit" class="btn btn-primary" value="Save and Next" name="save" />
						
					</div>
              </div>
   			 </div>	
                      
</form>	
</div>
<div style="marigin-top:10px;margin-bottom:10px">
	<p id="response" style="padding:10px;color:#f00;"></p>
</div>  
	<!---
	
	Filter form Start here
	-->
	
	
<div class="clear clearfix"></div>
	<div class="row" style="margin-bottom:20px;">
            <div class="col-sm-12">
                <fieldset class="scheduler-border">
					<legend class="scheduler-border">Search</legend>
					<div class="control-group">
							<form method="get" class="form-inline" action = "{{route('attendance.callingsearch')}}">
							<div class="form-group" style="padding-bottom: 15px;">
								<label for="rstartdate">Reminder Start Date:</label>
								<input style="line-height:20px;" type="date" name="rstartdate" value="<?php if(isset($rstartdate)){echo $rstartdate; } ?>" class="form-control" id="rstartdate">
							</div>
							<div class="form-group" style="padding-bottom: 15px;">
								<label for="renddate">Reminder End Date:</label>
								<input style="line-height:20px;" type="date" name="renddate" value="<?php if(isset($renddate)){echo $renddate; } ?>" class="form-control" id="renddate">
							</div>
							
							<div class="form-group" style="padding-bottom: 15px;">
								<label for="cstartdate">Calling Start Date:</label>
								<input style="line-height:20px;" type="date" name="cstartdate" value="<?php if(isset($cstartdate)){echo $cstartdate; } ?>" class="form-control" id="cstartdate">
							</div>
							<div class="form-group">
								<label for="cenddate">Calling End Date:</label>
								<input style="line-height:20px;" type="date" name="cenddate" value="<?php if(isset($cenddate)){echo $cenddate; } ?>" class="form-control" id="cenddate">
							</div>
							
							<div class="form-group" style="padding-bottom: 15px;">
								<label for="status">Status:</label>
								<select name="status" id="status" class="form-control">
											<option value=""> Select Status</option>
											<option <?php if(isset($status) && $status == 'Do not pick' ){echo "selected"; } ?> value="Do not pick">Do not pick</option>
											<option value="Switched Off" <?php if(isset($status) && $status == 'Switched Off' ){echo "selected"; } ?>>Switched Off</option>
											<option value="out of covarage area" <?php if(isset($status) && $status == 'out of covarage area' ){echo "selected"; } ?>>Out of covarage area</option>
											<option value="call later" <?php if(isset(($status)) && $status  == 'call later' ){echo "selected"; } ?>>Call later</option>
											<option value="unknown" <?php if(isset($status) && $status == 'unknown' ){echo "selected"; } ?>>Unknown</option>
											<option value="do not desturb" <?php if(isset($status) && $status == 'do not desturb' ){echo "selected"; } ?>>Do not disturb</option>
											<option value="done" <?php if(isset($status) && $status == 'done' ){echo "selected"; } ?>>Done</option>
											<option value="busy" <?php if(isset($status) && $status == 'busy' ){echo "selected"; } ?>>Busy</option>
											<option value="wrong number" <?php if(isset($status) && $status == 'wrong number' ){echo "selected"; } ?>>Wrong number</option>
											<option value="not interested" <?php if(isset($status) && $status == 'not interested' ){echo "selected"; } ?>>Not Interested</option>
											<option value="interested" <?php if(isset($status) && $status == 'interested' ){echo "selected"; } ?>>Interested</option>
											<!--
											//'Do not pick','Switched Off','out of covarage area','call later','unknown','do not desturb'
											-->
										
										</select>
							</div>
							
							<div class="form-group" style="padding-bottom: 15px;">
								<label for="ssearch">Name/Mobile/Email:</label>
								<input type="text" name="ssearch"  value="<?php if(isset($ssearch)){echo $ssearch; } ?>" class="form-control" id="ssearch">
							</div>
							
							<div class="form-group" style="padding-bottom: 15px;">
								<label for="category">Category:</label>
								<select name="category" id="category"  class="form-control">
										<option value="">Select Categories</option>
										<?php 
										for($i=0;$i<count($category_data);$i++)
										{
										?>
										<option data-type="<?php echo $category_data[$i]['customer_type']; ?>" <?php if(isset($category) && ($category_data[$i]['CategoryId'] == $category)){ echo "selected"; } ?> value="<?php echo $category_data[$i]['CategoryId']; ?>"><?php echo $category_data[$i]['Name']; ?></option>
										<?php
										}
										?>
										</select>
										
							</div>
							
							<div class="form-group" style="padding-bottom: 15px;">
								<label for="institute">Institute:</label>
								<select name="institute" id="institute" class="form-control">
									<option value="">Select Institutes</option>
								<?php $institutes = DB::table('institutes')->get();
								foreach($institutes as $institute):
								?>
								<option value="<?= $institute->id ?>" ><?= $institute->name ?></option>
								<?php endforeach; ?>
								</select>
								
							</div>
							
							
							<button type="submit" name="search" value="search" class="btn btn-primary">Search</button>
							<!-- <a href="calling.php" class="btn btn-secondary">Clear</a> -->
							</form> 
					</div>
				</fieldset> 
            </div>
    </div>
            
	<!---
	
	Filter form End here
	-->
	<div class="clear clearfix"></div>
	
	<div class="row"> 
		<div class="table-responsive">
		<table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
			<thead>
				<tr>
				<th>S.No.</th>
				
				<th>Name</th>
				<th>Category</th>
				<th>Institute</th>
				<th>Status</th>
				<th>Entry Date</th>
				<th>Reminder Date</th>
				<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
				<th>S.No.</th>
				
				<th>Name</th>
				<th>Category</th>
				<th>Institute</th>
				<th>Status</th>
				<th>Entry Date</th>
				<th>Reminder Date</th>
				<th>Action</th>
				</tr>
			</tfoot>
			<tbody>
			
				<?php
				
				$condi2 = ' where 1 = 1 ';
				if($search){
				$condi2 = $condi;
				}
					$rrr = get_all_array($con,"select * from calling $condi2 order by id desc limit 0,50");
			//print_r($rrr); die();
				$i=0;
				
				foreach($rrr as $r):
					?>
					<tr>
					<td><?= ++$i ?></td>
					
					<td><?= $r['name'] ?>
					
					</td>
					<?php
					//SELECT * FROM `institutes` 
					list($cat_name) = get_query_list($con,"SELECT Name FROM `customer_categories` where id='".$r['category']."'");
					list($inst_name) = get_query_list($con,"SELECT name FROM `institutes` where id='".$r['institute']."'");
					?>
					<td><?= $cat_name ?></td>
					<td><?= $inst_name ?></td>
					<td><?= $r['status'] ?></td>
					<td><?= $r['entrydate'] ?></td>
					<td><?= $r['reminder_date'] ?></td>
					<td>
						<a href="javascript:void(0)" class="btn btn-primary comments" data-val="<?= $r['name'] ?> - <?= $r['mobile'] ?>" data-val2="<?= $r['name'] ?> - <?= substr($r['mobile'],0,3).'xxxxx'.substr($r['mobile'],-2);  ?>" data-id="<?= $r['id'] ?>" id="comments-<?= $r['id'] ?>">Comments</a>
						<a href="javascript:void(0)" class="btn btn-primary sms" data-mobile="<?= $r['mobile'] ?>" id="sms<?= $r['id'] ?>" data-val="<?= $r['name'] ?> - <?= $r['mobile'] ?>" data-val2="<?= $r['name'] ?> - <?= substr($r['mobile'],0,3).'xxxxx'.substr($r['mobile'],-2);  ?>" data-id="<?= $r['id'] ?>">Sent SMS</a>
					</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	</div>
	</div>
	  
	<div id="myModal" class="modal fade" role="dialog" >
  <div class="modal-dialog" style="width:75%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
		  <?php //print_r($_SESSION); ?>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title callermodalheader"></h4>
      </div>
      <div class="modal-body callermodalbody">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>		


<div id="mysmsModal" class="modal fade" role="dialog" >
  <div class="modal-dialog" style="width:75%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header modalheadersms">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Sent SMS - <span class="smsmodalheadername"></span></h4>
      </div>
      <div class="modal-body callermodalsms">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>		
    
 @endsection

@section('script')
<script type="text/javascript">
function getcaller(){
	var institute = jQuery('#institute').val();
	var category = jQuery('#category').val();
	var userid = '<?= Auth::user()->id ?>';
	$.post(
		'{{ url("/admin/attendance/get_caller") }}',
		{'institute' : institute, 'category' : category,'userId' : userid , _token: "{{ csrf_token() }}" }	
	).done(function(data){
		console.log(data);
		data = JSON.parse(data);
		jQuery('#caller').html(dataType);
				getCallerName();
	});

	}
	
	function skipme(e){
		e.preventDefault;
		var id = jQuery('option:selected', '#caller').attr('data-id');
		var type = jQuery('option:selected', '#caller').attr('data-type');
		var institute = jQuery('#institute').val();
		var category = jQuery('#category').val();
		if(id == ""){
			alert("please select User");
			return false;
			}
		var rlink = "skipme.php?id="+id+"&type="+type+"&institute="+institute+"&category="+category;
		//alert(rlink);
		location.href=rlink;
		}
	
	function getCallerName(){
		jQuery('#name').val(jQuery('option:selected', '#caller').attr('data-name'));
	}
	getCallerName();
	// $('#reminder').datepicker({ dateFormat: 'yy-mm-dd'});
	// jQuery('#reminder_comment').datepicker({ dateFormat: 'yy-mm-dd'});

	$('.comments').on('click',function(){
		var id = jQuery(this).attr('data-id');
		jQuery('.callermodalheader').html(jQuery(this).attr('data-val2').toUpperCase());
		//$('#myModal').modal({show:true});
		 $('.callermodalbody').load('{{url("/admin/attendance/callingcomment")}}/'+id,function(){
			$('#myModal').modal({show:true});
			//jQuery('#reminder_comment').datepicker({ dateFormat: 'yy-mm-dd'});
			
			jQuery("#commentform").submit(function(event){
            event.preventDefault();

            jQuery.ajax({
                    url: '{{url('/admin/attendance/callercommentsubmit')}}',
                    type:'POST',
                    data:jQuery(this).serialize(),
                    success:function(result){
						//console.log(result);
						$('#myModal').modal('hide');
                        jQuery("#response").text("comment saved successfully");
                        

                    }

            });
        });
			
		}); 
	});
	
	
	$('.sms').on('click',function(){
		var id = jQuery(this).attr('data-id');
		//$('#mysmsModal').modal({show:true});
		var mobile = jQuery(this).attr('data-mobile');
		var mobile1 = mobile.substring(0,3)+'xxxxx'+mobile.slice(-2);
		var userid = '<?= Auth::user()->id ?>';
		jQuery('.smsmodalheadername').html(mobile1);
		 $('.callermodalsms').load('{{url("/admin/attendance/callingsms")}}/'+id+'/'+mobile+'/'+userid,function(){
			$('#mysmsModal').modal({show:true});
			//jQuery('#reminder_comment').datepicker({ dateFormat: 'yy-mm-dd'});
			//jQuery('#send_sms').click(function(e){ alert('testt'); })
			jQuery("#smsformtbl").submit(function(event){
            event.preventDefault();

            jQuery.ajax({
                    url:'{{url('/admin/attendance/callersmssubmit')}}',
                    type:'POST',
                    data:jQuery(this).serialize(),
                    success:function(result){
						$('#mysmsModal').modal('hide');
                        jQuery("#response").text("SMS Sent successfully");

                    }

            });
        });
			
		}); 
		
	});



</script>


@endsection