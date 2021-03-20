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
					<label  class="control-label">{{ $str }}</label>
				</td>
				<td>
					<input type="text" data-val ="{{ $str }}" name="isbns_{{ $str }}" value="" class="form-control isbnvart" required>
				</td>
				
				<td>
					<input type="text" name="rent_amount_{{ $str }}"  value="" class="form-control rentamountnew" >
				</td>
				<td>
					<input type="number" name="rent_security_{{ $str }}" value=""  class="form-control securityamountnew" >
				</td>
				<td>
					<input type="text" data-val ="{{ $str }}" name="oldisbns_{{ $str }}" value="" class="form-control isbnvart" >
				</td>
				<td>
					<input type="number" name="price_{{ $str }}" value="{{ $unit_price }}" min="0" step="0.01" class="form-control" required>
				</td>
				<td>
					<input type="text" name="sku_{{ $str }}" value="{{ $sku }}" class="form-control" required>
				</td>
				
				<td>
					<input type="number" name="qty_{{ $str }}" value="10" min="0" step="1" class="form-control" required>
				</td>
				<td>
					<input type="text" name="mrps_{{ $str }}" value="0" class="form-control varientmrp" required>
				</td>
				<td>
					<input type="file" class="variantImage" id="customFile" name="variant_image_{{ $str }}">
					<!-- <div class="variant_image">

					</div> -->
				</td>
			</tr>
	@endif
@endforeach
	</tbody>
</table>
@endif
