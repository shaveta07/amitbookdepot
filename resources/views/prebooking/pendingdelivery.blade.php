@extends('layouts.app')

@section('content')
<style>
#DataTables_Table_0_wrapper {
	padding-left: 2% !important;
	padding-right: 2% !important;
}
.dt-buttons {
	display: none !important;
}
</style>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Pending Delivery')}}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_categories" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder=" Type name & Enter">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="d-none d-sm-table-cell">{{__('PP number')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('Product ID')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('Isbn')}}</th>
                    <th class="d-none d-sm-table-cell">{{__('Name')}}</th>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Booked Qty')}}</th>
                    <th>{{__('Delivered')}}</th>
                    <th>{{__('store')}}</th>
                </tr>
            </thead>
            <tbody>
                
                <?php foreach($pendingdlvy as $key => $dlvy) {
                $prebook = \App\Prebooking::where('invoiceid', $dlvy->invoiceid)->first();
                $invoiceNumber = $prebook->invoicenumber;
                $InvoiceLookupType =$prebook->invoicelookuptype;
                $InvoiceDate = $prebook->invoicedate;
                $status= $prebook->status;
                $invoicelookuptype = $prebook->invoicelookuptype;
                    if($status != 'C' && $invoicelookuptype != 'C'){
                        $product = \App\Product::where('id',$dlvy->itemid)->first();
                        if($dlvy->variation != NULL && $dlvy->variation != 'null')
                        {
                            
                            $productStock = \App\ProductStock::where('variant',$dlvy->variation)->where('product_id',$product->id)->first();
                            if($productStock)
                            {
                                $isbn1 = $productStock->isbn;
                                $name =  $dlvy->variation;
                            }
                        }
                        else
                        {
                            $isbn1 = $product->isbn;
                            $name = $product->name;
                        }
                ?>
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td class="font-w600"><a href="{{url('/admin/PreOrderBooking/PreOrderBookingLines/')}}<?php echo $invoiceNumber ?>">{{ $invoiceNumber }}</a></td>
                        <td>{{ $dlvy->itemid}}</td>
                        <td class="font-w600">{{ $isbn1}}</td>
                        <td class="font-w600">{{ $name }}</td>
                        <td>{{ $InvoiceDate }} </td>
                        <td> {{ $dlvy->quantity}} </td>
                        <td class="font-w600">{{ $dlvy->delivered_qty}}</td>
                        <td>{{_('Amit Book Depot')}}</td>
                    </tr>
                    <?Php }}?>
            </tbody>
        </table>
        
    </div>
</div>

@endsection

@section('script')
   
@endsection
