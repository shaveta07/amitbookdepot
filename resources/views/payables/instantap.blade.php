@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Instant AP')}}</h3>
        </div>
    <div class="panel-body">
    <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('APInvoiceAlls.InstantapStore') }}" method="POST">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label" for="type">{{__('Store Name')}}</label>
                    <select class="form-control demo-select2-placeholder" name="store" id="Store" required>
                         <option value="1">Amit Book Depot</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                       <label class="control-label" for="name">{{__('Title * ')}}</label>
                       <input type='text' name="title" id="title"  class="form-control" required="required"  />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="name">{{__('Invoice number *')}}</label>
                        <input type='text' name="invnum" id="invnum"  class="form-control" required="required" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="type">{{__('Amount * ')}}</label>
                        <input type='text' name="amount" id="amount"  class="form-control" required="required"  maxlength="10" />
                        
                    </div>
                    <div class="col-sm-3">
                    <label class="control-label" for="type">{{__('Pay Date *')}}</label>
                    <input type='text' name="invdate" id="invdate"  class="form-control" required="required" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                       <label class="control-label" for="name">{{__('Upload Invoice ')}}</label>
                       <!-- <input type='file' name="invoice" id="invoice"  class="form-control" /> -->
                       <div id="image">

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="name">{{__('Payaccount')}}</label>
                        <select name="payaccount" required  class="form-control">
								<option value="">Select Account</option>
								<option value="cce">CCE</option>
								<option value="current">current</option>
								<option value="paytm">PayTM</option>
								<option value="cash" >Cash</option>
						</select>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="type">{{__('AP Type ')}}</label>
                            <select name="aptype" class="form-control">
									<option value="">Select AP Type</option>
                                    <option value="purchase">Purchase</option>
                                    <option value="expanse">Expanse</option>
                                    <option value="withdraw">Withdraw</option>
                                </select>
                    </div>
                </div>
              
                <div class="form-group">
                        <label class="control-label" for="name">{{__('Description')}}</label>
                        <textarea name="description" id="description"  class="form-control ckeditor"></textarea>
                        <input type="hidden" name="suppliers" id="suppliers" value="102449" />
            </div>
            </div>
            
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
            </div>
        </form>
    </div>
    </div>
</div>
@endsection
@section('script')
<script>
	$('#invdate').datepicker({ dateFormat: 'yy-mm-dd'});
    $("#image").spartanMultiImagePicker({
			fieldName:        'image',
			maxCount:         1,
			rowHeight:        '80px',
            allowedExt:       'png|jpg',
			groupClassName:   'col-md-4 col-sm-4 col-xs-6',
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

        $('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });

</script>
@endsection