@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Apply Leave')}}</h3>
        </div><?php echo $msg; ?> 

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('attendance.ApplyLeaveSubmit')}}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Leave Type')}}</label>
                    <div class="col-sm-10">
					<select required="required" name="leavetype" id="leavetype" class="form-control">
					<option value="SL">Sick Leave</option>
					<option value="CL">Casual Leave</option>
					<option value="EL">Emergency Leave</option>
					<option value="PL">Paternity Leave</option>
					<option value="ML">Maternity Leave</option>
					</select>
                    </div>
                </div>
              
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Date From')}}</label>
                    <div class="col-sm-10">
						<input type="date" name="date" class="form-control" id="startdate" required >		
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Date To')}}</label>
                    <div class="col-sm-10">
						<input type="date" name="date_to" class="form-control" id="enddate" required >		
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Description')}}</label>
                    <div class="col-sm-10">
                        <textarea name="desc" rows="8" class="form-control"></textarea>
                    </div>
                </div>
				<input type="hidden" id="hstatus" name="status" value="Y"  />
				<div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Document')}}</label>
                    <div class="col-sm-10">
						<div id="photos">

						</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" name="punch" type="submit">{{__('Apply')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>



<br/>
<br/>
<br/>
Name: <?php echo $data[0]['Name']; ?>&nbsp;&nbsp;
Employee ID:  <?php echo $data[0]['EmployeeID']; ?>&nbsp;&nbsp;
Email:  <?php echo $data[0]['Email']; ?><br />
<!-- <div class="table-responsive"> -->
<table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
<thead>
<tr><th>From Date</th>
<th>To Date</th>
<th>Description</th>
<th>Leave Type</th>
<th>File</th>
<th>Status</th>
<th>Action</th>

</tr>
</thead>
<tbody>
<?php for($i=0;$i<count($data_leave);$i++){ ?>
<tr>
<td><?php echo $data_leave[$i]['Date']; ?></td>
<td><?php echo $data_leave[$i]['ToDate']; ?></td>
<td><?php echo $data_leave[$i]['Description']; ?></td>
<td><?php if($data_leave[$i]['leavetype'] == 'SL'){echo "Sick Leave"; }elseif($data_leave[$i]['leavetype'] == 'EL'){echo "Emergency Leave"; }elseif($data_leave[$i]['leavetype'] == 'CL'){echo "Casual Leave"; }elseif($data_leave[$i]['leavetype'] == 'PL'){echo "Paternity Leave"; }elseif($data_leave[$i]['leavetype'] == 'ML'){echo "Maternity Leave"; }else{ echo "";} ?></td>
<td><?php
// if(is_array(json_decode($data_leave[$i]['document']))){
// 	foreach (json_decode($data_leave[$i]['document']) as $key => $photo) {
// 	echo "<a target='_blank' href='uploads/".$photo."'>".$photo."</a>"; 

// 	}}
if($data_leave[$i]['Document'] != null && json_decode($data_leave[$i]['Document']) != "[]")
{
	// echo sizeof(json_decode($data_leave[$i]['Document']));
	foreach(json_decode($data_leave[$i]['Document']) as $key => $doc)
	{	
?>
		<a target="_blank" href="{{ asset($doc) }}">File {{ $key + 1 }}</a> &nbsp;
<?php
	}
}
// if (sizeof(json_decode($data_leave[$i]['Document'])) > 0)
// {
// 	// echo $document = json_decode($data_leave[$i]['document']);
// 	foreach(json_decode($data_leave[$i]['Document']) as $doc)
// 	{	
// 		echo "<a target='_blank' href='uploads/".$doc."'>".$doc."</a>"; 
// 	}
// }
?></td>
<td><?php if($data_leave[$i]['Status']=='P') $status="Approval Pending"; 
else if($data_leave[$i]['Status']=='A') $status="Approved"; 
else $status="Rejected"; 
echo $status; ?></td>
<td>
	<?php //if($data_leave[$i]['Status']=='P'){ 
		$id =$data_leave[$i]['RowID'];?>
		<a class="btn btn-info" href="{{route('attendance.EditLeave', $id)}}" >Edit Leave</a>
		
	<?php //} ?>
	</td>
</tr>
<?php }?>
</tbody>
</table>
<!-- </div> -->
@endsection

