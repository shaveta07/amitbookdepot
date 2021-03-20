@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-lg-offset-3" style="margin-left:0px;">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('AR Transaction Report - Book Wise')}}</h3>
        </div>
        <?php if(Auth::user()->user_type != "admin" ){ echo "<h3>Unauthorised Access !!</h3>"; die(); }  ?>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form  id="myForm1" class="form-horizontal" action="{{ route('report.AR_book_report_submit')}}" method="Post" >
        	@csrf
            <div class="panel-body">
          
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('keyword:')}}</label>
                    <div class="col-sm-10">
                    <input type="text" name="keyword" required="required" style="width:400px;" value="<?= $keyword ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('From:')}}</label>
                    <div class="col-sm-10">
                            <input type="date" id="from" class="form-control" name="from" value="<?= $postfrom ?>" />               
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('To:')}}</label>
                    <div class="col-sm-10">
                         <input type="date" id="to" class="form-control" name="to" value="<?= $postto ?>" />     
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{__('Customer:')}}</label>
                    <div class="col-sm-10">
                    <select name="customer" class="form-control">
                    <option value="">select customer</option>
                    <?php
                   
                   // get_all_array($con,"select * from AR_Customers where Category IN(18,39)");
                    foreach($cusarr as $cus):
                    ?>
                    <option value="<?= $cus->cusid ?>" <?php if($cus->cusid == $customerto){echo "selected";} ?>><?= $cus->name?></option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                </div>
				
				
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" name="submit" type="submit">{{__('search')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>


<table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
<thead>
<tr><th>ISBN</th><th>Book Name</th><th>Author</th><th>Publisher</th><th>Invoice Number</th><th>Price</th><th>Quantity</th><th>Total</th><th>Store</th><th>Sale/Rent</th><th>Customer</th><th>Customer Name</th></tr>
</thead>
<tbody>
<?php
			$total_amount=0;
			$total_qty=0;
			for($i=0;$i<count($data);$i++)
			{
			$count=$i;
			?>
			<tr><td><?php echo $data[$i]['Isbn1']; ?></td><td><?php echo $data[$i]['Name']; ?></td>
			<td><?php echo $data[$i]['Author']; ?></td><td><?php echo $data[$i]['Publisher']; ?></td>
			<td><a href="ARinvoice-lines-workbench.php?invoice_number=<?php echo $data[$i]['InvoiceNumber']; ?>" target='_blank'><?php echo $data[$i]['InvoiceNumber']; ?></a></td>
			<td><?php 
			$price_book=0;
			if($data[$i]['TransactionType']=='S') { 
			//$total_amount+=$data[$i]['ItemPrice']; 
			$price_book=$data[$i]['ItemPrice']; }
			else { $price_book=$data[$i]['Amount']; } 
			echo $price_book;  ?></td>
			
			<td><?php $total_qty+=$data[$i]['Quantity']; echo $data[$i]['Quantity']; ?></td>
			<td><?php $total_amount+=$price_book*$data[$i]['Quantity']; echo $price_book*$data[$i]['Quantity']; ?></td>
			<td><?php echo getUserStoreName($data[$i]['store_id']); ?></td>
			<td><?php echo $data[$i]['TransactionType']; ?></td>
			<td><a href="edit-customer.php?customer-id=<?php echo $data[$i]['CustomerID'];  ?>" target='_blank'><?php echo $data[$i]['CustomerID'];  ?></a></td>
			<td><a href="edit-customer.php?customer-id=<?php echo $data[$i]['CustomerID'];  ?>" target='_blank'><?php echo $data[$i]['customercat'];  ?></a></td>
			</tr>
			<?php 
			}
			?>
			<tr>
			<td>Total</td><td></td><td></td>
			<td></td><td></td><td></td>
			<td><?php echo $total_qty; ?></td><td><?php echo $total_amount; ?></td><td></td><td></td><td></td>
			</tr>
</tbody>
</table>
<br />
		
            <br />
            <br />
            <div class="clearfix"></div>
            <div class="clearfix"></div>
<!-- </div> -->
@endsection

@section('script')

<script type="text/javascript">

</script>

@endsection