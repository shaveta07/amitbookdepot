@if(count($combinations[0]) > 0)

	<table class="table table-bordered">
		<thead>
			<tr>
				<td class="text-center">
					<label for="" class="control-label">{{__('Variant')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('ISBN')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Rent Amount')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Rent Security')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Old ISBN')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Variant Price')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('SKU')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Quantity')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('MRP')}}</label>
				</td>
				<td class="text-center">
					<label for="" class="control-label">{{__('Image')}}</label>
				</td>
			</tr>
		</thead>
		<tbody>

@foreach ($combinations as $key => $combination)
	@php
	
		$sku = '';
		foreach (explode(' ', $product_name) as $key => $value) {
			$sku .= substr($value, 0, 1);
		}

		$str = '';
		foreach ($combination as $key => $item){
			if($key > 0 ){
				$str .= '-'.str_replace(' ', '', $item);
				$sku .='-'.str_replace(' ', '', $item);
			}
			else{
				if($colors_active == 1){
					$color_name = \App\Color::where('code', $item)->first()->name;
					$str .= $color_name;
					$sku .='-'.$color_name;
				}
				else{
					$str .= str_replace(' ', '', $item);
					$sku .='-'.str_replace(' ', '', $item);
				}
			}
		}
	@endphp
	@if(strlen($str) > 0)
	<tr class="varientrow">
				<td class="varientdata">
				<label for="" class="control-label">{{ $str }}</label>
			</td>
			<td>
				<input type="text" name="isbns_{{ $str }}" value="@php
                    if(($stock = $product->stocks->where('variant', $str)->first()) != null){
	                        echo $stock->isbn;
	                    }
	                    
                @endphp" class="form-control" required>
			</td>
			<td>
					<input type="text" name="rent_amount_{{ $str }}"  value="" class="form-control rentamountnew" >
				</td>
				<td>
					<input type="number" name="rent_security_{{ $str }}" value=""  class="form-control securityamountnew" >
				</td>
				<td>
					<input type="text" data-val ="{{ $str }}" name="oldisbns_{{ $str }}" value="" class="form-control" >
				</td>
			<td>
				<input type="number" name="price_{{ $str }}" value="@php
                    if ($product->unit_price == $unit_price) {
						if(($stock = $product->stocks->where('variant', $str)->first()) != null){
	                        echo $stock->price;
	                    }
	                    else{
	                        echo $unit_price;
	                    }
                    }
					else{
						echo $unit_price;
					}
                @endphp" min="0" step="0.01" class="form-control" required>
			</td>
			<td>
				<input type="text" name="sku_{{ $str }}" value="{{ $sku }}" class="form-control" required>
			</td>
			<td>
				<input type="number" name="qty_{{ $str }}" value="@php
                    if(($stock = $product->stocks->where('variant', $str)->first()) != null){
                        echo $stock->qty;
                    }
                    else{
                        echo '10';
                    }
                @endphp" min="0" step="1" class="form-control" required>
			</td>
			<td>
				<input type="text" name="mrps_{{ $str }}" value="@php
                    if(($stock = $product->stocks->where('variant', $str)->first()) != null){
	                        echo $stock->mrp;
	                    }
	                    
                @endphp" class="form-control varientmrp" required>
			</td>
			<td>

				<div class ="varaint_image">
					@php $product_stock = $product->stocks->where('variant', $str)->first(); @endphp
					@if($product_stock != null)
					@if ($product_stock->variant_image != NULL)
						<div class="col-md-8 col-sm-8 col-xs-8">
							<div class="img-upload-preview variant_image_old_{{ $product_stock->id }}" >
								<img loading="lazy"  src="{{ asset($product_stock->variant_image) }}" alt="" class="img-responsive">
								<input type="hidden" class="variantName" name="previous_variant_image_{{ $str }}" value="{{ $product_stock->variant_image }}">
								<button type="button" data-id="{{$product_stock->id}}" class="btn btn-danger close-btn remove-image"><i class="fa fa-times"></i></button>
								
							</div>
							<input type="file" class="variantfile variant_image_new_{{ $product_stock->id }}" name="variant_image_{{ $str }}" style="display:none;">
						</div>
						@else
						<input type="file" class="variantImage" id="customFile" name="variant_image_{{ $str }}">
					@endif
					@else
						<input type="file" class="variantImage" id="customFile" name="variant_image_{{ $str }}">
					@endif
				</div>
		
			</td>
		</tr>
	@endif
@endforeach

	</tbody>
</table>
@endif