@section('script')

<script type="text/javascript">
// $('#date_from').datepicker({ dateFormat: 'yy-mm-dd'});
// $('#date_to').datepicker({ dateFormat: 'yy-mm-dd' });
$("#photos").spartanMultiImagePicker({
			fieldName:        'photos[]',
			maxCount:         10,
			rowHeight:        '100px',
			groupClassName:   'col-md-12 col-sm-12 col-xs-6',
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
		
function applyleave(){
	var datefrom = $('#date_from').val();
	var dateto = $('#date_to').val();
	
if(datefrom == ""){alert("Date from can not be empty"); return false;}
if(dateto == ""){alert("Date To can not be empty"); return false;}
	
var date1 = new Date(datefrom);
var date2 = new Date(dateto);
var timeDiff = (date2.getTime() - date1.getTime());
var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
	//alert(diffDays);
	if(diffDays<0){
		alert("DateTo should greater then Date From");
	return false;
}
	
	if($('#description').val() == ''){
	alert("Description is Mandatory field");
	return false;
	}

	$.post(
		'{{ url("/admin/attendance/applyleaveview") }}',
		{'date_from' : datefrom,'date_to':dateto, 'userid' :"{{ Auth::user()->id }}", _token: "{{ csrf_token() }}" }	
	).done(function(data){
		console.log(data);
		data = JSON.parse(data);
		var str = ''; var stss='';
					$.each(data, function(index, element) {
						//str = str+element.name + "has applied the leave between "+element.FromDate+" and "+element.ToDate+" \n";
						//alert(str);
					    stss = String(element.sts);
						alert(stss);
						if(stss == "yes"){
							$('#frm').submit();
							return true;
						}
						if(stss == "no"){
							str = str+element.name + "has applied the leave between "+element.FromDate+" and "+element.ToDate+" \n";
							
						}
					});
					
					// if(stss == "yes"){
					// 		$('#frm').submit();
					// 		return true;
					// }
						
					// 	if(stss == "no"){
					// 		if(confirm(str)){
					// 		$('#hstatus').val('N');
					// 			$('#frm').submit();
					// 		}else{
					// 			return false;
					// 		}
							
					// 	}
					
					
					
					//alert(str);
					return false;
					
	})
	/*
		$.ajax({
				async : true,
				url : '{{ url("/admin/attendance/applyleaveview") }}',
				type : "POST",
				data : {'date_from' : datefrom,'date_to':dateto, 'userid' :"{{ Auth::user()->id }}", _token: "{{ csrf_token() }}" },
				dataType : 'json',
				error:function(){
				   alert('Error!');
				   return false;
				},
				success:function(dataType) {
					
					var str = ''; var stss='';
					$.each(dataType, function(index, element) {
						//str = str+element.name + "has applied the leave between "+element.FromDate+" and "+element.ToDate+" \n";
						//alert(str);
					    stss = String(element.sts);
						if(stss == "yes"){
							//$('#frm').submit();
							//return true;
						}
						if(stss == "no"){
							str = str+element.name + "has applied the leave between "+element.FromDate+" and "+element.ToDate+" \n";
							
						}
					});
					
					if(stss == "yes"){
							$('#frm').submit();
							return true;
					}
						
						if(stss == "no"){
							if(confirm(str)){
							$('#hstatus').val('N');
								$('#frm').submit();
							}else{
								return false;
							}
							
						}
					
					
					
					//alert(str);
					return false;
					
				}
			});
			*/
			
			//return false;
	}

</script>

@endsection