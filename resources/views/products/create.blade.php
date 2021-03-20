@extends('layouts.app')

@section('content')

<div class="row">
	<form class="form form-horizontal mar-top" action="{{route('products.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
		@csrf
		<input type="hidden" name="added_by" value="admin">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Product Information')}}</h3>
			</div>
			<div class="panel-body">
			<div>
			@if ($errors->has('isbn'))
				<div class="alert alert-danger">	{{ $errors->first('isbn') }}</div>
				@endif
			</div>
		
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
									<input type="text" class="form-control" id="name" value="{{ old('name') }}" name="name" placeholder="{{__('Product Name')}}" onchange="update_sku()" required>
								</div>
							</div>
							<div class="form-group" id="category">
								<label class="col-lg-2 control-label">{{__('Category')}}<span class="required">*</span></label>
								<div class="col-lg-7">
									<select class="form-control demo-select2-placeholder" name="category_id" id="category_id" required>
									<option value="">{{ ('Select Category') }}</option>
										@foreach($categories as $category)
											<option data-id="{{$category->istaxapplicable}}" value="{{$category->id}}">{{__($category->name)}}</option>
										@endforeach
									</select>
								</div>
							</div>
						
							<div class="form-group" id="subcategory">
								<label class="col-lg-2 control-label">{{__('Subcategory')}}</label>
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
											<option value="{{ $brand->id }}">{{ $brand->name }}</option>
										@endforeach
									</select>
								</div>

							</div>
							
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Country of Origin')}}</label>
									<div class="col-lg-7">
										<input type="text" id="origin" class="form-control" value="india" name="origin" placeholder="" >
									</div>
								</div>	
								<!-- <div class="form-group">
								<label class="col-lg-2 control-label">{{__('Track ID')}}</label>
									<div class="col-lg-7">
										<input type="text" id="trackid" class="form-control" value="{{ old('track_id') }}" name="track_id" placeholder="" >
									</div>
								</div>	 -->
							
							
							<div class="form-group">
								
								<label class="col-lg-2 control-label">{{__('Author')}}<span class="required">*</span></label>
								<div class="col-lg-3">
									<select class="form-control demo-select2-placeholder" name="author_id" id="author" required>
										<option value="">{{ ('Select Author') }}</option>
										@foreach (\App\Author::all() as $author)
											<option value="{{ $author->id }}">{{ $author->name }}</option>
										@endforeach
									</select>
								</div>
							
	                            
								<label class="col-lg-1 control-label">{{__('Unit')}}</label>
								<div class="col-lg-3">
									<input type="text" id="unit" class="form-control" name="unit" value="{{ old('unit') }}"  placeholder="Unit (e.g. KG, Pc etc)" >
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Dimension Weight')}}</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" name="weight_dimension" value="{{ old('weight_dimension') }}"  placeholder="weight dimension(grm,kilogram etc)" >
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Tags')}}</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" name="tags[]" value="{{ old('tags') }}"  placeholder="Type to add a tag" data-role="tagsinput">
								</div>
							</div>
							
							<div class="form-group">

	                            <label class="col-lg-2 control-label">{{__('ISBN')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="text" class="form-control" name="isbn" id="isbn" value="{{ old('isbn') }}"  placeholder="ISBN" required />
	                            </div>
	                            
	                            <label class="col-lg-1 control-label">{{__('Version')}}<span class="required">*</span></label>
	                            <div class="col-lg-1">
									<select class="form-control" name="version" id="version">
										<option value="new" >New</option>
										<option value="old"  >Old</option>
									</select>
	                                
	                            </div>
	                            
	                            <label class="col-lg-1 control-label">{{__('OLD ISBN')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="text" class="form-control" name="oldisbn" value="{{ old('oldisbn') }}"  id="oldisbn" placeholder="ISBN" required />
	                            </div>
	                        </div>
							
							@php
							    $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
							@endphp
							@if ($pos_addon != null && $pos_addon->activated == 1)
								<div class="form-group">
									<label class="col-lg-2 control-label">{{__('Barcode')}}</label>
									<div class="col-lg-7">
										<input type="text" class="form-control" value="{{ old('barcode') }}"  name="barcode" placeholder="{{ ('Barcode') }}">
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
											<input type="checkbox" name="refundable" checked>
				                            <span class="slider round"></span></label>
										</label>
									</div>
								</div>
							@endif
				        </div>
				        <div id="demo-stk-lft-tab-2" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Main Images')}} </label>
								<div class="col-lg-7">
									<div id="photos">

									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Thumbnail Image')}} <small>(290x300)</small></label>
								<div class="col-lg-7">
									<div id="thumbnail_img">

									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Featured')}} <small>(290x300)</small></label>
								<div class="col-lg-7">
									<div id="featured_img">

									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Flash Deal')}} <small>(290x300)</small></label>
								<div class="col-lg-7">
									<div id="flash_deal_img">

									</div>
								</div>
							</div>
				        </div>
				        <div id="demo-stk-lft-tab-3" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Video Provider')}}</label>
								<div class="col-lg-7">
									<select class="form-control demo-select2-placeholder" name="video_provider" id="video_provider">
										<option value="youtube">{{__('Youtube')}}</option>
										<option value="dailymotion">{{__('Dailymotion')}}</option>
										<option value="vimeo">{{__('Vimeo')}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Video Link')}}</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" value="{{ old('video_link') }}" name="video_link" placeholder="{{__('Video Link')}}">
								</div>
							</div>
				        </div>
						<div id="demo-stk-lft-tab-4" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Meta Title')}}</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" value="{{ old('meta_title') }}" name="meta_title" placeholder="{{__('Meta Title')}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Description')}}</label>
								<div class="col-lg-7">
									<textarea name="meta_description" value="{{ old('meta_description') }}" rows="8" class="form-control"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{ __('Meta Image') }}</label>
								<div class="col-lg-7">
									<div id="meta_photo">

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
									<select class="form-control color-var-select" name="colors[]" id="colors" multiple disabled>
										@foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)
											<option value="{{ $color->code }}">{{ $color->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-lg-2">
									<label class="switch" style="margin-top:5px;">
										<input value="1" type="checkbox" name="colors_active">
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
											<option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
										@endforeach
			                        </select>
			                    </div>
			                </div>

							<div>
								<p>Choose the attributes of this product and then input values of each attribute</p>
								<br>
							</div>

							<div class="customer_choice_options" id="customer_choice_options">

							</div>

							{{-- <div class="customer_choice_options" id="customer_choice_options">

							</div>
							<div class="form-group">
								<div class="col-lg-2">
									<button type="button" class="btn btn-info" onclick="add_more_customer_choice_option()">{{ __('Add more customer choice option') }}</button>
								</div>
							</div> --}}
				        </div>

						<div id="demo-stk-lft-tab-6" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Unit price')}}<span class="required">*</span></label>
								<div class="col-lg-7">
									<input type="number" min="0" value="{{ (old('unit_price')!==null) ? old('unit_price') : '0' }}" step="0.01" placeholder="{{__('Unit price')}}" id="unit_price" name="unit_price" class="form-control" required>
								</div>
							</div>
							<!-- <div class="form-group">
								<label class="col-lg-2 control-label">{{__('Purchase price')}}<span class="required">*</span></label>
								<div class="col-lg-7">
									<input type="number" min="0" value="{{ (old('purchase_price')!==null) ? old('purchase_price') : '0' }}" step="0.01" placeholder="{{__('Purchase price')}}" id="purchase_price" name="purchase_price" class="form-control" required>
								</div>
							</div> -->
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('MRP')}}<span class="required">*</span></label>
								<div class="col-lg-3">
									<input type="number" min="0" value="{{ (old('mrp')!==null) ? old('mrp') : '0' }}" step="0.01" placeholder="{{__('MRP')}}" id="mrp" name="mrp" class="form-control" required>
								</div>
								
								<label class="col-lg-2 control-label">{{__('Minimum Stock QTY')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="number" min="0" value="0" step="1" placeholder="{{__('Minimum Stock QTY')}}" name="minstock" id="minstock" class="form-control" required>
	                            </div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Discount')}}</label>
								<div class="col-lg-5">
									<input type="number" min="0" value="0" step="0.01" placeholder="{{__('Discount')}}" value="{{ old('discount') }}" name="discount" class="form-control" required>
								</div>
								<div class="col-lg-2">
									<select class="demo-select2" name="discount_type">
										<option value="amount">$</option>
										<option value="percent">%</option>
									</select>
								</div>
							</div>
							
							<div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('ERP Price')}}<span class="required">*</span></label>
	                            <div class="col-lg-3">
	                                <input type="number" min="0" step="0.01" value="{{ old('erpprice') }}" placeholder="{{__('ERP Price')}}" name="erpprice" id="erpprice" class="form-control" required>
	                            </div>
	                        
	                            <label class="col-lg-2 control-label">{{__('Minimum Order QTY')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="number" min="1" step="1" value="1" placeholder="{{__('Minimum Order QTY')}}" id="minorderqty" name="minorderqty" class="form-control" required>
	                            </div>
	                        </div>
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Maximum Quantity')}}<span class="required">*</span></label>
	                            <div class="col-lg-3">
	                                <input type="number" min="0" step="0.01" value="{{ old('maxorderqty') }}" placeholder="{{__('Maximum Quantity')}}" id="maxorderqty" name="maxorderqty" class="form-control" required>
	                            </div>
	                        
	                            <label class="col-lg-2 control-label">{{__('On Rent')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <select class="form-control" name="onrent" id="onrent" required>
	                                	<option value="no" >No</option>
	                                	<option value="yes" >Yes</option>
	                                </select>
	                            </div>
	                        </div>
	                     
	                        <div class="form-group rentoption" style="display:none" id="rentoption">
	                            <label class="col-lg-2 control-label">{{__('Security Amount')}}<span class="required">*</span></label>
	                            <div class="col-lg-3">
	                                <input type="number" min="0" step="0.01" value="{{ old('securityamount') }}" placeholder="{{__('Security Amount')}}" name="securityamount" id="securityamount" class="form-control" >
	                            </div>
	                        
	                            <label class="col-lg-2 control-label">{{__('Rent Amount')}}<span class="required">*</span></label>
	                            <div class="col-lg-2">
	                                <input type="number" min="0" step="1" value="{{ old('rentamount') }}" placeholder="{{__('rentamount')}}" name="rentamount" id="rentamount" class="form-control" >
	                            </div>
	                        </div>
						
							
							<div class="taxdiv">
							
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Tax Type')}}</label>
								
								<div class="col-lg-7">
									<select class="demo-select2" name="tax_type">
										<option value="amount">Amount ₹</option>
										<option value="percent">Percent %</option>
									</select>
								</div>
							</div>
							
							<div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('GST')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control" name="tax" required>
										<option value="0">0%</option>
	                                	<option value="5">5%</option>
	                                	<option value="12">12%</option>
	                                	<option value="18">18%</option>
	                                	<option value="28">28%</option>
	                                </select>
	                            </div>
	                            
	                        </div>
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('SGST')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control" name="sgst" required>
										<option value="0">0%</option>
	                                	<option value="2.5">2.5%</option>
	                                	<option value="6">6%</option>
	                                	<option value="9">9%</option>
	                                	<option value="14">14%</option>
	                                </select>
	                            </div>
	                            
	                        </div>
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('CGST')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control" name="cgst" required>
										<option value="0" >0%</option>
	                                	<option value="2.5">2.5%</option>
	                                	<option value="6">6%</option>
	                                	<option value="9">9%</option>
	                                	<option value="14">14%</option>
	                                </select>
	                            </div>
	                            
	                        </div>
	                        
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('IGST')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control" name="igst" required>
										<option value="0">0%</option>
	                                	<option value="5" >5%</option>
	                                	<option value="12">12%</option>
	                                	<option value="18">18%</option>
	                                	<option value="28">28%</option>
	                                </select>
	                            </div>
	                            
	                        </div>
							</div>
							
							<div class="form-group" id="quantity">
								<label class="col-lg-2 control-label">{{__('Quantity')}}</label>
								<div class="col-lg-7">
									<input type="number" min="0" value="0" step="1" placeholder="{{__('Quantity')}}" value="{{ old('current_stock') }}" name="current_stock" class="form-control" required>
								</div>
							</div>
							<br>
							<div class="sku_combination" id="sku_combination">

							</div>
							
							<div class="product_variation col-lg-10" id="product_variation">
							<input type="hidden" name="row_counter"id="row_counter" value="0" />
							<table class="table table-striped" id="product_variation_table">
							<tr>
							<th>Customer Role</th>
							<th>Variation</th>
							<th>Erp Price</th>
							<th>Quantity</th>
							<th>Price</th>
							</tr>
							
							
							</table>
							<a href="Javascript:void(0)" id="addmorerow" class="btn btn-primary addmorerow">Add More</a>
							<a href="Javascript:void(0)" id="removerow" class="btn btn-primary removerow">Remove</a>
							</div>
				        </div>
						<div id="demo-stk-lft-tab-7" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Description')}}</label>
								<div class="col-lg-9">
									<textarea class="editor" value="{{ old('description') }}"  name="description"></textarea>
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
												<input type="radio" name="shipping_type" value="free" checked>
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
												<input type="radio" name="shipping_local" value="local_pickup" >
												<span class="slider round"></span>
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Shipping Local cost')}}</label>
										<div class="col-lg-7">
											<input type="number" min="0" step="0.01" placeholder="{{__('Shipping Local cost')}}" value="{{ old('local_pickup_shipping_cost') }}" name="local_pickup_shipping_cost" class="form-control"  required>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
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
												<input type="radio" name="shipping_type" value="flat_rate" checked>
												<span class="slider round"></span>
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Shipping cost')}}</label>
										<div class="col-lg-7">
											<input type="number" min="0" value="0" step="0.01" placeholder="{{__('Shipping cost')}}" value="{{ old('flat_shipping_cost') }}" name="flat_shipping_cost" class="form-control" required>
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
											<input type="text"   placeholder="{{__('Pincode')}}" value="" name="pincode_range" class="form-control" required>
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
				<button type="submit" id="saveproduct" onclick="validateme()" name="button" class="btn btn-info">{{ __('Save') }}</button>
			</div>
		</div>
	</form>
</div>


@endsection

@section('script')

<script type="text/javascript">
// 	$('#varientrow tr').each(function() {
//     var val = $(this).find(".varientdata td").html();    
// 	if(val == 'old')
// 	{
// 		$('#rentoptionnew').hide();
// 		$('#rentoptionold').show();
// 		$('#rentoption').hide();
// 	}
// 	if(val == 'new'){
// 		$('#rentoptionnew').show();
// 		$('#rentoptionold').hide();
// 		$('#rentoption').hide();
// 	}
// 	else{
// 		$('#rentoptionnew').hide();
// 		$('#rentoptionold').hide();
// 		$('#rentoption').show();
// 	}
//  });
	function add_more_customer_choice_option(i, name){
		$('#customer_choice_options').append('<div class="form-group"><div class="col-lg-2"><input type="hidden" name="choice_no[]" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="Choice Title" readonly></div><div class="col-lg-7"><input type="text" class="form-control" name="choice_options_'+i+'[]" placeholder="Enter choice values" data-role="tagsinput" onchange="update_sku()"></div></div>');

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
	$('.taxdiv').hide();

	$('input[name="unit_price"]').on('keyup', function() {
	    update_sku();
	});

	$('input[name="name"]').on('keyup', function() {
	    update_sku();
	});
	$('input[name="isbn"]').on('focusout', function() {
		$.post('{{ route('products.isbnvaldiate') }}',{ isbn:$(this).val(), _token:'{{ csrf_token() }}'}, function(data){
		 // console.log(data);
		 var data = JSON.parse(data);
		 if(data.status == "true")
		 {
			 alert(data.isbn + ' ISBN Should be Unique');
			 $('#saveproduct').prop("disabled", true);
		 }
		 else{
			$('#saveproduct').prop("disabled", false);
		 }

		});
		
	   
	});

$('#saveproduct').prop("disabled", true);

	$('body').on('focusout', '.isbnvart', function() {	
		
		var _item_id = $(this).attr('data-val');
		var val = $(this).val();
		$.post('{{ route('products.isbnvaldiate') }}',{ isbn: val, _token:'{{ csrf_token() }}'}, function(data){
	//	 console.log(data);
		 var data = JSON.parse(data);
		 if(data.status == "true")
		 {
			alert(data.isbn + ' ISBN Should be Unique');
			 $('#saveproduct').prop("disabled", true);
		 }
		 else{
			$('#saveproduct').prop("disabled", false);
		 }

		});
		
	});


	
	
	function delete_row(em){
		$(em).closest('.form-group').remove();
		update_sku();
	}

// function uploadImageOnVarient(){
// 	$(".variant_image").spartanMultiImagePicker({
// 			fieldName:        'variant_image',
// 			maxCount:         1,
// 			rowHeight:        '200px',
// 			groupClassName:   'col-md-8 col-sm-8 col-xs-8',
// 			maxFileSize:      '',
// 			dropFileLabel : "Drop Here",
// 			onExtensionErr : function(index, file){
// 				console.log(index, file,  'extension err');
// 				alert('Please only input png or jpg type file')
// 			},
// 			onSizeErr : function(index, file){
// 				console.log(index, file,  'file size too big');
// 				alert('File size too big');
// 			}
// 		});
// }
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
		   url:'{{ route('products.sku_combination') }}',
		   data:$('#choice_form').serialize(),
		   success: function(data){
			   //console.log(data);
			   _check_book_condition();
			   
			   $('#sku_combination').html(data);
			   if (data.length > 1) {
				   $('#quantity').hide();
			   }
			   else {
					$('#quantity').show();
			   }
			   //uploadImageOnVarient();
		   }
	   });
	   for(var i=0; i<20; i++){
				$('#removerow').click();
			}
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
		        $('.demo-select2').select2();
		    }
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
		        $('.demo-select2').select2();
		    }
		    //get_brands_by_subsubcategory();
			//get_attributes_by_subsubcategory();
		});
	}

	function get_brands_by_subsubcategory(){
		var subsubcategory_id = $('#subsubcategory_id').val();
		$.post('{{ route('subsubcategories.get_brands_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
		    $('#brand_id').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#brand_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		        $('.demo-select2').select2();
		    }
		});
	}

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
			$('.demo-select2').select2();
		});
	}

	$(document).ready(function(){
		// uploadImageOnVarient();
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
	});

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
	    // get_brands_by_subsubcategory();
		//get_attributes_by_subsubcategory();
	});

	$('#choice_attributes').on('change', function() {
		$('#customer_choice_options').html(null);
		$.each($("#choice_attributes option:selected"), function(){
			//console.log($(this).val());
            add_more_customer_choice_option($(this).val(), $(this).text());
        });
		update_sku();
	});
