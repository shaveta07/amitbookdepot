@extends('frontend.layouts.app')

@section('content')
<div id="page-content">
        <section class="slice-xs sct-color-2 border-bottom">
            <div class="container container-sm">
                <div class="row cols-delimited justify-content-center">
                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-shopping-cart"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. {{__('My Cart')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon mb-0 c-gray-light">
                                <i class="la la-map-o"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="la la-truck"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Delivery info')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-credit-card"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">4. {{__('Payment')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-check-circle"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">5. {{__('Confirmation')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-xl-8">
                        <form class="form-default" data-toggle="validator" action="{{ route('checkout.store_delivery_info') }}" role="form" method="POST">
                            @csrf
                            @php
                            $tax = 0;
							$subtotal = $shipping = 0;
							$cod=0;
                                $admin_products = array();
                                $seller_products = array();
                                foreach (Session::get('cart') as $key => $cartItem){
                                    if(\App\Product::find($cartItem['id'])->added_by == 'admin'){
                                        //array_push($admin_products, $cartItem['id']);
                                        $admin_products[] = array("id"=>$cartItem['id'],"quantity"=>$cartItem['quantity']);
                                    }
                                    else{
                                        $product_ids = array();
                                        if(array_key_exists(\App\Product::find($cartItem['id'])->user_id, $seller_products)){
                                            $product_ids = $seller_products[\App\Product::find($cartItem['id'])->user_id];
                                        }
                                        array_push($product_ids, $cartItem['id']);
                                        $seller_products[\App\Product::find($cartItem['id'])->user_id] = $product_ids;
                                    }
                                }
                                $subtotal += $cartItem['price']*$cartItem['quantity'];
								$tax += $cartItem['tax']*$cartItem['quantity'];
                            @endphp

                            @if (!empty($admin_products))
                            <div class="card mb-3">
                                <div class="card-header bg-white py-3">
                                    <h5 class="heading-6 mb-0">{{ \App\GeneralSetting::first()->site_name }} Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table-cart">
                                                <tbody>
                                                <?php  
                                                $flat_rate_admin=0;
                                                $local_rate =0;
                                                ?>
                                                     @foreach ($admin_products as $cartproduct)
                                                    <tr class="cart-item">
                                                        <td class="product-image" width="25%">
                                                            <a href="<?= route('product', \App\Product::find($cartproduct['id'])->slug) ?>" target="_blank">
                                                                <img loading="lazy"  src="<?=  asset(\App\Product::find($cartproduct['id'])->thumbnail_img)  ?>">
                                                            </a>
                                                        </td>
                                                        <td class="product-name strong-600">
                                                            <a href="<?= route('product', \App\Product::find($cartproduct['id'])->slug) ?>" target="_blank" class="d-block c-base-2">
                                                                {{ \App\Product::find($cartproduct['id'])->name }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                             //print_r($admin_products);die;
                                            if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'product_wise_shipping') {
												$flat_rate_admin += \App\Product::find($cartproduct['id'])->shipping_cost*$cartproduct['quantity'] ;
                                                   $local_rate += \App\Product::find($cartproduct['id'])->shipping_local_cost*$cartproduct['quantity'];
											}else{
                                                   $flat_rate_admin += \App\Product::find($cartproduct['id'])->shipping_cost ;
                                                   $local_rate += \App\Product::find($cartproduct['id'])->shipping_local_cost;
											   }
                                                    ?>
                                                    
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- <div class="row">
                                                <div class="col-6">
                                                    <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                        <input type="radio" name="shipping_type_admin" value="home_delivery" checked class="d-none" onchange="show_pickup_point(this)" data-target=".pickup_point_id_admin">
                                                        <span class="radio-box"></span>
                                                        <span class="d-block ml-2 strong-600">
                                                            {{ __('Home Delivery') }}
                                                        </span>
                                                    </label>
                                                </div>
                                                @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                                    <div class="col-6">
                                                        <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                            <input type="radio" name="shipping_type_admin" value="pickup_point" class="d-none" onchange="show_pickup_point(this)" data-target=".pickup_point_id_admin">
                                                            <span class="radio-box"></span>
                                                            <span class="d-block ml-2 strong-600">
                                                                {{ __('Local Pickup') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                            </div> -->

                                            <!-- @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                                <div class="mt-3 pickup_point_id_admin d-none">
                                                    <select class="pickup-select form-control-lg w-100" name="pickup_point_id_admin" data-placeholder="Select a pickup point">
                                                            <option>{{__('Select your nearest pickup point')}}</option>
                                                        @foreach (\App\PickupPoint::where('pick_up_status',1)->get() as $key => $pick_up_point)
                                                            <option value="{{ $pick_up_point->id }}" data-address="{{ $pick_up_point->address }}" data-phone="{{ $pick_up_point->phone }}">
                                                                {{ $pick_up_point->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if (!empty($seller_products))
                            <?php  
                                $flat_rate_seller=0;
                                $local_rate =0;
                            ?>
                                @foreach ($seller_products as $key => $seller_product)
                                    <div class="card mb-3">
                                        <div class="card-header bg-white py-3">
                                            <h5 class="heading-6 mb-0">{{ \App\Shop::where('user_id', $key)->first()->name }} Products</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row no-gutters">
                                                <div class="col-md-6">
                                                    <table class="table-cart">
                                                        <tbody>
                                                            @foreach ($seller_product as $id)
                                                            <tr class="cart-item">
                                                                <td class="product-image" width="25%">
                                                                    <a href="{{ route('product', \App\Product::find($id)->slug) }}" target="_blank">
                                                                        <img loading="lazy"  src="{{ asset(\App\Product::find($id)->thumbnail_img) }}">
                                                                    </a>
                                                                </td>
                                                                <td class="product-name strong-600">
                                                                    <a href="{{ route('product', \App\Product::find($id)->slug) }}" target="_blank" class="d-block c-base-2">
                                                                        {{ \App\Product::find($id)->name }}
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php 

                                                           $flat_rate_seller += \App\Product::find($id)->shipping_cost ;
                                                            $local_rate += \App\Product::find($id)->shipping_local_cost;
                                                                ?>
                                                                
                                                                @endforeach
                                                               
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- <div class="row">
                                                        <div class="col-6">
                                                            <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                                <input type="radio" name="shipping_type_{{ $key }}" value="home_delivery" checked class="d-none" onchange="show_pickup_point(this)" data-target=".pickup_point_id_{{ $key }}">
                                                                <span class="radio-box"></span>
                                                                <span class="d-block ml-2 strong-600">
                                                                    {{ __('Home Delivery') }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                        @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                                            @if (is_array(json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id)))
                                                                <div class="col-6">
                                                                    <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                                        <input type="radio" name="shipping_type_{{ $key }}" value="pickup_point" class="d-none" onchange="show_pickup_point(this)" data-target=".pickup_point_id_{{ $key }}">
                                                                        <span class="radio-box"></span>
                                                                        <span class="d-block ml-2 strong-600">
                                                                            {{ __('Local Pickup') }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div> -->

                                                    <!-- @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                                        @if (is_array(json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id)))
                                                            <div class="mt-3 pickup_point_id_{{ $key }} d-none">
                                                                <select class="pickup-select form-control-lg w-100" name="pickup_point_id_{{ $key }}" data-placeholder="Select a pickup point">
                                                                    <option>{{__('Select your nearest pickup point')}}</option>
                                                                    @foreach (json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id) as $pick_up_point)
                                                                        @if (\App\PickupPoint::find($pick_up_point) != null)
                                                                            <option value="{{ \App\PickupPoint::find($pick_up_point)->id }}" data-address="{{ \App\PickupPoint::find($pick_up_point)->address }}" data-phone="{{ \App\PickupPoint::find($pick_up_point)->phone }}">
                                                                                {{ \App\PickupPoint::find($pick_up_point)->name }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    @endif -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <?php
                             if (empty($seller_products))
                             {
                                $flat_rate_seller = 0;
                             }
                             if (empty($admin_products))
                             {
                                $flat_rate_admin = 0;
                             }
                             $total_flate_rate = $flat_rate_admin + $flat_rate_seller;
                            ?>
                            <div class ="flat_rate"  style="display:none;">{{$total_flate_rate}}</div>
                            <div class="row align-items-center pt-4">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                                <div class="col-6">
                                                    <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                        <input type="radio" name="shipping_type_admin" value="home_delivery" checked class="d-none" onchange="updateShipping(this.value)" data-target=".pickup_point_id_admin">
                                                        <span class="radio-box"></span>
                                                        <span class="d-block ml-2 strong-600">
                                                            {{ __('Home Delivery') }}
                                                        </span>
                                                    </label>
                                                </div>
                                                <?php $ispickup = 0; ?>
                                                @foreach (\App\PickupPoint::where('pick_up_status',1)->get() as $key => $pick_up_point)
                                                        <?php 
                                                        
                                                        if($pick_up_point->district == session('shipping_info')['district']){
                                                        $ispickup = 1;
                                                        
                                                          } ?>
                                                        @endforeach
                                                        
                                                @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                                <?php if($ispickup == 1){  ?>
                                                    <div class="col-6">
                                                        <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                            <input type="radio" name="shipping_type_admin" value="pickup_point" class="d-none" onchange="updateShipping(this.value)" data-target=".pickup_point_id_admin">
                                                            <span class="radio-box"></span>
                                                            <span class="d-block ml-2 strong-600">
                                                                {{ __('Local Pickup') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <?php } ?>
                                                @endif
                                            </div> 

                                            @if (\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1)
                                                <div class="mt-3 pickup_point_id_admin d-none">
                                                    <select class="pickup-select form-control-lg w-100" name="pickup_point_id_admin" data-placeholder="Select a pickup point">
                                                            <option>{{__('Select your nearest pickup point')}}</option>
                                                        @foreach (\App\PickupPoint::where('pick_up_status',1)->get() as $key => $pick_up_point)
                                                        <?php
                                                        if($pick_up_point->district == session('shipping_info')['district']){
                                                        ?>
                                                            <option value="{{ $pick_up_point->id }}" data-address="{{ $pick_up_point->address }}" data-phone="{{ $pick_up_point->phone }}">
                                                                {{ $pick_up_point->name }}
                                                            </option>
                                                            <?php } ?>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif 
                                </div>
                            </div>
                            <div class="row align-items-center pt-4">
                                <div class="col-md-6">
                                    <a href="{{ route('home') }}" class="link link--style-3">
                                        <i class="ion-android-arrow-back"></i>
                                        {{__('Return to shop')}}
                                    </a>
                                </div>
                                <input type="hidden" class="total_shipping" name="total_shipping" >
                                <input type="hidden" class="total_price" name="total_price" >
                                <input type="hidden" class="ship_type" name="ship_type" >
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-styled btn-base-1">{{__('Continue to Payment')}}</a>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="col-lg-4 ml-lg-auto cart_summary_rgt">
                        @include('frontend.partials.cart_summary')
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
		var cur = '<?= session('currencySymbol') ?>';
		var tax = parseFloat('<?= $tax ?>');
		var shipping = parseFloat('<?= Session::get('shipping_price'); ?>');
		var cod = parseFloat('<?= Session::get('codprice') ?>');
		var coupon_discount = parseFloat('<?php if(Session::get('coupon_discount')){ echo Session::get('coupon_discount'); }else{ echo 0; } ?>');
		var flat_rate  = parseFloat('<?= $total_flate_rate ?>');
		var subtotal = parseFloat('<?= $subtotal ?>');
		
       
        function updateShipping(shipping_type){
			 $.post( "{{ route('checkout.updateShipping') }}",{ shipping_type: shipping_type,_token: "{{ csrf_token() }}" }, function( data ) {
                   
                    $('.cart_summary_rgt').html(data);
                   
                    $('.error').html('');
                    
                        
                    });
			}
        $(document).ready(function(){
            if($("input[name='shipping_type_admin']").is(':checked')) {
                var value = $("input[name='shipping_type_admin']:checked").val();
                updateShipping(value);
               
                }

        });
       /*
        function show_pickup_point(el) {
        	var value = $(el).val();
        	updateShipping(value);
        	
        }
*/
        

    </script>
@endsection
