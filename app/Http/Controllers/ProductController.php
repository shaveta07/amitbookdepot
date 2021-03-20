<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Product;
use App\ProductStock;
use App\ProductBulks;
use App\Category;
use App\Author;
use App\Language;
use Auth;
use App\SubSubCategory;
use Session;
use ImageOptimizer;
use DB;
use CoreComponentRepository;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_products(Request $request)
    {
        //CoreComponentRepository::instantiateShopRepository();

        $type = 'In House';
        $col_name = null;
        $query = null;
        $sort_search = null;

        $products = Product::where('added_by', 'admin');

        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }
        if ($request->search != null){
            $products = $products
                        ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(15);

        return view('products.index', compact('products','type', 'col_name', 'query', 'sort_search'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seller_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::where('added_by', 'seller');
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null){
            $products = $products
                        ->where('name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
        if ($request->type != null){
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->orderBy('created_at', 'desc')->paginate(15);
        $type = 'Seller';

        return view('products.index', compact('products','type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }
    /**
     * show the seller list For Product
     */
    public function getProductSeller($id)
    {
        $products = [];
        // return $id;
        $product = Product::findOrFail($id);
        $product_name = $product->name;
        if(!empty($product_name) )
        {
            $get_products = \App\Product::where('name',$product_name)->get();
            foreach($get_products as $get_pro)
            {
                // get vendor info
                
                $added_by = $get_pro->added_by;
                $user_id = $get_pro->user_id;

                $item = [
                    "by"    =>  $get_pro->added_by,
                    "id"    =>  $get_pro->user_id
                ];

                array_push($products, $item);
            }
        }

        //return json_encode($products);
      
        return view('products.seller_view', compact('product', 'products'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function isbnvaldiate(Request $request)
    {
       
        $product_exists = \App\Product::where('isbn', $request->isbn)->first();
        if($product_exists)
        {
            $data = array('status' => 'true', 'isbn' =>$request->isbn);
            return json_encode($data);
        }
        else
        {
            $product_stk = \App\ProductStock::where('isbn', $request->isbn)->first();
            if($product_stk)
            {
                $data = array('status' => 'true', 'isbn' =>$request->isbn);
                return json_encode($data);
            }
            else
            {
               
                $data = array('status' => 'false',  'isbn' =>$request->isbn);
                return json_encode($data);
            }

        }
        
        
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_exists = \App\Product::where('name', $request->name)->where('user_id', Auth::id())->first();
        
        $isValid =  Validator::make($request->all(), [
            'isbn'    => 'required|unique:products'
          
        ]);

        if ($isValid->fails()) {
            return redirect('/admin/products/create')->withErrors($isValid)->withInput();
        }

        if(Auth::user()->user_type != 'admin')
        {
            $product_exists = \App\Product::where('name', $request->name)->where('user_id', Auth::id())->first();
            if($product_exists)
            {
                flash(__('Product Already Exist'))->error();
                return redirect('/seller/product/'.md5($product_exists->slug));
            }   
        }
        else
        {
            $product_exists = \App\Product::where('name', $request->name)->where('user_id', Auth::id())->first();
            if($product_exists)
            {
                flash(__('Product Already Exist'))->error();
              
                return redirect('/product/admin/'.md5($product_exists->slug).'/edit');
            }   

        }

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();

        $product = new Product;
        $product->name = $request->name;
        //$product->track_id = $request->track_id;
        
        if($request->origin == "")
        {
           
            $product->origin = 'india';
        }
        else
        {
            
            $product->origin = $request->origin;
        }
       
        //$product->origin = $request->origin;
        $product->added_by = $request->added_by;
        if(Auth::user()->user_type == 'seller'){
            $product->user_id = Auth::user()->id;
        }
        else{
            $product->user_id = \App\User::where('user_type', 'admin')->first()->id;
        }
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;
        

        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {
            if ($request->refundable != null) {
                $product->refundable = 1;
            }
            else {
                $product->refundable = 0;
            }
        }

        $photos = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
            $product->photos = json_encode($photos);
        }

        if($request->hasFile('thumbnail_img')){
            $product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        }

        if($request->hasFile('featured_img')){
            $product->featured_img = $request->featured_img->store('uploads/products/featured');
            //ImageOptimizer::optimize(base_path('public/').$product->featured_img);
        }

        if($request->hasFile('flash_deal_img')){
            $product->flash_deal_img = $request->flash_deal_img->store('uploads/products/flash_deal');
            //ImageOptimizer::optimize(base_path('public/').$product->flash_deal_img);
        }

        $product->unit = $request->unit;
        $product->weight_dimension = $request->weight_dimension;
       
        $bundleprice='';
        // $arr= array();
		// if($request->row_counter > 0){
		// 	for($i=1;$i<=$request->row_counter;$i++){
        //         $customercat = 'customercat'.$i;
        //         $variantcat = 'variantcat'.$i;
		// 		$varientqty = 'varientqty'.$i;
		// 		$varientprice = 'varientprice'.$i;
        //         $arr[$i]['customercat'] = $request->$customercat;
        //         $arr[$i]['variantcat'] = $request->$variantcat;
		// 		$arr[$i]['varientqty'] = $request->$varientqty;
		// 		$arr[$i]['varientprice'] = $request->$varientprice;
		// 		}
		// 	}
			//echo $request->customercat2;
			//echo $request->customercat3;
		//print_r($arr);die;
		// $product->bundleprice = json_encode($arr);
        $product->unit = $request->unit;
        $product->version = $request->version;
        $product->isbn = $request->isbn;
        $product->oldisbn = $request->oldisbn;
        
        if(($request->version == 'new' && $request->onrent == 'yes' && $request->oldisbn=="") || ($request->version == 'new' && $request->onrent == 'yes' && $request->oldisbn==$request->isbn)){
			
			flash(__('OLD ISBN Can not be empty.'))->error();
			/*
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            return redirect()->route('products.admin');
        }
        else{
            return redirect()->route('seller.products');
        }
        */
			}
        
        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->minstock = $request->minstock;
        $product->author_id = $request->author_id;
        $product->mrp = $request->mrp;
        $product->erpprice = $request->erpprice;
        $product->minorderqty = $request->minorderqty;
        $product->maxorderqty = $request->maxorderqty;
        $product->onrent = $request->onrent;
        if($request->securityamount == '')
        {
            $security=0;
        }
        else
        {
            $security=$request->securityamount;
        }
        $product->securityamount = $security;
        if($request->rentamount == '')
        {
            $rent=0;
        }
        else
        {
            $rent=$request->rentamount;
        }
        $product->rentamount = $rent;
        $product->tax = $request->tax;
        $product->sgst = $request->sgst;
        $product->cgst = $request->cgst;
        $product->igst = $request->igst;
        
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->shipping_type = $request->shipping_type;
        $product->shipping_local = $request->shipping_local;
        $product->pincode_range = $request->pincode_range;
       if($request->shipping_type == 'free'){
            $product->shipping_cost = 0;
        }else{
        if ($request->shipping_type == 'flat_rate') {
            $product->shipping_cost = $request->flat_shipping_cost;
        }
        
        
        if ($request->shipping_local == 'local_pickup') {
			
            $product->shipping_local_cost = $request->local_pickup_shipping_cost;
        }
	}
	
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        if($request->hasFile('meta_img')){
            $product->meta_img = $request->meta_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
        }

        if($request->hasFile('pdf')){
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();

        if($request->has('choice_no')){
            //print_r($request->choice_no); die();
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        }
        else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        //$variations = array();

        $product->save();

        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        
        //Generates the combinations of customer choice options
        $combinations = combinations($options);
        if(count($combinations[0]) > 0)
        {
            $product->variant_product = 1;
            
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                // $item = array();
                // $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                // $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                // $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                // $variations[$str] = $item;

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if($product_stock == null){
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }

                $product_stock->variant = $str;
             
                if($request->hasFile('variant_image_'.str_replace('.', '_', $str))){
                   
                    $product_stock->variant_image = $request['variant_image_'.str_replace('.', '_', $str)]->store('uploads/products/variant');
                   
                }
                // $isbnvart = $request['isbns_'.str_replace('.', '_', $str)];
                // $isValid =  Validator::make($request->all(), [
                //     $isbnvart   => 'required|unique:productstocks'
                  
                // ]);
        
                // if ($isValid->fails()) {
                //     return redirect('/admin/products/create')->withErrors($isValid)->withInput();
                // }

                //$product_stock->variant_image = $request->variant_image;
                $product_stock->price = $request['price_'.str_replace('.', '_', $str)];
                $product_stock->isbn = $request['isbns_'.str_replace('.', '_', $str)];
                $product_stock->mrp = $request['mrps_'.str_replace('.', '_', $str)];
                $product_stock->sku = $request['sku_'.str_replace('.', '_', $str)];
                $product_stock->qty = $request['qty_'.str_replace('.', '_', $str)];
                $product_stock->rent_amount = $request['rent_amount_'.str_replace('.', '_', $str)];
                $product_stock->rent_security = $request['rent_security_'.str_replace('.', '_', $str)];
                $product_stock->oldisbn = $request['oldisbns_'.str_replace('.', '_', $str)];
                $product_stock->save();
              
            }
        }
           // print_r($request->row_counter); die();
            if($request->row_counter > 0)
            {
                for($i=1;$i<=$request->row_counter;$i++)
                {

                    $variant_name = "variantcat".$i;
                    $variant_name_value = $request->$variant_name;
                    

                    $product_stock_info = ProductStock::where('variant', $variant_name_value)->where('product_id',$product->id )->first();
                    if($product_stock_info)
                    {
                        $stock_id = $product_stock_info->id;

                        $productBulks = new ProductBulks;
                        $customercat = 'customercat'.$i;
                        $varientqty = 'varientqty'.$i;
                        $varientprice = 'varientprice'.$i;
                        $erpprice = 'erpprice'.$i;
                        $productBulks->product_id = $product->id;
                        $productBulks->product_stock_id = $stock_id;
                        $productBulks->erpprice = $erpprice;
                        $productBulks->customertype = $request->$customercat;
                        $productBulks->overideprice = $request->$varientprice;
                        $productBulks->qtyrange = $request->$varientqty;
                        $productBulks->save();
                    }
                }
                    
            }
        //}
        else{
            $product->variant_product = 0;	
            $productBulks = new ProductBulks;
            $productBulks->product_id = $product->id;
            $productBulks->product_stock_id = '0';
            $productBulks->customertype = '0';
            $productBulks->overideprice = '0';
            $productBulks->qtyrange = '0';
            $productBulks->erpprice = '0';
            $productBulks->save();
            
        }
        //combinations end

        //$product->variations = json_encode($variations);

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     $data[$product->name] = $product->name;
        //     saveJSONFile($language->code, $data);
        // }

	    $product->save();

        flash(__('Product has been inserted successfully'))->success();
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            return redirect()->route('products.admin');
        }
        else{
            return redirect()->route('seller.products');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        $tags = json_decode($product->tags);
        $categories = Category::all();
        
        $options = ProductStock::where('product_id', $product->id)->get();

        $product->bulks = [];
        $product->bulks = ProductBulks::where('product_id', $product->id)->get();

        // return json_encode($options);

        // \App\Color::where('code', $item)->first()->name;

        return view('products.edit', compact('product', 'categories', 'tags', 'options'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        $tags = json_decode($product->tags);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        // $product_exists = \App\Product::where('name', $request->name)->where('user_id', Auth::id())->first();
        
        // $isValid =  Validator::make($request->all(), [
        //     'isbn'    => 'required|unique:products'
          
        // ]);

        // if ($isValid->fails()) {
        //     return redirect('/product/admin/'.md5($product_exists->slug).'/edit')->withErrors($isValid)->withInput();
        // }
         // return json_encode($request->input());
        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        //$product->track_id = $request->track_id;
        if($request->origin == "")
        {
           
            $product->origin = 'india';
        }
        else
        {
            
            $product->origin = $request->origin;
        }
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;

        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {
            if ($request->refundable != null) {
                $product->refundable = 1;
            }
            else {
                $product->refundable = 0;
            }
        }

        if($request->has('previous_photos')){
            $photos = $request->previous_photos;
        }
        else{
            $photos = array();
        }

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
        }
        $product->photos = json_encode($photos);

        $product->thumbnail_img = $request->previous_thumbnail_img;
        if($request->hasFile('thumbnail_img')){
            $product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        }

        $product->featured_img = $request->previous_featured_img;
        if($request->hasFile('featured_img')){
            $product->featured_img = $request->featured_img->store('uploads/products/featured');
            //ImageOptimizer::optimize(base_path('public/').$product->featured_img);
        }

        $product->flash_deal_img = $request->previous_flash_deal_img;
        if($request->hasFile('flash_deal_img')){
            $product->flash_deal_img = $request->flash_deal_img->store('uploads/products/flash_deal');
            //ImageOptimizer::optimize(base_path('public/').$product->flash_deal_img);
        }
        $bundleprice='';
        // $arr=array();
			//echo $request->customercat2;
			//echo $request->customercat3;
		//print_r($arr);die;
		$product->bundleprice = '';
        $product->unit = $request->unit;
        $product->weight_dimension = $request->weight_dimension;
        $product->version = $request->version;
       
        $product->isbn = $request->isbn;
        $product->oldisbn = $request->oldisbn;
        
          if(($request->version == 'new' && $request->onrent == 'yes' && $request->oldisbn=="") || ($request->version == 'new' && $request->onrent == 'yes' && $request->oldisbn==$request->isbn)){
			
			flash(__('OLD ISBN Can not be empty.'))->error();
			
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            return redirect()->route('products.admin');
        }
        else{
            return redirect()->route('seller.products');
        }
        
			}
        
        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->minstock = $request->minstock;
        $product->author_id = $request->author_id;
        $product->mrp = $request->mrp;
        $product->erpprice = $request->erpprice;
        $product->minorderqty = $request->minorderqty;
        $product->maxorderqty = $request->maxorderqty;
        $product->onrent = $request->onrent;
        $product->securityamount = $request->securityamount;
        $product->rentamount = $request->rentamount;
        $product->tax = $request->tax;
        $product->sgst = $request->sgst;
        $product->cgst = $request->cgst;
        $product->igst = $request->igst;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->shipping_type = $request->shipping_type;
        $product->shipping_local = $request->shipping_local;
        if($request->shipping_type == 'free'){
            $product->shipping_cost = 0;
        }
        else{
        if ($request->shipping_type == 'flat_rate') {
            $product->shipping_cost = $request->flat_shipping_cost;
        }
        if ($request->shipping_local == 'local_pickup') {
			
            $product->shipping_local_cost = $request->local_pickup_shipping_cost;
        }
	}
        $product->pincode_range = $request->pincode_range;
        $product->discount_type = $request->discount_type;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        $product->meta_img = $request->previous_meta_img;
        if($request->hasFile('meta_img')){
            $product->meta_img = $request->meta_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
        }

        if($request->hasFile('pdf')){
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.substr($product->slug, -5);

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($choice_options, $item);
            }
        }

        if($product->attributes != json_encode($request->choice_attributes)){
            foreach ($product->stocks as $key => $stock) {
                $stock->delete();
            }
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        }
        else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        // foreach (Language::all() as $key => $language) {
        //     $data = openJSONFile($language->code);
        //     unset($data[$product->name]);
        //     $data[$request->name] = "";
        //     saveJSONFile($language->code, $data);
        // }

        //$variations = array();

        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
    $combinations = combinations($options);
        
        if(count($combinations[0]) > 0){
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                // $item = array();
                // $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                // $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                // $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                // $variations[$str] = $item;

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if($product_stock == null){
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }
                $product_stock->variant_image = $request['previous_variant_image_'.str_replace('.', '_', $str)];
                if($request->hasFile('variant_image_'.str_replace('.', '_', $str))){
                   
                    $product_stock->variant_image = $request['variant_image_'.str_replace('.', '_', $str)]->store('uploads/products/variant');
                   
                }

                $product_stock->variant = $str;
                $product_stock->price = $request['price_'.str_replace('.', '_', $str)];
                $product_stock->isbn = $request['isbns_'.str_replace('.', '_', $str)];
                $product_stock->mrp = $request['mrps_'.str_replace('.', '_', $str)];
                $product_stock->sku = $request['sku_'.str_replace('.', '_', $str)];
                $product_stock->qty = $request['qty_'.str_replace('.', '_', $str)];
                $product_stock->rent_amount = $request['rent_amount_'.str_replace('.', '_', $str)];
                $product_stock->rent_security = $request['rent_security_'.str_replace('.', '_', $str)];
                $product_stock->oldisbn = $request['oldisbns_'.str_replace('.', '_', $str)];
                $product_stock->save();
            }

            // delete old
            $keep_ids = [];
            foreach ($combinations as $combination) {
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if($product_stock)
                {
                    array_push($keep_ids, $product_stock->id);
                }
            }
            
            DB::table('product_stocks')->where('product_id', $product->id)->whereNotIn('id', $keep_ids)->delete();

            // echo json_encode($keep_ids);
            // die();
        }
           if($request->row_counter > 0)
           {
                for($i=1;$i<=$request->row_counter;$i++)
                {

                    $variant_name = "variantcat".$i;
                    $variant_name_value = $request->$variant_name;

                    $product_stock_info = ProductStock::where('variant', $variant_name_value)->where('product_id',$product->id )->first();
                    if($product_stock_info)
                    {
                        $bulk_id_value = null;

                        $bulk_id = "bulk_id".$i;
                        $bulk_id_value = $request->$bulk_id;

                        if($bulk_id_value == null)
                        {
                            $stock_id = $product_stock_info->id;
                            $productBulks = new ProductBulks;
                            $customercat = 'customercat'.$i;
                            $erpprice = 'erpprice'.$i;
                            $varientqty = 'varientqty'.$i;
                            $varientprice = 'varientprice'.$i;
                            $productBulks->product_id = $product->id;
                            $productBulks->product_stock_id = $stock_id;
                            $productBulks->erpprice = $erpprice;
                            $productBulks->customertype = $request->$customercat;
                            $productBulks->overideprice = $request->$varientprice;
                            $productBulks->qtyrange = $request->$varientqty;
                            $productBulks->save();
                        }
                        else
                        {
                            $stock_id = $product_stock_info->id;
                            $productBulks = ProductBulks::where('id', $bulk_id_value)->first();
                            if($productBulks)
                            {
                                $customercat = 'customercat'.$i;
                                $varientqty = 'varientqty'.$i;
                                $varientprice = 'varientprice'.$i;
                                $erpprice = 'erpprice'.$i;
                                $productBulks->product_id = $product->id;
                                $productBulks->product_stock_id = $stock_id;
                                $productBulks->erpprice = $erpprice;
                                $productBulks->customertype = $request->$customercat;
                                $productBulks->overideprice = $request->$varientprice;
                                $productBulks->qtyrange = $request->$varientqty;
                                $productBulks->save();
                            }
                        }
                    }
                }
                    
            //    }
           
            
        }else{
            $product->variant_product = 0;	
            $productBulks = new ProductBulks;
            $productBulks->product_id = $product->id;
            $productBulks->product_stock_id = '0';
            $productBulks->customertype = '0';
            $productBulks->overideprice = '0';
            $productBulks->qtyrange = '0';
            $productBulks->erpprice = '0';
            $productBulks->save();
            
		}

        $product->save();

        flash(__('Product has been updated successfully'))->success();
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            return redirect()->route('products.admin');
        }
        else{
            return redirect()->route('seller.products');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if(Product::destroy($id)){
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$product->name]);
                saveJSONFile($language->code, $data);
            }
            flash(__('Product has been deleted successfully'))->success();
            if(Auth::user()->user_type == 'admin'){
                return redirect()->route('products.admin');
            }
            else{
                return redirect()->route('seller.products');
            }
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    public function deleteVariantImage($id)
    {
        $productStocks = ProductStock::findOrFail($id);
        if($productStocks!=null)
        {
            $image=$productStocks->variant_image;
            Storage::delete($image);
           $deleteimg = ProductStock::where('variant_image', $image)->update([
               'variant_image' => NULL
           ]);
            if($deleteimg == '1')
            {
                return "true";
            }

        }
        
    }
    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id)
    {
        $product = Product::find($id);
        $product_new = $product->replicate();
        $product_new->slug = substr($product_new->slug, 0, -5).str_random(5);

        if($product_new->save()){
            flash(__('Product has been duplicated successfully'))->success();
            if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
                return redirect()->route('products.admin');
            }
            else{
                return redirect()->route('seller.products');
            }
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    public function get_products_by_subsubcategory(Request $request)
    {
        $products = Product::where('subsubcategory_id', $request->subsubcategory_id)->get();
        return $products;
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function getProduct(Request $request){

        $search = $request->search;
  
        if($search == ''){
            $products = Product::orderby('name','asc')->select('id','name','category_id','subcategory_id','subsubcategory_id','brand_id','author_id')->limit(5)->get();
        }else{
            $products = Product::orderby('name','asc')->select('id','name','category_id','subcategory_id','subsubcategory_id','brand_id','author_id')->where('published','1')->where('name', 'like', '%' .$search . '%')->limit(5)->get();
        }
        
        $response = array();
        foreach($products as $product){

            $author_name = null;
            $get_author_name = \App\Author::where('id', $product->author_id)->first();
            if($get_author_name)
            {
                $author_name = $get_author_name->name;
            }

            $brand_name = null;
            $get_brand_name = \App\Brand::where('id', $product->brand_id)->first();
            if($get_brand_name)
            {
                $brand_name = $get_brand_name->name;
            }

            $category_name = null;
            $get_category_name = \App\Category::where('id', $product->category_id)->first();
            if($get_category_name)
            {
                $category_name = $get_category_name->name;
            }

            $sub_category_name = null;
            $get_sub_category_name = \App\SubCategory::where('id', $product->subcategory_id)->first();
            if($get_sub_category_name)
            {
                $sub_category_name = $get_sub_category_name->name;
            }

            $sub_sub_category_name = null;
            $get_sub_sub_category_name = \App\SubSubCategory::where('id', $product->subsubcategory_id)->first();
            if($get_sub_sub_category_name)
            {
                $sub_sub_category_name = $get_sub_sub_category_name->name;
            }

            
        
           $response[] = array(
               "id"             => $product->id,
               "value"          =>  $product->name,
               "category_id"    => $product->category_id,
               "category_name" =>  $category_name,
               "subcategory_id" => $product->subcategory->id,
               "sub_category_name" =>  $sub_category_name,
               "subsubcategory_id" => $product->subsubcategory_id,
               "sub_sub_category_name" =>  $sub_sub_category_name,
               "brand_id"   => $product->brand_id,
               "brand_name" =>  $brand_name,
               "author_id"  => $product->author_id,
               "author_name" =>  $author_name,
            );
        }

        return response()->json($response);
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;
       // $image = $request->image;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        //return json_encode($request->all());
        $product = Product::findOrFail($request->id);

        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        // $product_v = ProductStock::where('product_id',$product->id)->get();
        // $mrp = $request->mrps;
        // $isbn = $request->isbns;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

     $combinations = combinations($options);

        return view('partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

}
