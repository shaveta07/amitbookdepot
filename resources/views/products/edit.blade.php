@extends('layouts.app')

@section('content')

<div class="row">
	<form class="form form-horizontal mar-top" action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
		<input name="_method" type="hidden" value="POST">
		<input type="hidden" name="id" value="{{ $product->id }}">
		@csrf
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Product Information')}}</h3>
			</div>
			<div class="panel-body">
				<div id="customerr" style="display:none" class="alert alert-danger"></div>
				<div class="tab-base tab-stacked-left">
				    <!--Nav tabs-->
				    <ul class="nav nav-tabs">
						<li class="active">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-1" aria-expanded="true">{{__('General')}}</a>
				        </li>
				        <li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-2" aria-expanded="false">{{__('Images')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-3" aria-expanded="false">{{__('Videos')}}</a>
				        </li>
				        <li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-4" aria-expanded="false">{{__('Meta Tags')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-5" aria-expanded="false">{{__('Customer Choice')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-6" aria-expanded="false">{{__('Price')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-7" aria-expanded="false">{{__('Description')}}</a>
				        </li>
						{{-- <li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-8" aria-expanded="false">Display Settings</a>
				        </li> --}}
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-9" aria-expanded="false">{{__('Shipping Info')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-10" aria-expanded="false">{{__('PDF Specification')}}</a>
				        </li>
				    </ul>

				    <!--Tabs Content-->
				    <div class="tab-content">
				        <div id="demo-stk-lft-tab-1" class="tab-pane fade active in">
							<div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Product Name')}}<span class="required">*</span></label>
	                            <div class="col-lg-7">
	                                <input type="text" class="form-control" id="name" name="name" placeholder="{{__('Product Name')}}" value="{{$product->name}}" required>
	                            </div>
	                        </div>
	                        <div class="form-group" id="category">
	                            <label class="col-lg-2 control-label">{{__('Category')}}<span class="required">*</span></label>
	                            <div class="col-lg-7">
	                                <select class="form-control demo-select2-placeholder" name="category_id" id="category_id" required>
	                                	<option>Select an option</option>
	                                	@foreach($categories as $category)
	                                	    <option data-id="{{$category->istaxapplicable}}" value="{{$category->id}}" <?php if($product->category_id == $category->id) echo "selected"; ?> >{{__($category->name)}}</option>
	                                	@endforeach
	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group" id="subcategory">
	                            <label class="col-lg-2 control-label">{{__('Subcategory')}}<span class="required">*</span></label>
	                            <div class="col-lg-7">
	                                <select class="form-control demo-select2-placeholder" name="subcategory_id" id="subcategory_id" >

	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group" id="subsubcategory">
	                            <label class="col-lg-2 control-label">{{__('Sub Subcategory')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control demo-select2-placeholder" name="subsubcategory_id" id="subsubcategory_id">

	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group" id="brand">
	                            <label class="col-lg-2 control-label">{{__('Brand')}}<span class="required">*</span></label>
	                            <div class="col-lg-7">
	                                <select class="form-control demo-select2-placeholder" name="brand_id" id="brand_id" required>
										<option value="">{{ ('Select Brand') }}</option>
										@foreach (\App\Brand::all() as $brand)
											<option value="{{ $brand->id }}" @if($product->brand_id == $brand->id) selected @endif>{{ $brand->name }}</option>
										@endforeach
	                                </select>
	                            </div>
	                        </div>
						
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Country of Origin')}}</label>
									<div class="col-lg-7">
										<input type="text" id="unit" class="form-control" name="origin" placeholder="" value="<?php if($product->origin = ''){ echo $product->origin;} else { echo 'india';} ?>" >
									</div>
								</div>	
								<!-- <div class="form-group">
								<label class="col-lg-2 control-label">{{__('Track ID')}}</label>
									<div class="col-lg-7">
										<input type="text" id="trackid" class="form-control" value="{{ $product->track_id }}" name="track_id" placeholder="" >
									</div>
								</div>	 -->
	                        <div class="form-group">
								
								<label class="col-lg-2 control-label">{{__('Author')}}<span class="required">*</span></label>
	                            <div class="col-lg-3">
									<select class="form-control demo-select2-placeholder" name="author_id" id="author" required>
										<option value="">{{ ('Select Author') }}</option>
										@foreach (\App\Author::all() as $author)
											<option value="{{ $author->id }}" @if($product->author_id == $author->id) selected @endif>{{ $author->name }}</option>
										@endforeach
									</select>
								</div>
								
	                            <label class="col-lg-2 control-label">{{__('Unit')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit (e.g. KG, Pc etc)" value="{{$product->unit}}" required>
	                            </div>
	                        </div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Dimension Weight')}}</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" name="weight_dimension" value="{{ $product->weight_dimension }}"  placeholder="weight dimension(grm,kilogram etc)" >
								</div>
							</div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Tags')}}</label>
	                            <div class="col-lg-7">
	                                <input type="text" class="form-control" name="tags[]" id="tags" value="{{ $product->tags }}" placeholder="Type to add a tag" data-role="tagsinput">
	                            </div>
	                        </div>
	                        
	                        <div class="form-group">
	                            
	                            
	                            <label class="col-lg-2 control-label">{{__('ISBN')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="text" class="form-control" name="isbn" id="isbn" value="{{ $product->isbn }}" placeholder="ISBN" required />
	                            </div>
	                            
	                            <label class="col-lg-1 control-label">{{__('Version')}}<span class="required">*</span></label>
	                            <div class="col-lg-1">
									<select class="form-control" name="version" id="version" >
										<option value="new"  <?php if($product->version == 'new') echo "selected"; ?> >New</option>
										<option value="old"  <?php if($product->version == 'old') echo "selected"; ?> >Old</option>
									</select>
	                                
	                            </div>
	                            
	                            <label class="col-lg-1 control-label">{{__('OLD ISBN')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="text" class="form-control" name="oldisbn" id="oldisbn" required value="{{ $product->oldisbn }}" placeholder="ISBN" />
	                            </div>
	                        </div>
	                        
							@php
							    $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
							@endphp
							@if ($pos_addon != null && $pos_addon->activated == 1)
								<div class="form-group">
									<label class="col-lg-2 control-label">{{__('Barcode')}}</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" name="barcode" placeholder="{{ ('Barcode') }}" value="{{ $product->barcode }}">
									</div>
								</div>
							@endif

							@php
							    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
							@endphp
							@if ($refund_request_addon != null && $refund_request_addon->activated == 1)
								<div class="form-group">
									<label class="col-lg-2 control-label">{{__('Refundable')}}</label>
									<div class="col-lg-7">
										<label class="switch" style="margin-top:5px;">
											<input type="checkbox" name="refundable" @if ($product->refundable == 1) checked @endif>
				                            <span class="slider round"></span>
										</label>
									</div>
								</div>
							@endif
				        </div>
				        <div id="demo-stk-lft-tab-2" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Main Images')}}</label>
								<div class="col-lg-7">
									<div id="photos">
										@if(is_array(json_decode($product->photos)))
											@foreach (json_decode($product->photos) as $key => $photo)
												<div class="col-md-4 col-sm-4 col-xs-6">
													<div class="img-upload-preview">
														<img loading="lazy"  src="{{ asset($photo) }}" alt="" class="img-responsive">
														<input type="hidden" name="previous_photos[]" value="{{ $photo }}">
														<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
													</div>
												</div>
											@endforeach
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Thumbnail Image')}} <small>(290x300)</small></label>
								<div class="col-lg-7">
									<div id="thumbnail_img">
										@if ($product->thumbnail_img != null)
											<div class="col-md-4 col-sm-4 col-xs-6">
												<div class="img-upload-preview">
													<img loading="lazy"  src="{{ asset($product->thumbnail_img) }}" alt="" class="img-responsive">
													<input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">
													<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Featured')}} <small>(290x300)</small></label>
								<div class="col-lg-7">
									<div id="featured_img">
										@if ($product->featured_img != null)
											<div class="col-md-4 col-sm-4 col-xs-6">
												<div class="img-upload-preview">
													<img loading="lazy"  src="{{ asset($product->featured_img) }}" alt="" class="img-responsive">
													<input type="hidden" name="previous_featured_img" value="{{ $product->featured_img }}">
													<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Flash Deal')}} <small>(290x300)</small></label>
								<div class="col-lg-7">
									<div id="flash_deal_img">
										@if ($product->flash_deal_img != null)
											<div class="col-md-4 col-sm-4 col-xs-6">
												<div class="img-upload-preview">
													<img loading="lazy"  src="{{ asset($product->flash_deal_img) }}" alt="" class="img-responsive">
													<input type="hidden" name="previous_flash_deal_img" value="{{ $product->flash_deal_img }}">
													<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
				        </div>
				        <div id="demo-stk-lft-tab-3" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Video Provider')}}</label>
								<div class="col-lg-7">
									<select class="form-control demo-select2-placeholder" name="video_provider" id="video_provider">
										<option value="youtube" <?php if($product->video_provider == 'youtube') echo "selected";?> >{{__('Youtube')}}</option>
										<option value="dailymotion" <?php if($product->video_provider == 'dailymotion') echo "selected";?> >{{__('Dailymotion')}}</option>
										<option value="vimeo" <?php if($product->video_provider == 'vimeo') echo "selected";?> >{{__('Vimeo')}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Video Link')}}</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}" placeholder="Video Link">
								</div>
							</div>
				        </div>
						<div id="demo-stk-lft-tab-4" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Meta Title')}}</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" name="meta_title" value="{{ $product->meta_title }}" placeholder="{{__('Meta Title')}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Description')}}</label>
								<div class="col-lg-7">
									<textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{ __('Meta Image') }}</label>
								<div class="col-lg-7">
									<div id="meta_photo">
										@if ($product->meta_img != null)
											<div class="col-md-4 col-sm-4 col-xs-6">
												<div class="img-upload-preview">
													<img loading="lazy"  src="{{ asset($product->meta_img) }}" alt="" class="img-responsive">
													<input type="hidden" name="previous_meta_img" value="{{ $product->meta_img }}">
													<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
				        </div>
						<div id="demo-stk-lft-tab-5" class="tab-pane fade">
							<div class="form-group">
								<div class="col-lg-2">
									<input type="text" class="form-control" value="{{__('Colors')}}" disabled>
								</div>
								<div class="col-lg-7">
									<select class="form-control color-var-select" name="colors[]" id="colors" multiple>
										@foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)
											<option value="{{ $color->code }}" <?php if(in_array($color->code, json_decode($product->colors))) echo 'selected'?> >{{ $color->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-lg-2">
									<label class="switch" style="margin-top:5px;">
										<input value="1" type="checkbox" name="colors_active" <?php if(count(json_decode($product->colors)) > 0) echo "checked";?> >
										<span class="slider round"></span>
									</label>
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-2">
									<input type="text" class="form-control" value="{{__('Attributes')}}" disabled>
								</div>
			                    <div class="col-lg-7">
			                        <select name="choice_attributes[]" id="choice_attributes" class="form-control demo-select2" multiple data-placeholder="Choose Attributes">
										@foreach (\App\Attribute::all() as $key => $attribute)
											<option value="{{ $attribute->id }}" @if($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif>{{ $attribute->name }}</option>
										@endforeach
			                        </select>
			                    </div>
			                </div>

							<div class="">
								<p>Choose the attributes of this product and then input values of each attribute</p>
								<br>
							</div>

							<div class="customer_choice_options" id="customer_choice_options">
								@foreach (json_decode($product->choice_options) as $key => $choice_option)
									<div class="form-group">
										<div class="col-lg-2">
											<input type="hidden" name="choice_no[]" value="{{ $choice_option->attribute_id }}">
											<input type="text" class="form-control" name="choice[]" value="{{ @(\App\Attribute::find($choice_option->attribute_id)->name) }}" placeholder="Choice Title" disabled>
										</div>
										<div class="col-lg-7">
											<input type="text" class="form-control" name="choice_options_{{ $choice_option->attribute_id }}[]" placeholder="Enter choice values" value="{{ implode(',', $choice_option->values) }}" data-role="tagsinput" onchange="update_sku()">
										</div>
										<div class="col-lg-2">
											<button onclick="delete_row(this)" class="btn btn-danger btn-icon"><i class="demo-psi-recycling icon-lg"></i></button>
										</div>
									</div>
								@endforeach
							</div>
							{{-- <div class="form-group">
								<div class="col-lg-2">
									<button type="button" class="btn btn-info" onclick="add_more_customer_choice_option()">{{ __('Add more customer choice option') }}</button>
								</div>
							</div> --}}
				        </div>
						<div id="demo-stk-lft-tab-6" class="tab-pane fade">
							<div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Unit price')}}<span class="required">*</span></label>
	                            <div class="col-lg-7">
	                                <input type="text" placeholder="{{__('Unit price')}}" name="unit_price" id="unit_price" class="form-control" value="{{$product->unit_price}}" required>
	                            </div>
	                        </div>
	                        <!-- <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Purchase price')}}<span class="required">*</span></label>
	                            <div class="col-lg-7">
	                                <input type="number" min="0" step="0.01" placeholder="{{__('Purchase price')}}" id="purchase_price" name="purchase_price" class="form-control" value="{{$product->purchase_price}}" required>
	                            </div>
	                        </div> -->
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('MRP')}}<span class="required">*</span></label>
	                            <div class="col-lg-3">
	                                <input type="number" min="0" step="0.01" placeholder="{{__('MRP')}}" name="mrp" id="mrp" class="form-control" value="{{$product->mrp}}" required>
	                            </div>
	                            
	                            <label class="col-lg-2 control-label">{{__('Minimum Stock QTY')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="number" min="0" step="1" placeholder="{{__('Minimum Stock QTY')}}" name="minstock" id="minstock" class="form-control" value="{{$product->minstock}}" required>
	                            </div>
	                            
	                        </div>
							<div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Discount')}}</label>
	                            <div class="col-lg-7">
	                                <input type="number" min="0" step="0.01" placeholder="{{__('Discount')}}" name="discount" class="form-control" value="{{ $product->discount }}" required>
	                            </div>
	                            <div class="col-lg-1">
	                                <select class="demo-select2" name="discount_type" required>
	                                	<option value="amount" <?php if($product->discount_type == 'amount') echo "selected";?> >₹</option>
	                                	<option value="percent" <?php if($product->discount_type == 'percent') echo "selected";?> >%</option>
	                                </select>
	                            </div>
	                        </div>
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('ERP Price')}}<span class="required">*</span></label>
	                            <div class="col-lg-3">
	                                <input type="number" min="0" step="0.01" placeholder="{{__('ERP Price')}}" name="erpprice" id="erpprice" class="form-control" value="{{$product->erpprice}}" required>
	                            </div>
	                        
	                            <label class="col-lg-2 control-label">{{__('Minimum Order QTY')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="number" min="1" step="1" placeholder="{{__('Minimum Order QTY')}}" name="minorderqty" id="minorderqty" class="form-control" value="{{$product->minorderqty}}" required>
	                            </div>
	                        </div>
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Maximum Quantity')}}<span class="required">*</span></label>
	                            <div class="col-lg-3">
	                                <input type="number" min="0" step="0.01" placeholder="{{__('Maximum Quantity')}}" id="maxorderqty" name="maxorderqty" class="form-control" value="{{$product->maxorderqty}}" required>
	                            </div>
	                        
	                            <label class="col-lg-2 control-label">{{__('On Rent')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <select class="form-control" name="onrent" id="onrent" required>
	                                	<option value="no" <?php if($product->onrent == 'no') echo "selected";?> >No</option>
	                                	<option value="yes" <?php if($product->onrent == 'yes') echo "selected";?> >Yes</option>
	                                </select>
	                            </div>
	                        </div>
	                        
	                        <div class="form-group rentoption" style="<?php if($product->onrent == 'no'){ echo 'display:none'; } ?>" id="rentoption">
	                            <label class="col-lg-2 control-label">{{__('Security Amount')}}<span class="required">*</span></label>
	                            <div class="col-lg-3">
	                                <input type="number" min="0" step="0.01" placeholder="{{__('Security Amount')}}" name="securityamount" id="securityamount" class="form-control" value="{{$product->securityamount}}" >
	                            </div>
	                        
	                            <label class="col-lg-2 control-label">{{__('Rent Amount')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="number" min="0" step="1" placeholder="{{__('rentamount')}}" name="rentamount" id="rentamount" class="form-control" value="{{$product->rentamount}}" >
	                            </div>
	                        </div>
	                        <div class="taxdiv">
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Tax Type')}}</label>
	                            
	                            <div class="col-lg-7">
	                                <select class="demo-select2" name="tax_type" required>
	                                	<option value="amount" <?php if($product->tax_type == 'amount') echo "selected";?> >Amount ₹</option>
	                                	<option value="percent" <?php if($product->tax_type == 'percent') echo "selected";?> > Percent %</option>
	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('GST')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control tax" name="tax" id="tax" required>
										<option value="0" <?php if($product->tax == '0') echo "selected";?> >0%</option>
	                                	<option value="5" <?php if($product->tax == '5') echo "selected";?> >5%</option>
	                                	<option value="12" <?php if($product->tax == '12') echo "selected";?> >12%</option>
	                                	<option value="18" <?php if($product->tax == '18') echo "selected";?> >18%</option>
	                                	<option value="28" <?php if($product->tax == '28') echo "selected";?> >28%</option>
	                                </select>
	                            </div>
	                            
	                        </div>
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('SGST')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control sgst" name="sgst" id="sgst" required>
										<option value="0" <?php if($product->sgst == '0') echo "selected";?> >0%</option>
	                                	<option value="2.5" <?php if($product->sgst == '2.5') echo "selected";?> >2.5%</option>
	                                	<option value="6" <?php if($product->sgst == '6') echo "selected";?> >6%</option>
	                                	<option value="9" <?php if($product->sgst == '9') echo "selected";?> >9%</option>
	                                	<option value="14" <?php if($product->sgst == '14') echo "selected";?> >14%</option>
	                                </select>
	                            </div>
	                            
	                        </div>
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('CGST')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control cgst" name="cgst" id="cgst" required>
										<option value="0" <?php if($product->cgst == '0') echo "selected";?> >0%</option>
	                                	<option value="2.5" <?php if($product->cgst == '2.5') echo "selected";?> >2.5%</option>
	                                	<option value="6" <?php if($product->cgst == '6') echo "selected";?> >6%</option>
	                                	<option value="9" <?php if($product->cgst == '9') echo "selected";?> >9%</option>
	                                	<option value="14" <?php if($product->cgst == '14') echo "selected";?> >14%</option>
	                                </select>
	                            </div>
	                           
	                        </div>
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('IGST')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control igst" name="igst" id="igst" required>
										<option value="0" <?php if($product->igst == '0') echo "selected";?> >0%</option>
	                                	<option value="5" <?php if($product->igst == '5') echo "selected";?> >5%</option>
	                                	<option value="12" <?php if($product->igst == '12') echo "selected";?> >12%</option>
	                                	<option value="18" <?php if($product->igst == '18') echo "selected";?> >18%</option>
	                                	<option value="28" <?php if($product->igst == '28') echo "selected";?> >28%</option>
	                                </select>
	                            </div>
	                           
	                        </div>
	                        </div>
	                     
							<div class="form-group" id="quantity">
								<label class="col-lg-2 control-label">{{__('Quantity')}}</label>
								<div class="col-lg-7">
									<input type="number" min="0" value="{{ $product->current_stock }}" step="1" placeholder="{{__('Quantity')}}" name="current_stock" class="form-control" required>
								</div>
							</div>
							<br>
							<div class="sku_combination" id="sku_combination">

							</div>
							<div class="product_variation col-lg-10" id="product_variation">
							<input type="hidden" name="row_counter"id="row_counter" @if($product->bulks) value="{{ sizeof($product->bulks) }}" @endif />
							<table class="table table-striped" id="product_variation_table">
								<tr>
									<th>Customer Role</th>
									<th>Variation</th>
									<th>Erp Price</th>
									<th>Quantity</th>
									<th>Price</th>
								</tr>
								@if($product->bulks)
								@foreach($product->bulks as $key => $bulk)
								<input type="hidden" name="bulk_id{{ $key + 1 }}" value="{{ $bulk->id }}">
								<tr>
									<td>
										<select class="form-control customercat" id="customercat{{ $key+1 }}" name="customercat{{ $key+1 }}">
											<option value="customer"
												<?php if($bulk->customertype == 'customer') echo "selected";?>
											>
												Customer
											</option> 
											<option value="institute"
												<?php if($bulk->customertype == 'institute') echo "selected";?>
											>
												Institute
											</option> 
											<option value="wholeseller"
												<?php if($bulk->customertype == 'wholeseller') echo "selected";?>
											>
												WholeSaler
											</option>
										</select>
									</td>
									<td>
									<select class="form-control variantcat newvar" id="variantcat{{ $key+1 }}" name="variantcat{{ $key+1 }}">
											@foreach($options as $opt)
												<option value="{{ $opt->variant }}"
													<?php if($bulk->product_stock_id == $opt->id) echo "selected";?>
												>{{ $opt->variant }}</option>
											@endforeach
										</select>
									</td>
									<td>
										<input type="text" name="erpprice{{ $key+1 }}" id="erpprice{{ $key+1 }}"  class="form-control erpprice" value="{{ $bulk->erpprice }}"/>
									</td>
									<td>
										<input type="number" name="varientqty{{ $key+1 }}" id="varientqty{{ $key+1 }}" step="1" class="form-control varientqty" value="{{ $bulk->qtyrange }}"/>
									</td>
									<td>
										<input type="number" step="0.1" name="varientprice{{ $key+1 }}" id="varientprice{{ $key+1 }}" class="form-control varientprice" value="{{ $bulk->overideprice }}" />
									</td>
								</tr>
								@endforeach
								@endif
							</table>
							<a href="Javascript:void(0)" id="addmorerow" class="btn btn-primary addmorerow">Add More</a>
							<a href="Javascript:void(0)" id="removerow" class="btn btn-primary removerow">Remove</a>
							</div>
						</div>
						<div id="demo-stk-lft-tab-7" class="tab-pane fade">
							<div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Description')}}</label>
	                            <div class="col-lg-9">
	                                <textarea class="editor" name="description">{{$product->description}}</textarea>
	                            </div>
	                        </div>
				        </div>
						{{-- <div id="demo-stk-lft-tab-8" class="tab-pane fade">

				        </div> --}}
						<div id="demo-stk-lft-tab-9" class="tab-pane fade">
							<div class="row bord-btm">
								<div class="col-md-2">
									<div class="panel-heading">
										<h3 class="panel-title">{{__('Free Shipping')}}</h3>
									</div>
								</div>
								<div class="col-md-10">
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Status')}}</label>
										<div class="col-lg-7">
											<label class="switch" style="margin-top:5px;">
												<input type="radio" name="shipping_type" value="free" @if($product->shipping_type == 'free') checked @endif>
												<span class="slider round"></span>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="row bord-btm">
								<div class="col-md-2">
									<div class="panel-heading">
										<h3 class="panel-title">{{__('Local Pickup')}}</h3>
									</div>
								</div>
								<div class="col-md-10">
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Status')}}</label>
										<div class="col-lg-7">
											<label class="switch" style="margin-top:5px;">
												<input type="radio" name="shipping_local" value="local_pickup" @if($product->shipping_local == 'local_pickup') checked @endif>
												<span class="slider round"></span>
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Shipping Local cost')}}</label>
										<div class="col-lg-7">
											<input type="number" min="0" step="0.01" placeholder="{{__('Shipping Local cost')}}" name="local_pickup_shipping_cost" class="form-control" value="{{ $product->shipping_local_cost }}" required>
										</div>
									</div>
								</div>
							</div>

							<div class="row bord-btm">
								<div class="col-md-2">
									<div class="panel-heading">
										<h3 class="panel-title">{{__('Flat Rate')}}</h3>
									</div>
								</div>
								<div class="col-md-10">
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Status')}}</label>
										<div class="col-lg-7">
											<label class="switch" style="margin-top:5px;">
												<input type="radio" name="shipping_type" value="flat_rate" @if($product->shipping_type == 'flat_rate') checked @endif>
												<span class="slider round"></span>
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Shipping cost')}}</label>
										<div class="col-lg-7">
											<input type="number" min="0" step="0.01" placeholder="{{__('Shipping cost')}}" name="flat_shipping_cost" class="form-control" value="{{ $product->shipping_cost }}" required>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
							<div class="col-md-2">
									<div class="panel-heading">
										<h3 class="panel-title">{{__('Pincode Range')}}</h3>
									</div>
								</div>
								<div class="col-md-10">
									
									<div class="form-group">
										
										<div class="col-lg-7">
											<input type="text"   placeholder="{{__('Pincode')}}" value="{{ $product->pincode_range }}" name="pincode_range" class="form-control" required>
										</div>
									</div>
								</div>
							</div>
				        </div>
						<div id="demo-stk-lft-tab-10" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('PDF Specification')}}</label>
								<div class="col-lg-7">
									<input type="file" class="form-control" placeholder="{{__('PDF')}}" name="pdf" accept="application/pdf">
								</div>
							</div>
				        </div>
				    </div>
				</div>
			</div>
			<div class="panel-footer text-right">
				<button type="submit" name="button" onclick="validateme()" class="btn btn-purple">{{ __('Save') }}</button>
			</div>
		</div>
	</form>
</div>

@endsection

@section('script')

<script type="text/javascript">

	// var i = $('input[name="choice_no[]"').last().val();
	// if(isNaN(i)){
	// 	i =0;
	// }

	function add_more_customer_choice_option(i, name){
		$('#customer_choice_options').append('<div class="form-group"><div class="col-lg-2"><input type="hidden" name="choice_no[]" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="'+name+'" readonly></div><div class="col-lg-7"><input type="text" class="form-control" name="choice_options_'+i+'[]" placeholder="Enter choice values" data-role="tagsinput" onchange="update_sku()"></div><div class="col-lg-2"><button onclick="delete_row(this)" class="btn btn-danger btn-icon"><i class="demo-psi-recycling icon-lg"></i></button></div></div>');
		$("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
	}

	$('input[name="colors_active"]').on('change', function() {
	    if(!$('input[name="colors_active"]').is(':checked')){
			$('#colors').prop('disabled', true);
		}
		else{
			$('#colors').prop('disabled', false);
		}
		update_sku();
	});

	$('#colors').on('change', function() {
	    update_sku();
	});

	// $('input[name="unit_price"]').on('keyup', function() {
	//     update_sku();
	// });

	function delete_row(em){
		$(em).closest('.form-group').remove();
		update_sku();
	}
	function uploadImageOnVarient(){
		$(".variant_image").spartanMultiImagePicker({
			fieldName:        'variant_image',
			maxCount:         1,
			rowHeight:        '200px',
			groupClassName:   'col-md-8 col-sm-8 col-xs-6',
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
}


$('body').on('click', '.remove-image', function() {
	var id = $(this).attr('data-id');
	$.ajax({
		   type:"GET",
		   url:"{{ url('/admin/products/deletevariantimage') }}/"+id,
		   data:{ id: id,_token: "{{ csrf_token() }}" },
		   success: function(data){
			  console.log(data);
			 // $(".img-upload-preview").load({{ url('/admin/products/admin/') }});
			// $('.variantfile').show();
			$(".variant_image_old_"+id).remove();
			$(".variant_image_new_"+id).show();
		   }
		});
	
	
});

function _check_book_condition()
{
	if($( "#onrent option:selected" ).val() == 'yes'){
		$('#rentoption').show();
		$('#securityamount').prop('required',true);
		$('#rentamount').prop('required',true);
	

		$('tr.varientrow').each (function() {
			//alert("found");
			// do your cool stuff
			var val = $('td.varientdata label').text();
		
			if(val == "new" || val =="old")
			{
				var mrpval = $('input.varientmrp').val();
				$( "#version").val('');
				$('#rentoption').hide();
				$('#securityamount').prop('required',false);
				$('#rentamount').prop('required',false);
			
				if($( "#version" ).val() == null){
					
					var mrp = parseFloat(mrpval); 
					//alert(mrp);
					var security = (mrp*80/100).toFixed(2);
					var rent = (mrp*30/100).toFixed(2);
					
					
					$('input.securityamountnew').val(security);
					$('input.rentamountnew').val(rent);
					
				}
			}
		});
		if($( "#version option:selected" ).val() == 'new'){
			$('#securityamount').val($('#mrp').val());
			var mrp = parseFloat($('#mrp').val());
			var rent = (mrp*40/100).toFixed(2);
			
			$('#rentamount').val(rent);
			}else{
			var mrp = parseFloat($('#mrp').val());
			var security = (mrp*80/100).toFixed(2);
			var rent = (mrp*30/100).toFixed(2);
			$('#securityamount').val(security);
			$('#rentamount').val(rent);	
			
			}
	}else
	{
		$('#securityamount').prop('required',false);
		$('#rentamount').prop('required',false);
		$('#rentoption').hide();	
		$('.securityamountnew').prop('required',false);
		$('.rentamountnew').prop('required',false);
	}
}



	function update_sku(){
		$.ajax({
		   type:"POST",
		   url:'{{ route('products.sku_combination_edit') }}',
		   data:$('#choice_form').serialize(),
		   success: function(data){
			_check_book_condition();
			   $('#sku_combination').html(data);

			   var opt = '';
					$("#sku_combination >table > tbody > tr > td > label").each(function() {
					//mvar += $(this).text();
					opt += '<option value="'+$(this).text()+'">'+$(this).text()+'</option>';
					var select = $('.newvar');
					select.empty().append(opt);
					});
					
					
			if (data.length > 1) {
				   $('#quantity').hide();
			   }
			   else {
					$('#quantity').show();
			   }
			  // uploadImageOnVarient();
			   
		   }
	   });
	}

	function get_subcategories_by_category(){
		var category_id = $('#category_id').val();
		$.post('{{ route('subcategories.get_subcategories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
		    $('#subcategory_id').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#subcategory_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		    }
		    $("#subcategory_id > option").each(function() {
		        if(this.value == '{{$product->subcategory_id}}'){
		            $("#subcategory_id").val(this.value).change();
		        }
		    });

		    $('.demo-select2').select2();

		    get_subsubcategories_by_subcategory();
		});
	}

	function get_subsubcategories_by_subcategory(){
		var subcategory_id = $('#subcategory_id').val();
		$.post('{{ route('subsubcategories.get_subsubcategories_by_subcategory') }}',{_token:'{{ csrf_token() }}', subcategory_id:subcategory_id}, function(data){
		    $('#subsubcategory_id').html(null);
			$('#subsubcategory_id').append($('<option>', {
				value: null,
				text: null
			}));
		    for (var i = 0; i < data.length; i++) {
		        $('#subsubcategory_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		    }
		    $("#subsubcategory_id > option").each(function() {
		        if(this.value == '{{$product->subsubcategory_id}}'){
		            $("#subsubcategory_id").val(this.value).change();
		        }
		    });

		    $('.demo-select2').select2();

		    //get_brands_by_subsubcategory();
			//get_attributes_by_subsubcategory();
		});
	}

	// function get_brands_by_subsubcategory(){
	// 	var subsubcategory_id = $('#subsubcategory_id').val();
	// 	$.post('{{ route('subsubcategories.get_brands_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
	// 	    $('#brand_id').html(null);
	// 	    for (var i = 0; i < data.length; i++) {
	// 	        $('#brand_id').append($('<option>', {
	// 	            value: data[i].id,
	// 	            text: data[i].name
	// 	        }));
	// 	    }
	// 	    $("#brand_id > option").each(function() {
	// 	        if(this.value == '{{$product->brand_id}}'){
	// 	            $("#brand_id").val(this.value).change();
	// 	        }
	// 	    });
	//
	// 	    $('.demo-select2').select2();
	//
	// 	});
	// }


   
   _check_book_condition();
	$('#onrent').change(function(e){
		_check_book_condition();
	});

	$('body').on('keyup', '.varientmrp', function() {
		var varient = $(this).parents('tr.varientrow');
		// varient.hide();
		
		
		//$('tr.varientrow').each (function() {
		// do your cool stuff
		var val = varient.find('td.varientdata label').text();
		
		if(val == "new" || val =="old")
		{
			var mrpval = varient.find('input.varientmrp').val();
			$( "#version").val('');
			$('#rentoption').hide();
			$('#securityamount').prop('required',false);
			$('#rentamount').prop('required',false);
		
			if($( "#version" ).val() == null){
				
				var mrp = parseFloat(mrpval); 
				//alert(mrp);
				var security = (mrp*80/100).toFixed(2);
				var rent = (mrp*30/100).toFixed(2);
				
				
				varient.find('input.securityamountnew').val(parseInt(security));
				varient.find('input.rentamountnew').val(parseInt(rent));
				
			}
		}
	
		//}); 
	});


	function get_attributes_by_subsubcategory(){
		var subsubcategory_id = $('#subsubcategory_id').val();
		$.post('{{ route('subsubcategories.get_attributes_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
		    $('#choice_attributes').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#choice_attributes').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		    }
			$("#choice_attributes > option").each(function() {
				var str = @php echo $product->attributes @endphp;
		        $("#choice_attributes").val(str).change();
		    });

			$('.demo-select2').select2();
		});
	}

	$(document).ready(function(){
		
	//	uploadImageOnVarient();
		$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
	    get_subcategories_by_category();
		$("#photos").spartanMultiImagePicker({
			fieldName:        'photos[]',
			maxCount:         10,
			rowHeight:        '200px',
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
		
		$("#thumbnail_img").spartanMultiImagePicker({
			fieldName:        'thumbnail_img',
			maxCount:         1,
			rowHeight:        '200px',
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
		$("#featured_img").spartanMultiImagePicker({
			fieldName:        'featured_img',
			maxCount:         1,
			rowHeight:        '200px',
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
		
		$("#flash_deal_img").spartanMultiImagePicker({
			fieldName:        'flash_deal_img',
			maxCount:         1,
			rowHeight:        '200px',
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
		$("#meta_photo").spartanMultiImagePicker({
			fieldName:        'meta_img',
			maxCount:         1,
			rowHeight:        '200px',
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

		update_sku();

		$('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
	});
	var option = $('option:selected', this).attr('data-id');
	   if(option == "yes")
	   {
		$('.taxdiv').show();

	   }
	   else{
		$('.taxdiv').hide();
	   }

	$('#category_id').on('change', function() {
	    get_subcategories_by_category();
		var option = $('option:selected', this).attr('data-id');
	   if(option == "yes")
	   {
		$('.taxdiv').show();

	   }
	   else{
		$('.taxdiv').hide();
	   }

	});

	$('#subcategory_id').on('change', function() {
	    get_subsubcategories_by_subcategory();
	});

	$('#subsubcategory_id').on('change', function() {
	    //get_brands_by_subsubcategory();
		//get_attributes_by_subsubcategory();
	});

	$('#choice_attributes').on('change', function() {
		//$('#customer_choice_options').html(null);
		$.each($("#choice_attributes option:selected"), function(j, attribute){
			flag = false;
			$('input[name="choice_no[]"]').each(function(i, choice_no) {
				if($(attribute).val() == $(choice_no).val()){
					flag = true;
				}
			});
            if(!flag){
				add_more_customer_choice_option($(attribute).val(), $(attribute).text());
			}
        });

		var str = @php echo $product->attributes @endphp;

		$.each(str, function(index, value){
			flag = false;
			$.each($("#choice_attributes option:selected"), function(j, attribute){
				if(value == $(attribute).val()){
					flag = true;
				}
			});
            if(!flag){
				//console.log();
				$('input[name="choice_no[]"][value="'+value+'"]').parent().parent().remove();
			}
		});

		update_sku();
	});


	$('#addmorerow').click(function(e){
	var counter = parseInt($('#row_counter').val());
	var i = counter+1;
	$('#row_counter').val(i);
	var opt = '';
	$("#sku_combination >table > tbody > tr > td > label").each(function() {
    //mvar += $(this).text();
    opt += '<option value="'+$(this).text()+'">'+$(this).text()+'</option>';
	});
	
	var htm = '<tr id="row_'+i+'">';
	/*
	var all = $('#sku_combination >table > tbody > tr > td > label').map(function() {
    return this.innerHTML;
}	).get();
*/
	htm += '<td><select class="form-control customercat" id="customercat'+i+'" name="customercat'+i+'"><option value="customer">Customer</option> <option value="institute">Institute</option> <option value="wholeseller">WholeSaler</option></select></td>';
	htm += '<td><select class="form-control variantcat" id="variantcat'+i+'" name="variantcat'+i+'">'+opt+'</select></td>';
	htm += '<td><input type="text" name="erpprice'+i+'" id="erpprice'+i+'" class="form-control erpprice" value=""  /></td>';
	htm += '<td><input type="number" name="varientqty'+i+'" id="varientqty'+i+'" step="1" class="form-control varientqty" value=""  /></td>';
	htm += '<td><input type="number" step="0.1" name="varientprice'+i+'" id="varientprice'+i+'" class="form-control varientprice" value=""  /></td></tr>';
	$('#product_variation_table').append(htm);
	});

$('#removerow').click(function(e){
	var counter = parseInt($('#row_counter').val());
	if(counter > 0){
		$('#row_'+counter).remove();
		counter = counter-1;
	$('#row_counter').val(counter);
		}
	
	
});

$('#onrent').change(function(e){
	
	if($( "#onrent option:selected" ).val() == 'yes'){
		$('#rentoption').show();
		$('#securityamount').prop('required',true);
		$('#rentamount').prop('required',true);
		if($( "#version option:selected" ).val() == 'new'){
			$('#securityamount').val($('#mrp').val());
			var mrp = parseFloat($('#mrp').val());
			var rent = (mrp*40/100).toFixed(2);
			
			$('#rentamount').val(rent);
			}else{
			var mrp = parseFloat($('#mrp').val());
			var security = (mrp*80/100).toFixed(2);
			var rent = (mrp*30/100).toFixed(2);
			$('#securityamount').val(security);
			$('#rentamount').val(rent);	
			
			}
		}else{
			$('#securityamount').prop('required',false);
		$('#rentamount').prop('required',false);
		$('#rentoption').hide();	
		}
	});
$('#tax').change(function(e){
	
	if($( "#tax option:selected" ).val() == '5'){
		$('#sgst').val('2.5');
		$('#cgst').val('2.5');
		$('#igst').val('5');
		}else if($( "#tax option:selected" ).val() == '12'){
		$('#sgst').val('6');
		$('#cgst').val('6');
		$('#igst').val('12');
		} else if($( "#tax option:selected" ).val() == '18'){
		$('#sgst').val('9');
		$('#cgst').val('9');
		$('#igst').val('18');
		} else if($( "#tax option:selected" ).val() == '28'){
		$('#sgst').val('14');
		$('#cgst').val('14');
		$('#igst').val('28');
		}
});

$('#version').change(function(e){
	if($( "#version option:selected" ).val() == 'new'){
		$('#oldisbn').attr('readonly','');
			$('#oldisbn').prop('readonly',false);
			if($( "#onrent option:selected" ).val() == 'yes'){
			$('#securityamount').val($('#mrp').val());
			var mrp = parseFloat($('#mrp').val());
			var rent = (mrp*40/100).toFixed(2);
			
			$('#rentamount').val(rent);
				}
		}else{
			$('#oldisbn').attr('readonly','readonly');
			$('#oldisbn').prop('readonly',true);
			$('#oldisbn').val($('#isbn').val());
			if($( "#onrent option:selected" ).val() == 'yes'){
			var mrp = parseFloat($('#mrp').val());
			var security = (mrp*80/100).toFixed(2);
			var rent = (mrp*30/100).toFixed(2);
			$('#securityamount').val(security);
			$('#rentamount').val(rent);	
				}
		}
	});
	
	function validateme(){
		var msg="";
                if (!document.getElementById("name").checkValidity()) { 
                    //$('#customerr').html("Product Name is required field");
                    msg += "Product Name is required field<br/>";
                }
                if (!document.getElementById("category_id").checkValidity()) { 
                    msg += "Category is required field<br/>";
                }
                // if (!document.getElementById("subcategory_id").checkValidity()) { 
                //     msg += "Subcategory is required field<br/>";
                // }
                if (!document.getElementById("brand_id").checkValidity()) { 
                    msg += "Brand is required field<br/>";
                }
                if (!document.getElementById("author").checkValidity()) { 
                    msg += "Author is required field.<br/>";
                }
                // if (!document.getElementById("unit").checkValidity()) { 
                //     msg += "Unit is required field.<br/>";
                // }
                if (!document.getElementById("isbn").checkValidity()) { 
                    msg += "ISBN is required field.<br/>";
                }
                if (!document.getElementById("oldisbn").checkValidity()) { 
                    msg += "Old ISBN is required field.<br/>";
                }
                if (!document.getElementById("unit_price").checkValidity()) { 
                    msg += "Unit Price is required field.<br/>";
                }
                // if (!document.getElementById("purchase_price").checkValidity()) { 
                //     msg += "Purchase Price is required field.<br/>";
                // }
                if (!document.getElementById("mrp").checkValidity()) { 
                    msg += "MRP is required field.<br/>";
                }
                if (!document.getElementById("minorderqty").checkValidity()) { 
                    msg += "Minimum Order Quantity is required field.<br/>";
                }
                if (!document.getElementById("minstock").checkValidity()) { 
                    msg += "Minimum Stock is required field.<br/>";
                }
                if (!document.getElementById("erpprice").checkValidity()) { 
                    msg += "Erp Price is required field.<br/>";
                }
                if (!document.getElementById("maxorderqty").checkValidity()) { 
                    msg += "Maximum Stock is required field.<br/>";
                }
                if($( "#onrent option:selected" ).val() == 'yes'){
                if (!document.getElementById("securityamount").checkValidity()) { 
                    msg += "Security Amount is required field.<br/>";
                }
                if (!document.getElementById("rentamount").checkValidity()) { 
                    msg += "rent Amount is required field.<br/>";
                }
			}
			
                if(msg != ''){
					$('#customerr').show();
					$('#customerr').html(msg);
					}
		}
</script>

@endsection
<style>
span.required {
	color: red;
	font-size: 17px;
	padding: 0px 0px 0px 4px;
	margin-top: 0px;
}
</style>