/*
$('#addmorerow').click(function(e){
	var counter = parseInt($('#row_counter').val());
	var i = counter+1;
	$('#row_counter').val(i);
	var htm = '<tr id="row_'+i+'"><td><select class="form-control customercat" id="customercat'+i+'" name="customercat'+i+'"><option value="customer">Customer</option> <option value="institute">Institute</option> <option value="wholeseller">WhileSaler</option></select></td>';
	htm += '<td><select class="form-control variantcat" id="variantcat'+i+'" name="variantcat'+i+'"><option value="L">L</option></select></td>';
	htm += '<td><input type="number" name="varientqty'+i+'" id="varientqty'+i+'" step="1" class="form-control varientqty" value=""  /></td>';
	htm += '<td><input type="number" step="0.1" name="varientprice'+i+'" id="varientprice'+i+'" class="form-control varientprice" value=""  /></td></tr>';
	$('#product_variation_table').append(htm);
	});
*/
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
				
				
				varient.find('input.securityamountnew').val(security);
				varient.find('input.rentamountnew').val(rent);
				
			}
		}
	
		//}); 
	});




$('#tax').change(function(e){
		$('tr.varientrow').each (function() {
		// do your cool stuff
		var val = $('td.varientdata label').text();
		
		if(val == "new")
		{
			var mrpval = $('input.varientmrp').val();
			$( "#version").val('');
			$('#rentoption').hide();
			$('#securityamount').prop('required',false);
			$('#rentamount').prop('required',false);
		
			if($( "#version" ).val() == null){
				$('.securityamountnew').val(mrpval);
				var mrp = parseFloat(mrpval);
				var rent = (mrp*40/100).toFixed(2);
				$('.rentamountnew').val(rent);
				$('input.varientmrp').on('keyup',function(){
					var mrpval = $('input.varientmrp').val();
					$('.securityamountnew').val(mrpval);
					var mrp = parseFloat(mrpval);
					var rent = (mrp*40/100).toFixed(2);
					$('.rentamountnew').val(rent);

				});
			}
		}
		if(val == "old")
		{
			var mrpval = $('input.varientmrp').val();
			
			$( "#version").val('');
			$('#rentoption').hide();
			$('#securityamount').prop('required',false);
			$('#rentamount').prop('required',false);
			
			if($("#version").val() == null){

			var mrp = parseFloat(mrpval); 
			//alert(mrp);
			var security = (mrp*80/100).toFixed(2);
			var rent = (mrp*30/100).toFixed(2);
			$('.securityamountnew').val(security);
			$('.rentamountnew').val(rent);
			$('input.varientmrp').on('keyup',function(){
				
				var mrpval = $('input.varientmrp').val();
				var mrp = parseFloat(mrpval); 
				//alert(mrp);
				var security = (mrp*80/100).toFixed(2);
				var rent = (mrp*30/100).toFixed(2);
				$('.securityamountnew').val(security);
				$('.rentamountnew').val(rent);
			});	
			}
		}
		}); 
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
