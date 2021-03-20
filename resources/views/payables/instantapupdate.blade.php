@extends('layouts.app')

@section('content')

<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Update Instant AP')}}</h3>
        </div>
    <div class="panel-body">
    <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('APInvoiceAlls.InstantapUpdateStore') }}" method="POST">
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
                       <input type='text' name="title" id="title" value="<?php echo $data->title ?>" class="form-control" required="required"  />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="name">{{__('Invoice number *')}}</label>
                        <input type='text' name="invnum" id="invnum" value="<?php echo $data->invoice_number; ?>" class="form-control" required="required" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label" for="type">{{__('Amount * ')}}</label>
                        <input type='text' name="amount" value="<?php echo $data->Total; ?>" id="amount"  class="form-control" required="required"  maxlength="10" />
                        
                    </div>
                    <div class="col-sm-3">
                    <label class="control-label" for="type">{{__('Pay Date *')}}</label>
                    <input type='text' name="invdate" id="invdate" value="<?php echo $data->paydate; ?>" class="form-control" required="required" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                       <label class="control-label" for="name">{{__('Upload Invoice ')}}</label>
                       <div id="image">
										@if ($data->image != null || $data->image != '')
											<div class="col-md-4 col-sm-4 col-xs-6">
												<div class="img-upload-preview" style="height:80px;">
													<img loading="lazy"  src="{{ asset($data->image) }}" alt="" class="img-responsive">
													<input type="hidden" name="previous_img" value="{{ $data->image }}">
													<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
												</div>
											</div>
										@endif
									</div>
                    
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="name">{{__('Payaccount')}}</label>
                        <select name="payaccount" required  class="form-control">
								<option value="">Select Account</option>
								<option value="cce"<?php if($data->payaccount == 'cce'){echo "selected";} ?>>CCE</option>
								<option value="current"<?php if($data->payaccount == 'current'){echo "selected";} ?>>current</option>
								<option value="paytm"<?php if($data->payaccount == 'paytm'){echo "selected";} ?>>PayTM</option>
								<option value="cash" <?php if($data->payaccount == 'cash'){echo "selected";} ?> >Cash</option>
						</select>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label" for="type">{{__('AP Type ')}}</label>
                            <select name="aptype" class="form-control">
									
                                    <option value="purchase" <?php if($data->type == 'purchase'){echo "selected";} ?>>Purchase</option>
                                    <option value="expanse" <?php if($data->type == 'expanse'){echo "selected";} ?>>Expanse</option>
                                    <option value="withdraw" <?php if($data->type == 'withdraw'){echo "selected";} ?>>Withdraw</option>
                                </select>
                    </div>
                </div>
              
                <div class="form-group">
                        <label class="control-label" for="name">{{__('Description')}}</label>
                        <textarea name="description" id="description"  class="form-control ckeditor"><?php echo $data->description; ?></textarea>
                        <input type="hidden" name="suppliers" id="suppliers" value="102449" />
                        <input type="hidden" name="invoiceid" id="invoiceid" value="<?php echo $data->invoiceid?>" />
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