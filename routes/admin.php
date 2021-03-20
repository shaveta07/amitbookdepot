<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin', 'HomeController@admin_dashboard')->name('admin.dashboard')->middleware(['auth', 'admin']);

Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
	Route::resource('categories','CategoryController');
	Route::get('/categories/destroy/{id}', 'CategoryController@destroy')->name('categories.destroy');
	Route::post('/categories/featured', 'CategoryController@updateFeatured')->name('categories.featured');

	Route::resource('subcategories','SubCategoryController');
	Route::get('/subcategories/destroy/{id}', 'SubCategoryController@destroy')->name('subcategories.destroy');

	Route::resource('subsubcategories','SubSubCategoryController');
	Route::get('/subsubcategories/destroy/{id}', 'SubSubCategoryController@destroy')->name('subsubcategories.destroy');

	Route::resource('brands','BrandController');
	Route::get('/brands/destroy/{id}', 'BrandController@destroy')->name('brands.destroy');

	Route::resource('couriers','CouriersController');
	Route::get('/couriers/destroy/{id}', 'CouriersController@destroy')->name('couriers.destroy');


	Route::get('/products/code','ProductController@code')->name('products.code');
	Route::get('/products/admin','ProductController@admin_products')->name('products.admin');
	Route::get('/products/seller','ProductController@seller_products')->name('products.seller');
	Route::get('/products/create','ProductController@create')->name('products.create');
	Route::get('/products/admin/{id}/edit','ProductController@admin_product_edit')->name('products.admin.edit');
	Route::get('/products/seller/{id}/edit','ProductController@seller_product_edit')->name('products.seller.edit');
	Route::post('/products/todays_deal', 'ProductController@updateTodaysDeal')->name('products.todays_deal');
	Route::post('/products/get_products_by_subsubcategory', 'ProductController@get_products_by_subsubcategory')->name('products.get_products_by_subsubcategory');
	Route::get('/products/deletevariantimage/{id}', 'ProductController@deleteVariantImage')->name('products.deletevariantimage');
	Route::post('/products/isbnvaldiate', 'ProductController@isbnvaldiate')->name('products.isbnvaldiate');

	Route::resource('sellers','SellerController');
	Route::get('/sellers/destroy/{id}', 'SellerController@destroy')->name('sellers.destroy');
	Route::get('/sellers/view/{id}/verification', 'SellerController@show_verification_request')->name('sellers.show_verification_request');
	Route::get('/sellers/approve/{id}', 'SellerController@approve_seller')->name('sellers.approve');
	Route::get('/sellers/reject/{id}', 'SellerController@reject_seller')->name('sellers.reject');
	Route::post('/sellers/payment_modal', 'SellerController@payment_modal')->name('sellers.payment_modal');
	Route::get('/seller/payments', 'PaymentController@payment_histories')->name('sellers.payment_histories');
	Route::get('/seller/payments/show/{id}', 'PaymentController@show')->name('sellers.payment_history');

	Route::resource('customers','CustomerController');
	Route::get('/customers/destroy/{id}', 'CustomerController@destroy')->name('customers.destroy');

	Route::get('/newsletter', 'NewsletterController@index')->name('newsletters.index');
	Route::post('/newsletter/send', 'NewsletterController@send')->name('newsletters.send');

	Route::resource('profile','ProfileController');

	Route::post('/business-settings/update', 'BusinessSettingsController@update')->name('business_settings.update');
	Route::post('/business-settings/update/activation', 'BusinessSettingsController@updateActivationSettings')->name('business_settings.update.activation');
	Route::get('/activation', 'BusinessSettingsController@activation')->name('activation.index');
	Route::get('/payment-method', 'BusinessSettingsController@payment_method')->name('payment_method.index');
	Route::get('/social-login', 'BusinessSettingsController@social_login')->name('social_login.index');
	Route::get('/smtp-settings', 'BusinessSettingsController@smtp_settings')->name('smtp_settings.index');
	Route::get('/google-analytics', 'BusinessSettingsController@google_analytics')->name('google_analytics.index');
	Route::get('/facebook-chat', 'BusinessSettingsController@facebook_chat')->name('facebook_chat.index');
	Route::post('/env_key_update', 'BusinessSettingsController@env_key_update')->name('env_key_update.update');
	Route::post('/payment_method_update', 'BusinessSettingsController@payment_method_update')->name('payment_method.update');
	Route::post('/google_analytics', 'BusinessSettingsController@google_analytics_update')->name('google_analytics.update');
	Route::post('/facebook_chat', 'BusinessSettingsController@facebook_chat_update')->name('facebook_chat.update');
	Route::post('/facebook_pixel', 'BusinessSettingsController@facebook_pixel_update')->name('facebook_pixel.update');
	Route::get('/currency', 'CurrencyController@currency')->name('currency.index');
    Route::post('/currency/update', 'CurrencyController@updateCurrency')->name('currency.update');
    Route::post('/your-currency/update', 'CurrencyController@updateYourCurrency')->name('your_currency.update');
	Route::get('/currency/create', 'CurrencyController@create')->name('currency.create');
	Route::post('/currency/store', 'CurrencyController@store')->name('currency.store');
	Route::post('/currency/currency_edit', 'CurrencyController@edit')->name('currency.edit');
	Route::post('/currency/update_status', 'CurrencyController@update_status')->name('currency.update_status');
	Route::get('/verification/form', 'BusinessSettingsController@seller_verification_form')->name('seller_verification_form.index');
	Route::post('/verification/form', 'BusinessSettingsController@seller_verification_form_update')->name('seller_verification_form.update');
	Route::get('/vendor_commission', 'BusinessSettingsController@vendor_commission')->name('business_settings.vendor_commission');
	Route::post('/vendor_commission_update', 'BusinessSettingsController@vendor_commission_update')->name('business_settings.vendor_commission.update');

	Route::resource('/languages', 'LanguageController');
	Route::post('/languages/update_rtl_status', 'LanguageController@update_rtl_status')->name('languages.update_rtl_status');
	Route::get('/languages/destroy/{id}', 'LanguageController@destroy')->name('languages.destroy');
	Route::get('/languages/{id}/edit', 'LanguageController@edit')->name('languages.edit');
	Route::post('/languages/{id}/update', 'LanguageController@update')->name('languages.update');
	Route::post('/languages/key_value_store', 'LanguageController@key_value_store')->name('languages.key_value_store');

	Route::get('/frontend_settings/home', 'HomeController@home_settings')->name('home_settings.index');
	Route::post('/frontend_settings/home/top_10', 'HomeController@top_10_settings')->name('top_10_settings.store');
	Route::get('/sellerpolicy/{type}', 'PolicyController@index')->name('sellerpolicy.index');
	Route::get('/returnpolicy/{type}', 'PolicyController@index')->name('returnpolicy.index');
	Route::get('/supportpolicy/{type}', 'PolicyController@index')->name('supportpolicy.index');
	Route::get('/terms/{type}', 'PolicyController@index')->name('terms.index');
	Route::get('/privacypolicy/{type}', 'PolicyController@index')->name('privacypolicy.index');

	//Policy Controller
	Route::post('/policies/store', 'PolicyController@store')->name('policies.store');

	Route::group(['prefix' => 'frontend_settings'], function(){
		Route::resource('sliders','SliderController');
	    Route::get('/sliders/destroy/{id}', 'SliderController@destroy')->name('sliders.destroy');

		Route::resource('home_banners','BannerController');
		Route::get('/home_banners/create/{position}', 'BannerController@create')->name('home_banners.create');
		Route::post('/home_banners/update_status', 'BannerController@update_status')->name('home_banners.update_status');
	    Route::get('/home_banners/destroy/{id}', 'BannerController@destroy')->name('home_banners.destroy');

		Route::resource('home_categories','HomeCategoryController');
	    Route::get('/home_categories/destroy/{id}', 'HomeCategoryController@destroy')->name('home_categories.destroy');
		Route::post('/home_categories/update_status', 'HomeCategoryController@update_status')->name('home_categories.update_status');
		Route::post('/home_categories/get_subsubcategories_by_category', 'HomeCategoryController@getSubSubCategories')->name('home_categories.get_subsubcategories_by_category');
	});

	Route::resource('roles','RoleController');
    Route::get('/roles/destroy/{id}', 'RoleController@destroy')->name('roles.destroy');

    Route::resource('staffs','StaffController');
    Route::get('/staffs/destroy/{id}', 'StaffController@destroy')->name('staffs.destroy');

	Route::resource('flash_deals','FlashDealController');
    Route::get('/flash_deals/destroy/{id}', 'FlashDealController@destroy')->name('flash_deals.destroy');
	Route::post('/flash_deals/update_status', 'FlashDealController@update_status')->name('flash_deals.update_status');
	Route::post('/flash_deals/update_featured', 'FlashDealController@update_featured')->name('flash_deals.update_featured');
	Route::post('/flash_deals/product_discount', 'FlashDealController@product_discount')->name('flash_deals.product_discount');
	Route::post('/flash_deals/product_discount_edit', 'FlashDealController@product_discount_edit')->name('flash_deals.product_discount_edit');

	Route::get('/orders', 'OrderController@admin_orders')->name('orders.index.admin');
	Route::get('/orders/{id}/show', 'OrderController@show')->name('orders.show');
	Route::post('/orders/update_tracking', 'OrderController@updateTracking')->name('orders.updateTracking');
	Route::post('/orders/gettrackdata', 'OrderController@gettrackdata')->name('orders.gettrackdata');
	Route::get('/sales/{id}/show', 'OrderController@sales_show')->name('sales.show');
	Route::get('/orders/destroy/{id}', 'OrderController@destroy')->name('orders.destroy');
	Route::get('/sales', 'OrderController@sales')->name('sales.index');

	Route::resource('links','LinkController');
	Route::get('/links/destroy/{id}', 'LinkController@destroy')->name('links.destroy');

	Route::resource('generalsettings','GeneralSettingController');
	Route::get('/logo','GeneralSettingController@logo')->name('generalsettings.logo');
	Route::post('/logo','GeneralSettingController@storeLogo')->name('generalsettings.logo.store');
	Route::get('/color','GeneralSettingController@color')->name('generalsettings.color');
	Route::post('/color','GeneralSettingController@storeColor')->name('generalsettings.color.store');

	Route::resource('seosetting','SEOController');

	Route::post('/pay_to_seller', 'CommissionController@pay_to_seller')->name('commissions.pay_to_seller');

	//Reports
	Route::get('/stock_report', 'ReportController@stock_report')->name('stock_report.index');
	Route::get('/in_house_sale_report', 'ReportController@in_house_sale_report')->name('in_house_sale_report.index');
	Route::get('/seller_report', 'ReportController@seller_report')->name('seller_report.index');
	Route::get('/seller_sale_report', 'ReportController@seller_sale_report')->name('seller_sale_report.index');
	Route::get('/wish_report', 'ReportController@wish_report')->name('wish_report.index');
	Route::get('/reports/open_ap_invoice_report', 'ReportController@open_ap_invoice_report')->name('report.open_ap_invoice_report');
	Route::get('/reports/open_ap_invoice_report_search', 'ReportController@open_ap_invoice_report_search')->name('report.open_ap_invoice_report_search');
	Route::get('/reports/open_ar_invoice_report', 'ReportController@open_ar_invoice_report')->name('report.open_ar_invoice_report');
	Route::get('/reports/open_ar_invoice_report_search', 'ReportController@open_ar_invoice_report_search')->name('report.open_ar_invoice_report_search');
	Route::get('/reports/supplier_purchase_report', 'ReportController@supplier_purchase_report')->name('report.supplier_purchase_report');
	Route::get('/reports/supplier_purchase_report_search', 'ReportController@supplier_purchase_report_search')->name('report.supplier_purchase_report_search');

	Route::get('/reports/AR_book_report', 'ReportController@AR_book_report')->name('report.AR_book_report');
	Route::post('/reports/AR_book_report_submit', 'ReportController@AR_book_report_submit')->name('report.AR_book_report_submit');

	Route::get('/reports/AP_book_report', 'ReportController@AP_book_report')->name('report.AP_book_report');
	Route::post('/reports/AP_book_report_submit', 'ReportController@AP_book_report_submit')->name('report.AP_book_report_submit');

	Route::get('/reports/supplier_ledger_report', 'ReportController@supplier_ledger_report')->name('report.supplier_ledger_report');
	Route::post('/reports/supplier_statement_pdf', 'ReportController@supplier_statement_pdf')->name('report.supplier_statement_pdf');
	Route::post('/reports/creditinvoice_pdf', 'ReportController@creditinvoice_pdf')->name('report.creditinvoice_pdf');
	Route::get('/reports/AR_ageing_report', 'ReportController@AR_ageing_report')->name('report.AR_ageing_report');
	Route::get('/reports/emp_adv_report', 'ReportController@emp_adv_report')->name('report.emp_adv_report');
	Route::post('/reports/emp_adv_report_search', 'ReportController@emp_adv_report_search')->name('report.emp_adv_report_search');
	Route::get('/reports/creditinvoicereport', 'ReportController@creditinvoicereport')->name('report.creditinvoicereport');
	Route::get('/reports/gstreport', 'ReportController@gstreport')->name('report.gstreport');
	Route::get('/reports/gstreportsearch', 'ReportController@gstreportsearch')->name('report.gstreportsearch');
	Route::get('/reports/gstreportbysuppliers', 'ReportController@gstreportbysuppliers')->name('report.gstreportbysuppliers');
	Route::get('/reports/gstreportbysupplierssearch', 'ReportController@gstreportbysupplierssearch')->name('report.gstreportbysupplierssearch');
	Route::get('/reports/gstreportbycheque', 'ReportController@gstreportbycheque')->name('report.gstreportbycheque');
	Route::get('/reports/gstreportbychequesearch', 'ReportController@gstreportbychequesearch')->name('report.gstreportbychequesearch');


	//Coupons
	Route::resource('coupon','CouponController');
	Route::post('/coupon/get_form', 'CouponController@get_coupon_form')->name('coupon.get_coupon_form');
	Route::post('/coupon/get_form_edit', 'CouponController@get_coupon_form_edit')->name('coupon.get_coupon_form_edit');
	Route::get('/coupon/destroy/{id}', 'CouponController@destroy')->name('coupon.destroy');

	//Reviews
	Route::get('/reviews', 'ReviewController@index')->name('reviews.index');
	Route::post('/reviews/published', 'ReviewController@updatePublished')->name('reviews.published');

	//Support_Ticket
	Route::get('support_ticket/','SupportTicketController@admin_index')->name('support_ticket.admin_index');
	Route::get('support_ticket/{id}/show','SupportTicketController@admin_show')->name('support_ticket.admin_show');
	Route::post('support_ticket/reply','SupportTicketController@admin_store')->name('support_ticket.admin_store');

	//Pickup_Points
	Route::resource('pick_up_points','PickupPointController');
	Route::get('/pick_up_points/destroy/{id}', 'PickupPointController@destroy')->name('pick_up_points.destroy');


	Route::get('orders_by_pickup_point','OrderController@order_index')->name('pick_up_point.order_index');
	Route::get('/orders_by_pickup_point/{id}/show', 'OrderController@pickup_point_order_sales_show')->name('pick_up_point.order_show');

	Route::get('invoice/admin/order_id{order_id}', 'InvoiceController@admin_invoice_download')->name('admin.invoice.download');

	//conversation of seller customer
	Route::get('conversations','ConversationController@admin_index')->name('conversations.admin_index');
	Route::get('conversations/{id}/show','ConversationController@admin_show')->name('conversations.admin_show');
	Route::get('/conversations/destroy/{id}', 'ConversationController@destroy')->name('conversations.destroy');


    Route::post('/sellers/profile_modal', 'SellerController@profile_modal')->name('sellers.profile_modal');
    Route::post('/sellers/approved', 'SellerController@updateApproved')->name('sellers.approved');

	Route::resource('attributes','AttributeController');
	Route::get('/attributes/destroy/{id}', 'AttributeController@destroy')->name('attributes.destroy');

	Route::resource('addons','AddonController');
	Route::post('/addons/activation', 'AddonController@activation')->name('addons.activation');

	Route::get('/customer-bulk-upload/index', 'CustomerBulkUploadController@index')->name('customer_bulk_upload.index');
	Route::post('/bulk-user-upload', 'CustomerBulkUploadController@user_bulk_upload')->name('bulk_user_upload');
	Route::post('/bulk-customer-upload', 'CustomerBulkUploadController@customer_bulk_file')->name('bulk_customer_upload');
	Route::get('/user', 'CustomerBulkUploadController@pdf_download_user')->name('pdf.download_user');
	//Customer Package
	Route::resource('customer_packages','CustomerPackageController');
	Route::get('/customer_packages/destroy/{id}', 'CustomerPackageController@destroy')->name('customer_packages.destroy');
	//Classified Products
	Route::get('/classified_products', 'CustomerProductController@customer_product_index')->name('classified_products');
	Route::post('/classified_products/published', 'CustomerProductController@updatePublished')->name('classified_products.published');

	//Shipping Configuration
	Route::get('/shipping_configuration', 'BusinessSettingsController@shipping_configuration')->name('shipping_configuration.index');
	Route::post('/shipping_configuration/update', 'BusinessSettingsController@shipping_configuration_update')->name('shipping_configuration.update');

	Route::resource('pages', 'PageController');
	Route::get('/pages/destroy/{id}', 'PageController@destroy')->name('pages.destroy');

	Route::resource('countries','CountryController');
	Route::post('/countries/status', 'CountryController@updateStatus')->name('countries.status');
	
	Route::resource('shippings','ShippingController');
	Route::post('/shippings/{id}/update', 'ShippingController@update')->name('shippings.update');
	Route::resource('customercategories','CustomerCategoryController');
	Route::post('/customercategories/{id}/update', 'CustomerCategoryController@update')->name('customercategories.update');
	Route::get('/customercategories/destroy/{id}', 'CustomerCategoryController@destroy')->name('customercategories.destroy');
	
	Route::resource('institutes','InstituteController');
	Route::post('/institutes/{id}/update', 'InstituteController@update')->name('institutes.update');
	Route::get('/institutes/destroy/{id}', 'InstituteController@destroy')->name('institutes.destroy');
	
	Route::resource('authors','AuthorController');
	Route::post('/authors/{id}/update', 'AuthorController@update')->name('authors.update');
	Route::get('/authors/destroy/{id}', 'AuthorController@destroy')->name('authors.destroy');

	Route::get('/attendance/punch_attendance', 'HrAttendanceController@PunchAttendance')->name('attendance.PunchAttendance');
	Route::post('/attendance/updateStatus', 'HrAttendanceController@updateStatus')->name('attendance.updateStatus');
	Route::get('/attendance/empsalary', 'HrAttendanceController@EmpSalary')->name('attendance.EmpSalary');
	Route::get('/attendance/applyleaveview', 'HrAttendanceController@ApplyLeaveView')->name('attendance.ApplyLeaveView');
	Route::post('/attendance/applyleaveview', 'HrAttendanceController@ApplyLeave')->name('attendance.ApplyLeave');
	Route::post('/attendance/applyleaveview', 'HrAttendanceController@ApplyLeaveSubmit')->name('attendance.ApplyLeaveSubmit');
	Route::get('/attendance/editleave/{id}', 'HrAttendanceController@EditLeave')->name('attendance.EditLeave');
	Route::post('/attendance/editleavesubmit', 'HrAttendanceController@EditLeaveSubmit')->name('attendance.EditLeaveSubmit');
	Route::get('/attendance/delete/{id}', 'HrAttendanceController@delete')->name('attendance.delete');
	Route::get('/attendance/takeactionleave/{id}', 'HrAttendanceController@takeactionleave')->name('attendance.takeactionleave');
	Route::post('/attendance/takeactionleaveSubmit', 'HrAttendanceController@takeactionleaveSubmit')->name('attendance.takeactionleaveSubmit');
	Route::post('/attendance/repeatcategory', 'HrAttendanceController@repeatCategory')->name('attendance.repeatCategory');
	

	Route::get('/attendance/lunchreportSubmit', 'HrAttendanceController@lunchreportSubmit')->name('attendance.lunchreportSubmit');
	Route::post('/attendance/getEmp', 'HrAttendanceController@getEmp')->name('attendance.getEmp');
	Route::post('/attendance/salInv', 'HrAttendanceController@salInv')->name('attendance.salInv');
	Route::get('/attendance/actionleave', 'HrAttendanceController@ActionLeave')->name('attendance.ActionLeave');
	Route::get('/attendance/searchactionleave', 'HrAttendanceController@SearchActionLeave')->name('attendance.SearchActionLeave');
	Route::get('/attendance/lunchreport', 'HrAttendanceController@LunchReport')->name('attendance.LunchReport');
	Route::get('/attendance/leavereport', 'HrAttendanceController@LeaveReport')->name('attendance.LeaveReport');
	Route::get('/attendance/calling', 'HrAttendanceController@Calling')->name('attendance.Calling');
	Route::get('/attendance/callerlist', 'HrAttendanceController@CallerList')->name('attendance.CallerList');
	Route::get('/attendance/addcaller', 'HrAttendanceController@AddCaller')->name('attendance.AddCaller');
	Route::post('/attendance/addcallersave', 'HrAttendanceController@AddCallerSave')->name('attendance.AddCallerSave');
	Route::get('/attendance/leavedate/{id}', 'HrAttendanceController@LeaveDate')->name('attendance.LeaveDate');
	Route::get('/attendance/leavedate', 'HrAttendanceController@Search')->name('attendance.Search');
	Route::get('/attendance/editcaller/{id}', 'HrAttendanceController@editCaller')->name('attendance.edit_caller');
	Route::post('/attendance/UpdateCallerSave', 'HrAttendanceController@UpdateCallerSave')->name('attendance.UpdateCallerSave');
	Route::post('/attendance/get_caller', 'HrAttendanceController@getCaller')->name('attendance.getCaller');
	Route::post('/attendance/callersave', 'HrAttendanceController@callersave')->name('attendance.callersave');
	Route::get('/attendance/callingsearch', 'HrAttendanceController@callingsearch')->name('attendance.callingsearch');


	Route::get('/attendance/callingcomment/{id}', 'HrAttendanceController@callingcomment')->name('attendance.callingcomment');
	Route::post('/attendance/callercommentsubmit', 'HrAttendanceController@callercommentsubmit')->name('attendance.callercommentsubmit');

	Route::get('/attendance/callingsms/{id}/{mobile}/{userid}', 'HrAttendanceController@callingsms')->name('attendance.callingsms');
	Route::post('/attendance/callersmssubmit', 'HrAttendanceController@callersmssubmit')->name('attendance.callersmssubmit');

	Route::get('/attendance/download_attendance/{emp}/{month}/{year}', 'HrAttendanceController@download_attendance')->name('attendance.download_attendance');
	Route::get('/attendance/generate', 'HrAttendanceController@generatePDF')->name('attendance.generate');


	Route::get('/wallet/create', 'WalletController@walletView')->name('wallet.view');
	Route::get('/wallet/admin/{id}/update', 'WalletController@showEditWalletRecharge')->name('show.wallet.edit');
	Route::post('/wallet/admin/{id}/edit','WalletController@getEditWalletRecharge')->name('get.wallet.edit');
	Route::get('/wallet', 'WalletController@walletIndexAdmin')->name('wallet.admin.index');
	Route::post('/wallet/recharge', 'WalletController@walletRecharge')->name('wallet.recharge.admin');
	Route::post('/wallet/updateApproved', 'WalletController@updateApproved')->name('wallet.approved');
	Route::post('/wallet/destory', 'WalletController@destory')->name('wallet.destory');


	Route::post('/ARinvoice_header_workbench/advancepay', 'OrderController@advancepay')->name('order.advancepay');
	Route::get('/ARinvoice_header_workbench/create', 'OrderController@ArInvoiceCreate')->name('order.ARinvoiceCreate');
	Route::get('/ARinvoice_header_workbench/search', 'OrderController@ArInvoiceSearch')->name('order.ARinvoiceSearch');
	Route::get('/ARinvoice_header_workbench/search_data', 'OrderController@ArInvoiceSearchData')->name('order.ARinvoiceSearchData');
	Route::get('/ARinvoice_header_workbench/cancel', 'OrderController@ArInvoiceCancel')->name('order.ARinvoiceCancel');
	Route::get('/ARinvoice_header_workbench/cancel_data', 'OrderController@ArInvoiceCancelData')->name('order.ARinvoiceCancelData');
	Route::post('/ARinvoice_header_workbench/arstore','OrderController@ArInvoiceStore')->name('order.ARinvoiceStore');
	Route::get('/ARinvoice_header_workbench/CustomerForPrebooking','OrderController@CustomerForPrebooking')->name('order.CustomerForPrebooking');
	Route::post('/ARinvoice_header_workbench/verify_phone_prebooking','OrderController@verify_phone_prebooking')->name('order.verifyPhonePrebooking');
	Route::get('/ARinvoice_header_workbench/view/{order_id}/{invoice_number}', 'OrderController@ArInvoiceView')->name('order.ARinvoiceView');
	Route::post('/ARinvoice_header_workbench/getbookdetail', 'OrderController@getBookDetail')->name('order.getbookdetail');
	Route::post('/ARinvoice_header_workbench/get_book_detail_invoice', 'OrderController@getBookDetailInvoice')->name('order.get_book_detail_invoice');
	Route::post('/ARinvoice_header_workbench/getDescription', 'OrderController@getDescription')->name('order.getDescription');
	Route::post('/ARinvoice_header_workbench/resend_code', 'OrderController@resend_code')->name('order.resend_code');
	Route::post('/ARinvoice_header_workbench/getInvoicePrint', 'OrderController@getInvoicePrint')->name('order.getInvoicePrint');
	Route::post('/ARinvoice_header_workbench/ProductSave', 'OrderController@ProductSave')->name('order.ProductSave');
	Route::post('/ARinvoice_header_workbench/applycoupon', 'OrderController@applycoupon')->name('order.applycoupon');
	Route::post('/ARinvoice_header_workbench/InvoiceEmail', 'OrderController@getInvoiceEmail')->name('order.InvoiceEmail');
	Route::post('/ARinvoice_header_workbench/updateline', 'OrderController@updateline')->name('order.updateline');
	Route::post('/ARinvoice_header_workbench/destroyLine', 'OrderController@destroyLine')->name('order.destroyLine');
	Route::post('/ARinvoice_header_workbench/statusChangeOrder', 'OrderController@statusChangeOrder')->name('order.statusChangeOrder');
	Route::post('/ARinvoice_header_workbench/deletecoupon/{id}', 'OrderController@deletecoupon')->name('order.deletecoupon');
	Route::get('/ARinvoice_header_workbench/ARInvoice/{order_id}', 'OrderController@ARInvoice')->name('order.ARInvoice');
	Route::get('/ARinvoice_header_workbench/statuschangeinvoice/{order_id}', 'OrderController@statuschangeinvoice')->name('order.statuschangeinvoice');
	Route::get('/ARinvoice_header_workbench/returnrent/{invoice_num}', 'OrderController@returnrent')->name('order.returnrent');
	Route::post('/ARinvoice_header_workbench/saverent', 'OrderController@saverent')->name('order.saverent');
	Route::post('/ARinvoice_header_workbench/resend_code', 'OrderController@resend_code')->name('order.resend_code');
	Route::post('/ARinvoice_header_workbench/InvoiceEmailPre', 'OrderController@getInvoiceEmailPre')->name('order.InvoiceEmailPre');


	Route::get('/ARinvoice_header_workbench_f/create', 'ArInvoicesAllFController@Create')->name('ArInvoicesAllF.Create');
	Route::get('/ARinvoice_header_workbench_f/search', 'ArInvoicesAllFController@Search')->name('ArInvoicesAllF.Search');
	Route::get('/ARinvoice_header_workbench_f/search_data', 'ArInvoicesAllFController@SearchData')->name('ArInvoicesAllF.SearchData');
	Route::post('/ARinvoice_header_workbench_f/store','ArInvoicesAllFController@Store')->name('ArInvoicesAllF.Store');
	Route::get('/ARinvoice_header_workbench_f/CustomerForPrebooking','ArInvoicesAllFController@CustomerForPrebooking')->name('ArInvoicesAllF.CustomerForPrebooking');
	Route::post('/ARinvoice_header_workbench_f/verify_phone_prebooking','ArInvoicesAllFController@verify_phone_prebooking')->name('ArInvoicesAllF.verifyPhonePrebooking');
	Route::get('/ARinvoice_header_workbench_f/view/{invoiceid}/{invoice_number}', 'ArInvoicesAllFController@View')->name('ArInvoicesAllF.View');
	Route::get('/ARinvoice_header_workbench_f/arformreport', 'ArInvoicesAllFController@arformreportCreate')->name('ArInvoicesAllF.arformreportCreate');
	Route::get('/ARinvoice_header_workbench_f/linearformreport', 'ArInvoicesAllFController@LinearformreportCreate')->name('ArInvoicesAllF.LinearformreportCreate');
	Route::post('/ARinvoice_header_workbench_f/arformreport', 'ArInvoicesAllFController@arformreportSubmit')->name('ArInvoicesAllF.arformreportSubmit');
	Route::post('/ARinvoice_header_workbench_f/linearformreport', 'ArInvoicesAllFController@LinearformreportSubmit')->name('ArInvoicesAllF.LinearformreportSubmit');
	Route::post('/ARinvoice_header_workbench_f/resend_code', 'ArInvoicesAllFController@resend_code')->name('ArInvoicesAllF.resend_code');
	Route::post('/ARinvoice_header_workbench_f/getbookdetail', 'ArInvoicesAllFController@getBookDetail')->name('ArInvoicesAllF.getbookdetail');
	Route::post('/ARinvoice_header_workbench_f/get_book_detail_invoice', 'ArInvoicesAllFController@getBookDetailInvoice')->name('ArInvoicesAllF.get_book_detail_invoice');
	Route::post('/ARinvoice_header_workbench_f/ProductSave', 'ArInvoicesAllFController@ProductSave')->name('ArInvoicesAllF.ProductSave');
	Route::post('/ARinvoice_header_workbench_f/statusChangeOrder', 'ArInvoicesAllFController@statusChangeOrder')->name('ArInvoicesAllF.statusChangeOrder');
	Route::post('/ARinvoice_header_workbench_f/getDescription', 'ArInvoicesAllFController@getDescription')->name('ArInvoicesAllF.getDescription');
	Route::post('/ARinvoice_header_workbench_f/InvoiceEmail', 'ArInvoicesAllFController@getInvoiceEmail')->name('ArInvoicesAllF.InvoiceEmail');
	Route::get('/ARinvoice_header_workbench_f/ARInvoice/{order_id}', 'ArInvoicesAllFController@ARInvoice')->name('ArInvoicesAllF.ARInvoice');
	Route::post('/ARinvoice_header_workbench_f/change_status_formline', 'ArInvoicesAllFController@changeStatusFormline')->name('ArInvoicesAllF.changeStatusFormline');
	Route::post('/ARinvoice_header_workbench_f/save_status_formline', 'ArInvoicesAllFController@saveStatusFormline')->name('ArInvoicesAllF.saveStatusFormline');
	Route::post('/ARinvoice_header_workbench_f/get_status_formline', 'ArInvoicesAllFController@getStatusFormline')->name('ArInvoicesAllF.getStatusFormline');
	Route::post('/ARinvoice_header_workbench_f/save_recieved_amount', 'ArInvoicesAllFController@saveRecievedAmount')->name('ArInvoicesAllF.saveRecievedAmount');
	Route::post('/ARinvoice_header_workbench_f/destroyLine', 'ArInvoicesAllFController@destroyLine')->name('ArInvoicesAllF.destroyLine');
	Route::post('/ARinvoice_header_workbench_f/getInvoicePrint', 'ArInvoicesAllFController@getInvoicePrint')->name('ArInvoicesAllF.getInvoicePrint');
	Route::post('/ARinvoice_header_workbench_f/sendsms', 'ArInvoicesAllFController@sendsms')->name('ArInvoicesAllF.sendsms');



	Route::get('/APinvoice_header_workbench/create', 'APInvoiceAllsController@Create')->name('APInvoiceAlls.Create');
	Route::get('/APinvoice_header_workbench/varifyme/{invoice_number}/{supplier_id}', 'APInvoiceAllsController@VarifyMe')->name('APInvoiceAlls.VarifyMe');
	Route::get('/APinvoice_header_workbench/create-old', 'APInvoiceAllsController@CreateOld')->name('APInvoiceAlls.CreateOld');
	Route::get('/APinvoice_header_workbench/create-old-2', 'APInvoiceAllsController@CreateOld2')->name('APInvoiceAlls.CreateOld2');
	Route::get('/APinvoice_header_workbench/search', 'APInvoiceAllsController@Search')->name('APInvoiceAlls.Search');
	Route::get('/APinvoice_header_workbench/search_data', 'APInvoiceAllsController@APinvoiceSearchData')->name('APInvoiceAlls.APinvoiceSearchData');
	Route::post('/APinvoice_header_workbench/OldInvoiceStore','APInvoiceAllsController@OldInvoiceStore')->name('APInvoiceAlls.OldInvoiceStore');
	Route::post('/APinvoice_header_workbench/store','APInvoiceAllsController@store')->name('APInvoiceAlls.Store');
	Route::get('/APinvoice_header_workbench/view/{invoice_number}/{supplier_id}', 'APInvoiceAllsController@APInvoiceView')->name('APInvoiceAlls.APinvoiceView');
	Route::post('/APinvoice_header_workbench/getbookdetail', 'APInvoiceAllsController@getBookDetail')->name('APInvoiceAlls.getbookdetail');
	Route::post('/APinvoice_header_workbench/get_book_detail_invoice', 'APInvoiceAllsController@getBookDetailInvoice')->name('APInvoiceAlls.get_book_detail_invoice');
	Route::post('/APinvoice_header_workbench/get_book_detail_invoice_old', 'APInvoiceAllsController@getBookDetailInvoiceOld')->name('APInvoiceAlls.get_book_detail_invoice_old');
	Route::post('/APinvoice_header_workbench/ProductSave', 'APInvoiceAllsController@ProductSave')->name('APInvoiceAlls.ProductSave');
	Route::post('/APinvoice_header_workbench/destroyLine', 'APInvoiceAllsController@destroyLine')->name('APInvoiceAlls.destroyLine');
	Route::post('/APinvoice_header_workbench/saveApInvoice', 'APInvoiceAllsController@saveApInvoice')->name('APInvoiceAlls.saveApInvoice');
	Route::post('/APinvoice_header_workbench/closeApInvoice', 'APInvoiceAllsController@closeApInvoice')->name('APInvoiceAlls.closeApInvoice');
	Route::post('/APinvoice_header_workbench/statusChangeOrder', 'APInvoiceAllsController@statusChangeOrder')->name('APInvoiceAlls.statusChangeOrder');
	Route::post('/APinvoice_header_workbench/ProductUpdate', 'APInvoiceAllsController@ProductUpdate')->name('APInvoiceAlls.ProductUpdate');
	Route::post('/APinvoice_header_workbench/ProductUpdateold', 'APInvoiceAllsController@ProductUpdateold')->name('APInvoiceAlls.ProductUpdateold');
	Route::post('/APinvoice_header_workbench/get_customer_AP', 'APInvoiceAllsController@getCustomerAP')->name('APInvoiceAlls.getCustomerAP');
	Route::get('/APinvoice_header_workbench_old/view/{invoice_number}/{supplier_id}', 'APInvoiceAllsController@APInvoiceViewOld')->name('APInvoiceAlls.APinvoiceViewOld');
	Route::post('/APinvoice_header_workbench_old/advancepay', 'APInvoiceAllsController@advancepay')->name('APInvoiceAlls.advancepay');

	Route::post('/APinvoice_header_workbench/ProductSaveOld', 'APInvoiceAllsController@ProductSaveOld')->name('APInvoiceAlls.ProductSaveOld');
	Route::get('/APinvoice_header_workbench/get_customerForOldBooking', 'APInvoiceAllsController@get_customerForOldBooking')->name('APInvoiceAlls.get_customerForOldBooking');
	Route::post('/APinvoice_header_workbench/store_old2','APInvoiceAllsController@ApInvoiceOld2Store')->name('APInvoiceAlls.ApInvoiceOld2Store');
	Route::post('/APinvoice_header_workbench/verify_phone_prebooking','APInvoiceAllsController@verify_phone_prebooking')->name('APInvoiceAlls.verifyPhonePrebooking');
	Route::get('/APinvoice_header_workbench/instantap', 'APInvoiceAllsController@instantAp')->name('APInvoiceAlls.instantAp');
	Route::post('/APinvoice_header_workbench/instantap_store','APInvoiceAllsController@InstantapStore')->name('APInvoiceAlls.InstantapStore');
	Route::get('/APinvoice_header_workbench/instantapupdate/{invoiceid}', 'APInvoiceAllsController@instantApUpdate')->name('APInvoiceAlls.instantApUpdate');
	Route::post('/APinvoice_header_workbench/instantap_updatestore','APInvoiceAllsController@InstantapUpdateStore')->name('APInvoiceAlls.InstantapUpdateStore');
	Route::get('/APinvoice_header_workbench/{image}', 'APInvoiceAllsController@invoiceView')->name('APInvoiceAlls.invoiceView');
	Route::get('/APinvoice_header_workbench/APInvoice/{invoice_id}', 'APInvoiceAllsController@APInvoice')->name('APInvoiceAlls.APInvoice');
	Route::get('/APinvoice_header_workbench/APInvoiceold/{invoice_id}', 'APInvoiceAllsController@APInvoiceold')->name('APInvoiceAlls.APInvoiceold');
	Route::post('/APinvoice_header_workbench/otp_send','APInvoiceAllsController@otpSend')->name('APInvoiceAlls.otpSend');
	Route::get('/APinvoice_header_workbench/resend/{invoice_number}/{supplier_id}', 'APInvoiceAllsController@resend')->name('APInvoiceAlls.resend');
	Route::post('/APinvoice_header_workbench/otp_verify','APInvoiceAllsController@verifyotp')->name('APInvoiceAlls.verifyotp');
	Route::get('/APinvoice_header_workbench_old2/view/{invoice_number}/{supplier_id}', 'APInvoiceAllsController@APInvoiceViewOld2')->name('APInvoiceAlls.APinvoiceViewOld2');
	Route::post('/APinvoice_header_workbench/otp_resend','APInvoiceAllsController@otpreSend')->name('APInvoiceAlls.otpreSend');
	
	
	
	Route::get('/PreOrderBooking/invoice/{invoice_id}/{bal}', 'PreOrderBookingController@Invoice')->name('PreOrderBooking.Invoice');
	Route::get('/PreOrderBooking/arinvoicepre/{invoice_id}', 'PreOrderBookingController@Arinvoicepre')->name('PreOrderBooking.Arinvoicepre');
	Route::get('/PreOrderBooking/advInvoice/{invoice_id}', 'PreOrderBookingController@AdvInvoice')->name('PreOrderBooking.AdvInvoice');
	Route::get('/PreOrderBooking', 'PreOrderBookingController@index')->name('PreOrderBooking.index');
	Route::get('/PreOrderBooking/Advancepay/{invoice_id}/{invoice_num}', 'PreOrderBookingController@Advancepay')->name('PreOrderBooking.Advancepay');
	Route::get('/PreOrderBooking/find_order_booking', 'PreOrderBookingController@FindOrderBooking')->name('PreOrderBooking.FindOrderBooking');
	Route::get('/PreOrderBooking/credit_booking', 'PreOrderBookingController@CreditBooking')->name('PreOrderBooking.CreditBooking');
	Route::get('/PreOrderBooking/CreditBookingdata', 'PreOrderBookingController@CreditBookingdata')->name('PreOrderBooking.CreditBookingdata');
	Route::get('/PreOrderBooking/pending_delivery', 'PreOrderBookingController@PendingDelivery')->name('PreOrderBooking.PendingDelivery');
	Route::get('/PreOrderBooking/CustomerForPrebooking','PreOrderBookingController@CustomerForPrebooking')->name('PreOrderBooking.CustomerForPrebooking');
	Route::post('/PreOrderBooking/store','PreOrderBookingController@PreBookingStore')->name('PreOrderBooking.PreBookingStore');
	Route::post('/PreOrderBooking/generatear','PreOrderBookingController@GenerateAr')->name('PreOrderBooking.GenerateAr');
	Route::get('/PreOrderBooking/FindOrderBookingdata', 'PreOrderBookingController@FindOrderBookingdata')->name('PreOrderBooking.FindOrderBookingdata');
	Route::get('/PreOrderBooking/PreOrderBookingLines/{invoice_number}', 'PreOrderBookingController@PreOrderBookingLines')->name('PreOrderBooking.PreOrderBookingLines');
	Route::post('/PreOrderBooking/ProductSave', 'PreOrderBookingController@ProductSave')->name('PreOrderBooking.ProductSave');
	Route::post('/PreOrderBooking/getbookdetail', 'PreOrderBookingController@getBookDetail')->name('PreOrderBooking.getbookdetail');
	Route::post('/PreOrderBooking/get_book_detail_invoice', 'PreOrderBookingController@getBookDetailInvoice')->name('PreOrderBooking.get_book_detail_invoice');
	Route::post('/PreOrderBooking/getDescription', 'PreOrderBookingController@getDescription')->name('PreOrderBooking.getDescription');
	Route::post('/PreOrderBooking/getInvoicePrint', 'PreOrderBookingController@getInvoicePrint')->name('PreOrderBooking.getInvoicePrint');
	Route::post('/PreOrderBooking/ProductSave', 'PreOrderBookingController@ProductSave')->name('PreOrderBooking.ProductSave');
	Route::post('/PreOrderBooking/applycoupon', 'PreOrderBookingController@applycoupon')->name('PreOrderBooking.applycoupon');
	Route::post('/PreOrderBooking/updateline', 'PreOrderBookingController@updateline')->name('PreOrderBooking.updateline');
	Route::post('/PreOrderBooking/destroyLine', 'PreOrderBookingController@destroyLine')->name('PreOrderBooking.destroyLine');
	Route::post('/PreOrderBooking/statusChangeOrder', 'PreOrderBookingController@statusChangeOrder')->name('PreOrderBooking.statusChangeOrder');
	Route::get('/PreOrderBooking/deletecoupon/{id}', 'PreOrderBookingController@deletecoupon')->name('PreOrderBooking.deletecoupon');
	Route::post('/PreOrderBooking/verify_phone_prebooking','PreOrderBookingController@verify_phone_prebooking')->name('PreOrderBooking.verifyPhonePrebooking');
	Route::post('/PreOrderBooking/resend_code', 'PreOrderBookingController@resend_code')->name('PreOrderBooking.resend_code');
	Route::post('/PreOrderBooking/PreBookingUpdate', 'PreOrderBookingController@PreBookingUpdate')->name('PreOrderBooking.PreBookingUpdate');
	Route::get('/PreOrderBooking/Cancelorder/{id}', 'PreOrderBookingController@CancelOrder')->name('PreOrderBooking.CancelOrder');
	Route::get('/PreOrderBooking/SearchInpFind', 'PreOrderBookingController@SearchInpFind')->name('PreOrderBooking.SearchInpFind');
	Route::get('/PreOrderBooking/AdvanceInvoice/{invoice_id}/{amt}', 'PreOrderBookingController@AdvanceInvoice')->name('PreOrderBooking.AdvanceInvoice');
	Route::get('/PreOrderBooking/arinvoiceprebookview/{invoice_number}', 'PreOrderBookingController@arinvoiceprebookView')->name('PreOrderBooking.arinvoiceprebookView');


	Route::get('/task/tasklist', 'TasksController@index')->name('Task.index');
	Route::get('/task/create', 'TasksController@create')->name('Task.create');
	Route::get('/task/{id}/edit', 'TasksController@edit')->name('Task.edit');
	Route::post('/task/update', 'TasksController@update')->name('Task.update');
	Route::post('/task/subform', 'TasksController@subform')->name('Task.subform');
	Route::get('/task/taskdetail/{id}', 'TasksController@taskdetail')->name('Task.taskdetail');
	Route::get('/task/delete/{id}', 'TasksController@delete')->name('Task.delete');
	Route::get('/task/search', 'TasksController@search')->name('Task.search');
	Route::get('/task/closetasksearch', 'TasksController@closesearch')->name('Task.closesearch');
	Route::post('/task/store', 'TasksController@store')->name('Task.store');
	Route::post('/task/wrnmsgstore', 'TasksController@wrnmsgstore')->name('Task.wrnmsgstore');
	Route::get('/task/wrnmsgcreate', 'TasksController@wrnmsgcreate')->name('Task.wrnmsgcreate');
	Route::get('/task/closedtask', 'TasksController@ClosedTask')->name('Task.ClosedTask');
	Route::get('/task/warningmessage', 'TasksController@warningmessage')->name('Task.warningmessage');
	Route::get('/task/{id}/warningmessageupdate', 'TasksController@warningmessageupdate')->name('Task.warningmessageupdate');
	Route::post('/task/wrnuptstore', 'TasksController@wrnuptstore')->name('Task.wrnuptstore');
	Route::get('/task/searchwrnmsg', 'TasksController@searchwrnmsg')->name('Task.searchwrnmsg');
});