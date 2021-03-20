<!--MAIN NAVIGATION-->
<!--===================================================-->
<nav id="mainnav-container">
    <div id="mainnav">

        <!--Menu-->
        <!--================================-->
        <div id="mainnav-menu-wrap">
            <div class="nano">
                <div class="nano-content">
                    <!--Shortcut buttons-->
                    <!--================================-->
                    <div id="mainnav-shortcut" class="hidden">
                        <ul class="list-unstyled shortcut-wrap">
                            <li class="col-xs-3" data-content="My Profile">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-mint">
                                    <i class="demo-pli-male"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Messages">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-warning">
                                    <i class="demo-pli-speech-bubble-3"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Activity">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-success">
                                    <i class="demo-pli-thunder"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Lock Screen">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-purple">
                                    <i class="demo-pli-lock-2"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!--================================-->
                    <!--End shortcut buttons-->


                    <ul id="mainnav-menu" class="list-group">

                        <!--Category name-->
                        {{-- <li class="list-header">Navigation</li> --}}

                        <!--Menu list item-->
                        <li class="{{ areActiveRoutes(['admin.dashboard'])}}">
                            <a class="nav-link" href="{{route('admin.dashboard')}}">
                                <i class="fa fa-home"></i>
                                <span class="menu-title">{{__('Dashboard')}}</span>
                            </a>
                        </li>

                        @if (\App\Addon::where('unique_identifier', 'pos_system')->first() != null && \App\Addon::where('unique_identifier', 'pos_system')->first()->activated)

                            <li>
                                <a href="#">
                                    <i class="fa fa-print"></i>
                                    <span class="menu-title">{{__('POS Manager')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['poin-of-sales.index', 'poin-of-sales.create'])}}">
                                        <a class="nav-link" href="{{route('poin-of-sales.index')}}">{{__('POS Manager')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['poin-of-sales.activation'])}}">
                                        <a class="nav-link" href="{{route('poin-of-sales.activation')}}">{{__('POS Configuration')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <!-- Product Menu -->
                        @if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="menu-title">{{__('Products')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit'])}}">
                                        <a class="nav-link" href="{{route('brands.index')}}">{{__('Brand')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit'])}}">
                                        <a class="nav-link" href="{{route('categories.index')}}">{{__('Category')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['subcategories.index', 'subcategories.create', 'subcategories.edit'])}}">
                                        <a class="nav-link" href="{{route('subcategories.index')}}">{{__('Subcategory')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['subsubcategories.index', 'subsubcategories.create', 'subsubcategories.edit'])}}">
                                        <a class="nav-link" href="{{route('subsubcategories.index')}}">{{__('Sub Subcategory')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['products.admin', 'products.create', 'products.admin.edit'])}}">
                                        <a class="nav-link" href="{{route('products.admin')}}">{{__('In House Products')}}</a>
                                    </li>
                                    @if(\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                                        <li class="{{ areActiveRoutes(['products.seller', 'products.seller.edit'])}}">
                                            <a class="nav-link" href="{{route('products.seller')}}">{{__('Seller Products')}}</a>
                                        </li>
                                    @endif
                                    @if(\App\BusinessSetting::where('type', 'classified_product')->first()->value == 1)
                                        <li class="{{ areActiveRoutes(['classified_products'])}}">
                                            <a class="nav-link" href="{{route('classified_products')}}">{{__('Classified Products')}}</a>
                                        </li>
                                    @endif
                                    <li class="{{ areActiveRoutes(['digitalproducts.index', 'digitalproducts.create', 'digitalproducts.edit'])}}">
                                        <a class="nav-link" href="{{route('digitalproducts.index')}}">{{__('Digital Products')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['product_bulk_upload.index'])}}">
                                        <a class="nav-link" href="{{route('product_bulk_upload.index')}}">{{__('Bulk Import')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['product_bulk_export.export'])}}">
                                        <a class="nav-link" href="{{route('product_bulk_export.index')}}">{{__('Bulk Export')}}</a>
                                    </li>
                                    @php
                                        $review_count = DB::table('reviews')
                                                    ->orderBy('code', 'desc')
                                                    ->join('products', 'products.id', '=', 'reviews.product_id')
                                                    ->where('products.user_id', Auth::user()->id)
                                                    ->where('reviews.viewed', 0)
                                                    ->select('reviews.id')
                                                    ->distinct()
                                                    ->count();
                                    @endphp
                                    <li class="{{ areActiveRoutes(['reviews.index'])}}">
                                        <a class="nav-link" href="{{route('reviews.index')}}">{{__('Product Reviews')}}@if($review_count > 0)<span class="pull-right badge badge-info">{{ $review_count }}</span>@endif</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('2', json_decode(Auth::user()->staff->role->permissions)))
                        <li class="{{ areActiveRoutes(['flash_deals.index', 'flash_deals.create', 'flash_deals.edit'])}}">
                            <a class="nav-link" href="{{ route('flash_deals.index') }}">
                                <i class="fa fa-bolt"></i>
                                <span class="menu-title">{{__('Flash Deal')}}</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('3', json_decode(Auth::user()->staff->role->permissions)))
                            @php
                                $orders = DB::table('orders')
                                            ->orderBy('code', 'desc')
                                            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                            ->where('order_details.seller_id', \App\User::where('user_type', 'admin')->first()->id)
                                            ->where('orders.viewed', 0)
                                            ->select('orders.id')
                                            ->distinct()
                                            ->count();
                            @endphp
                        <li class="{{ areActiveRoutes(['orders.index.admin', 'orders.show'])}}">
                            <a class="nav-link" href="{{ route('orders.index.admin') }}">
                                <i class="fa fa-shopping-basket"></i>
                                <span class="menu-title">{{__('Inhouse orders')}} @if($orders > 0)<span class="pull-right badge badge-info">{{ $orders }}</span>@endif</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->permissions)))
                        <li class="{{ areActiveRoutes(['pick_up_point.order_index','pick_up_point.order_show'])}}">
                            <a class="nav-link" href="{{ route('pick_up_point.order_index') }}">
                                <i class="fa fa-money"></i>
                                <span class="menu-title">{{__('Pick-up Point Order')}}</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('4', json_decode(Auth::user()->staff->role->permissions)))
                        <li class="{{ areActiveRoutes(['sales.index', 'sales.show'])}}">
                            <a class="nav-link" href="{{ route('sales.index') }}">
                                <i class="fa fa-money"></i>
                                <span class="menu-title">{{__('Total sales')}}</span>
                            </a>
                        </li>
                        @endif


                        <!-- Product Menu -->
                        @if(Auth::user()->user_type == 'admin' || in_array('111', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="menu-title">{{__('Track Courier')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['couriers.index', 'couriers.create', 'couriers.edit'])}}">
                                        <a class="nav-link" href="{{route('couriers.index')}}">{{__('Courier')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif


                        <!-- Product Menu -->
                        @if(Auth::user()->user_type == 'admin' || in_array('115', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="menu-title">{{__('Task')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['Task.index', 'Task.create', 'Task.edit', 'Task.update', 'Task.subform','Task.taskdetail','Task.search', 'Task.delete'])}}">
                                        <a class="nav-link" href="{{route('Task.index')}}">{{__('Open Task List')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['Task.closesearch', 'Task.ClosedTask'])}}">
                                        <a class="nav-link" href="{{route('Task.ClosedTask')}}">{{__('Closed Task List')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['Task.wrnmsgstore', 'Task.wrnmsgcreate', 'Task.warningmessage','Task.warningmessageupdate','Task.wrnuptstore','Task.searchwrnmsg'])}}">
                                        <a class="nav-link" href="{{route('Task.warningmessage')}}">{{__('Warning Message')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff' || in_array('155', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-user-plus"></i>
                                <span class="menu-title">{{__('HR')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['attendance.EmpSalary'] )}}">
                                    
                                    <a class="nav-link" href="{{route('attendance.EmpSalary')}}">{{__('View Employee Salary')}} </a>
                                </li>
                                <li class="{{ areActiveRoutes(['attendance.ApplyLeaveView'])}}">
                                    <a class="nav-link" href="{{ route('attendance.ApplyLeaveView') }}">{{__('Apply Leave')}}</a>
                                </li>

                                <li class="{{ areActiveRoutes(['attendance.ActionLeave'])}}">
                                    <a class="nav-link" href="{{ route('attendance.ActionLeave') }}">{{__('Action Leave')}}</a>
                                </li>

                                <li class="{{ areActiveRoutes(['attendance.LunchReport'])}}">
                                    <a class="nav-link" href="{{ route('attendance.LunchReport') }}">{{__('Lunch Report')}}</a>
                                </li>

                                <li class="{{ areActiveRoutes(['attendance.LeaveReport'])}}">
                                    <a class="nav-link" href="{{ route('attendance.LeaveReport') }}">{{__('Leave Report')}}</a>
                                </li>

                                <li class="{{ areActiveRoutes(['attendance.Calling'])}}">
                                    <a class="nav-link" href="{{ route('attendance.Calling') }}">{{__('Calling')}}</a>
                                </li>

                                <li class="{{ areActiveRoutes(['attendance.CallerList'])}}">
                                    <a class="nav-link" href="{{ route('attendance.CallerList') }}">{{__('Caller List')}}</a>
                                </li>

                                <li class="{{ areActiveRoutes(['attendance.AddCaller'])}}">
                                    <a class="nav-link" href="{{ route('attendance.AddCaller') }}">{{__('Add Caller')}}</a>
                                </li>
                               
                            </ul>
                        </li>
                        @endif


                        @if(Auth::user()->user_type == 'admin' || in_array('15', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-user-plus"></i>
                                <span class="menu-title">{{__('Wallet Recharge')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['wallet.admin.index', 'wallet.recharge.admin', 'show.wallet.edit'] )}}">
                                    @php
                                        $wallets = \App\Wallet::where('approval', 0)->count();
                                        
                                    @endphp
                                    <a class="nav-link" href="{{route('wallet.admin.index')}}">{{__('Recharge Wallet List')}} @if($wallets > 0)<span class="pull-right badge badge-info">{{ $wallet }}</span> @endif</a>
                                </li>
                                <li class="{{ areActiveRoutes(['wallet.view'])}}">
                                    <a class="nav-link" href="{{ route('wallet.view') }}">{{__('Create Recharge Wallet')}}</a>
                                </li>
                               
                            </ul>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('16', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-user-plus"></i>
                                <span class="menu-title">{{__('Receiveables')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                
                                <li class="{{ areActiveRoutes(['order.ARinvoiceCreate'])}}">
                                    <a class="nav-link" href="{{ route('order.ARinvoiceCreate') }}">{{__('Create AR Invoice')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['order.ARinvoiceSearch'])}}">
                                    <a class="nav-link" href="{{ route('order.ARinvoiceSearch') }}">{{__('Search AR Invoice')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['order.ARinvoiceCancel'])}}">
                                    <a class="nav-link" href="{{ route('order.ARinvoiceCancel') }}">{{__('Cancel AR Invoice')}}</a>
                                </li>
                               
                            </ul>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('20', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-user-plus"></i>
                                <span class="menu-title">{{__('PreBooking')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                
                                <li class="{{ areActiveRoutes(['PreOrderBooking.index'])}}">
                                    <a class="nav-link" href="{{ route('PreOrderBooking.index') }}">{{__('Prebooking Form')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['PreOrderBooking.FindOrderBooking'])}}">
                                    <a class="nav-link" href="{{ route('PreOrderBooking.FindOrderBooking') }}">{{__('Find Order')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['PreOrderBooking.CreditBooking'])}}">
                                    <a class="nav-link" href="{{ route('PreOrderBooking.CreditBooking') }}">{{__('Exchange Invoice Booking')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['PreOrderBooking.PendingDelivery'])}}">
                                    <a class="nav-link" href="{{ route('PreOrderBooking.PendingDelivery') }}">{{__('Pending Delivery')}}</a>
                                </li>
                               
                            </ul>
                        </li>
                        @endif



                        @if(Auth::user()->user_type == 'admin' || in_array('21', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-user-plus"></i>
                                <span class="menu-title">{{__('Receivables form')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                
                                <li class="{{ areActiveRoutes(['ArInvoicesAllF.Create'])}}">
                                    <a class="nav-link" href="{{ route('ArInvoicesAllF.Create') }}">{{__('Create Ar Invoice Form')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['ArInvoicesAllF.Search'])}}">
                                    <a class="nav-link" href="{{ route('ArInvoicesAllF.Search') }}">{{__('Search Invoice')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['ArInvoicesAllF.arformreportCreate'])}}">
                                    <a class="nav-link" href="{{ route('ArInvoicesAllF.arformreportCreate') }}">{{__('Ar Form Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['ArInvoicesAllF.LinearformreportCreate'])}}">
                                    <a class="nav-link" href="{{ route('ArInvoicesAllF.LinearformreportCreate') }}">{{__('Linewise Form Reports')}}</a>
                                </li>
                               
                            </ul>
                        </li>
                        @endif



                        @if(Auth::user()->user_type == 'admin' || in_array('17', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-user-plus"></i>
                                <span class="menu-title">{{__('Payables')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                
                                <li class="{{ areActiveRoutes(['APInvoiceAlls.Create'])}}">
                                    <a class="nav-link" href="{{ route('APInvoiceAlls.Create') }}">{{__('Create AP Invoice')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['APInvoiceAlls.Search'])}}">
                                    <a class="nav-link" href="{{ route('APInvoiceAlls.Search') }}">{{__('Search AP Invoice')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['APInvoiceAlls.CreateOld'])}}">
                                    <a class="nav-link" href="{{ route('APInvoiceAlls.CreateOld') }}">{{__('Create AP Invoice - old')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['APInvoiceAlls.CreateOld2'])}}">
                                    <a class="nav-link" href="{{ route('APInvoiceAlls.CreateOld2') }}">{{__('Create AP Invoice - old-2')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['APInvoiceAlls.instantAp'])}}">
                                    <a class="nav-link" href="{{ route('APInvoiceAlls.instantAp') }}">{{__('Instant AP ')}}</a>
                                </li>
                               
                            </ul>
                        </li>
                        @endif

                        

                        @if (\App\Addon::where('unique_identifier', 'refund_request')->first() != null)
                            <li>
                                <a href="#">
                                    <i class="fa fa-refresh"></i>
                                    <span class="menu-title">{{__('Refund Request')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['refund_requests_all', 'reason_show'])}}">
                                        <a class="nav-link" href="{{route('refund_requests_all')}}">{{__('Refund Requests')}}
                                            @if(count(\App\RefundRequest::where('admin_seen',0)->get()) > 0)<span class="pull-right badge badge-info">{{ count(\App\RefundRequest::where('admin_seen',0)->get()) }}</span>@endif
                                        </a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['paid_refund'])}}">
                                        <a class="nav-link" href="{{route('paid_refund')}}">{{__('Approved Refund')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['refund_time_config'])}}">
                                        <a class="nav-link" href="{{route('refund_time_config')}}">{{__('Refund Configuration')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if((Auth::user()->user_type == 'admin' || in_array('5', json_decode(Auth::user()->staff->role->permissions))) && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                        <li>
                            <a href="#">
                                <i class="fa fa-user-plus"></i>
                                <span class="menu-title">{{__('Sellers')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['sellers.index', 'sellers.create', 'sellers.edit', 'sellers.payment_history','sellers.approved','sellers.profile_modal'])}}">
                                    @php
                                        $sellers = \App\Seller::where('verification_status', 0)->where('verification_info', '!=', null)->count();
                                        //$withdraw_req = \App\SellerWithdrawRequest::where('viewed', '0')->get();
                                    @endphp
                                    <a class="nav-link" href="{{route('sellers.index')}}">{{__('Seller List')}} @if($sellers > 0)<span class="pull-right badge badge-info">{{ $sellers }}</span> @endif</a>
                                </li>
                                <li class="{{ areActiveRoutes(['withdraw_requests_all'])}}">
                                    <a class="nav-link" href="{{ route('withdraw_requests_all') }}">{{__('Seller Withdraw Requests')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['sellers.payment_histories'])}}">
                                    <a class="nav-link" href="{{ route('sellers.payment_histories') }}">{{__('Seller Payments')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['business_settings.vendor_commission'])}}">
                                    <a class="nav-link" href="{{ route('business_settings.vendor_commission') }}">{{__('Seller Commission')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['seller_verification_form.index'])}}">
                                    <a class="nav-link" href="{{route('seller_verification_form.index')}}">{{__('Seller Verification Form')}}</a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('6', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-user-plus"></i>
                                <span class="menu-title">{{__('Customers')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                 <li class="{{ areActiveRoutes(['customers.index'])}}">
                                    <a class="nav-link" href="{{ route('customers.index') }}">{{__('Customer list')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['customer_packages.index','customer_packages.edit'])}}">
                                    <a class="nav-link" href="{{ route('customer_packages.index') }}">{{__('Classified Packages')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['customercategories.index'])}}">
                                    <a class="nav-link" href="{{ route('customercategories.index') }}">{{__('Customer Categories')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['institutes.index'])}}">
                                    <a class="nav-link" href="{{ route('institutes.index') }}">{{__('Institutes')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['authors.index'])}}">
                                    <a class="nav-link" href="{{ route('authors.index') }}">{{__('Publisher')}}</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @php
                            $conversation = \App\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '1')->get();
                        @endphp
                        <li class="{{ areActiveRoutes(['conversations.admin_index', 'conversations.admin_show'])}}">
                            <a class="nav-link" href="{{ route('conversations.admin_index') }}">
                                <i class="fa fa-comment"></i>
                                <span class="menu-title">{{__('Conversations')}}</span>
                                @if (count($conversation) > 0)
                                    <span class="pull-right badge badge-info">{{ count($conversation) }}</span>
                                @endif
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <i class="fa fa-file"></i>
                                <span class="menu-title">{{__('Reports')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['stock_report.index'])}}">
                                    <a class="nav-link" href="{{ route('stock_report.index') }}">{{__('Stock Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['in_house_sale_report.index'])}}">
                                    <a class="nav-link" href="{{ route('in_house_sale_report.index') }}">{{__('In House Sale Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['seller_report.index'])}}">
                                    <a class="nav-link" href="{{ route('seller_report.index') }}">{{__('Seller Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['seller_sale_report.index'])}}">
                                    <a class="nav-link" href="{{ route('seller_sale_report.index') }}">{{__('Seller Based Selling Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['wish_report.index'])}}">
                                    <a class="nav-link" href="{{ route('wish_report.index') }}">{{__('Product Wish Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.open_ap_invoice_report'])}}">
                                    <a class="nav-link" href="{{ route('report.open_ap_invoice_report') }}">{{__('Open Ap Invoice Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.open_ar_invoice_report'])}}">
                                    <a class="nav-link" href="{{ route('report.open_ar_invoice_report') }}">{{__('Open Ar Invoice Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.supplier_purchase_report'])}}">
                                    <a class="nav-link" href="{{ route('report.supplier_purchase_report') }}">{{__('Supplier Purchase Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.AR_book_report'])}}">
                                    <a class="nav-link" href="{{ route('report.AR_book_report') }}">{{__('AR Book Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.supplier_ledger_report'])}}">
                                    <a class="nav-link" href="{{ route('report.supplier_ledger_report') }}">{{__('Supplier Ledger Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.AR_ageing_report'])}}">
                                    <a class="nav-link" href="{{ route('report.AR_ageing_report') }}">{{__('AR Ageing Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.emp_adv_report'])}}">
                                    <a class="nav-link" href="{{ route('report.emp_adv_report') }}">{{__('Emp Adv. Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.creditinvoicereport'])}}">
                                    <a class="nav-link" href="{{ route('report.creditinvoicereport') }}">{{__('Credit Invoice Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.gstreport'])}}">
                                    <a class="nav-link" href="{{ route('report.gstreport') }}">{{__('GST Report')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.gstreportbysuppliers'])}}">
                                    <a class="nav-link" href="{{ route('report.gstreportbysuppliers') }}">{{__('GST AP Report By Supplier')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['report.gstreportbycheque'])}}">
                                    <a class="nav-link" href="{{ route('report.gstreportbycheque') }}">{{__('GST AP Report By Cheque')}}</a>
                                </li>
                            </ul>
                        </li>

                        @if(Auth::user()->user_type == 'admin' || in_array('7', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-envelope"></i>
                                <span class="menu-title">{{__('Messaging')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['newsletters.index'])}}">
                                    <a class="nav-link" href="{{route('newsletters.index')}}">{{__('Newsletters')}}</a>
                                </li>

                                @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null)
                                    <li class="{{ areActiveRoutes(['sms.index'])}}">
                                        <a class="nav-link" href="{{route('sms.index')}}">{{__('SMS')}}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                         <li>
							<a href="#">
                                <i class="fa fa-envelope"></i>
                                <span class="menu-title">{{__('Shipping & Payments Methods')}}</span>
                                <i class="arrow"></i>
                            </a>
                            
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['shippings.index'])}}">
                                    <a class="nav-link" href="{{route('shippings.index')}}">{{__('Shippings & Payments Methods')}}</a>
                                </li>
                             </ul>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('8', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-briefcase"></i>
                                <span class="menu-title">{{__('Business Settings')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['activation.index'])}}">
                                    <a class="nav-link" href="{{route('activation.index')}}">{{__('Activation')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['payment_method.index'])}}">
                                    <a class="nav-link" href="{{ route('payment_method.index') }}">{{__('Payment method')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['smtp_settings.index'])}}">
                                    <a class="nav-link" href="{{ route('smtp_settings.index') }}">{{__('SMTP Settings')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['google_analytics.index'])}}">
                                    <a class="nav-link" href="{{ route('google_analytics.index') }}">{{__('Google Analytics')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['facebook_chat.index'])}}">
                                    <a class="nav-link" href="{{ route('facebook_chat.index') }}">{{__('Facebook Chat & Pixel')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['social_login.index'])}}">
                                    <a class="nav-link" href="{{ route('social_login.index') }}">{{__('Social Media Login')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['currency.index'])}}">
                                    <a class="nav-link" href="{{route('currency.index')}}">{{__('Currency')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['languages.index', 'languages.create', 'languages.store', 'languages.show', 'languages.edit'])}}">
                                    <a class="nav-link" href="{{route('languages.index')}}">{{__('Languages')}}</a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('9', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-desktop"></i>
                                <span class="menu-title">{{__('Frontend Settings')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['home_settings.index', 'home_banners.index', 'sliders.index', 'home_categories.index', 'home_banners.create', 'home_categories.create', 'home_categories.edit', 'sliders.create'])}}">
                                    <a class="nav-link" href="{{route('home_settings.index')}}">{{__('Home')}}</a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="menu-title">{{__('Policy Pages')}}</span>
                                        <i class="arrow"></i>
                                    </a>

                                    <!--Submenu-->
                                    <ul class="collapse">

                                        <li class="{{ areActiveRoutes(['sellerpolicy.index'])}}">
                                            <a class="nav-link" href="{{route('sellerpolicy.index', 'seller_policy')}}">{{__('Seller Policy')}}</a>
                                        </li>
                                        <li class="{{ areActiveRoutes(['returnpolicy.index'])}}">
                                            <a class="nav-link" href="{{route('returnpolicy.index', 'return_policy')}}">{{__('Return Policy')}}</a>
                                        </li>
                                        <li class="{{ areActiveRoutes(['supportpolicy.index'])}}">
                                            <a class="nav-link" href="{{route('supportpolicy.index', 'support_policy')}}">{{__('Support Policy')}}</a>
                                        </li>
                                        <li class="{{ areActiveRoutes(['terms.index'])}}">
                                            <a class="nav-link" href="{{route('terms.index', 'terms')}}">{{__('Terms & Conditions')}}</a>
                                        </li>
                                        <li class="{{ areActiveRoutes(['privacypolicy.index'])}}">
                                            <a class="nav-link" href="{{route('privacypolicy.index', 'privacy_policy')}}">{{__('Privacy Policy')}}</a>
                                        </li>
                                    </ul>

                                </li>
                                <li class="{{ areActiveRoutes(['pages.index', 'pages.create', 'pages.edit'])}}">
                                    <a class="nav-link" href="{{route('pages.index')}}">{{__('Custom Pages')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['links.index', 'links.create', 'links.edit'])}}">
                                    <a class="nav-link" href="{{route('links.index')}}">{{__('Useful Link')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['generalsettings.index'])}}">
                                    <a class="nav-link" href="{{route('generalsettings.index')}}">{{__('General Settings')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['generalsettings.logo'])}}">
                                    <a class="nav-link" href="{{route('generalsettings.logo')}}">{{__('Logo Settings')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['generalsettings.color'])}}">
                                    <a class="nav-link" href="{{route('generalsettings.color')}}">{{__('Color Settings')}}</a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-gear"></i>
                                <span class="menu-title">{{__('E-commerce Setup')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['attributes.index','attributes.create','attributes.edit'])}}">
                                    <a class="nav-link" href="{{route('attributes.index')}}">{{__('Attribute')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['coupon.index','coupon.create','coupon.edit'])}}">
                                    <a class="nav-link" href="{{route('coupon.index')}}">{{__('Coupon')}}</a>
                                </li>
                                <li>
                                    <li class="{{ areActiveRoutes(['pick_up_points.index','pick_up_points.create','pick_up_points.edit'])}}">
                                        <a class="nav-link" href="{{route('pick_up_points.index')}}">{{__('Pickup Point')}}</a>
                                    </li>
                                </li>
                                <li>
                                    <li class="{{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                        <a class="nav-link" href="{{route('shipping_configuration.index')}}">{{__('Shipping Configuration')}}</a>
                                    </li>
                                </li>
                                <li>
                                    <li class="{{ areActiveRoutes(['countries.index','countries.edit','countries.update'])}}">
                                        <a class="nav-link" href="{{route('countries.index')}}">{{__('Shipping Countries')}}</a>
                                    </li>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null)
                            <li>
                                <a href="#">
                                    <i class="fa fa-link"></i>
                                    <span class="menu-title">{{__('Affiliate System')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['affiliate.configs'])}}">
                                        <a class="nav-link" href="{{route('affiliate.configs')}}">{{__('Affiliate Configurations')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['affiliate.index'])}}">
                                        <a class="nav-link" href="{{route('affiliate.index')}}">{{__('Affiliate Options')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['affiliate.users', 'affiliate_users.show_verification_request', 'affiliate_user.payment_history'])}}">
                                        <a class="nav-link" href="{{route('affiliate.users')}}">{{__('Affiliate Users')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['refferals.users'])}}">
                                        <a class="nav-link" href="{{route('refferals.users')}}">{{__('Refferal Users')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if (\App\Addon::where('unique_identifier', 'offline_payment')->first() != null)
                            <li>
                                <a href="#">
                                    <i class="fa fa-bank"></i>
                                    <span class="menu-title">{{__('Offline Payment System')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['manual_payment_methods.index', 'manual_payment_methods.create', 'manual_payment_methods.edit'])}}">
                                        <a class="nav-link" href="{{ route('manual_payment_methods.index') }}">{{__('Manual Payment Methods')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['offline_wallet_recharge_request.index'])}}">
                                        <a class="nav-link" href="{{ route('offline_wallet_recharge_request.index') }}">{{__('Offline Wallet Rechage')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if (\App\Addon::where('unique_identifier', 'paytm')->first() != null)
                            <li>
                                <a href="#">
                                    <i class="fa fa-mobile"></i>
                                    <span class="menu-title">{{__('Paytm Payment Gateway')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['paytm.index'])}}">
                                        <a class="nav-link" href="{{route('paytm.index')}}">{{__('Set Paytm Credentials')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if (\App\Addon::where('unique_identifier', 'club_point')->first() != null)
                            <li>
                                <a href="#">
                                    <i class="fa fa-btc"></i>
                                    <span class="menu-title">{{__('Club Point System')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['club_points.configs'])}}">
                                        <a class="nav-link" href="{{route('club_points.configs')}}">{{__('Club Point Configurations')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['set_product_points', 'product_club_point.edit'])}}">
                                        <a class="nav-link" href="{{route('set_product_points')}}">{{__('Set Product Point')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['club_points.index', 'club_point.details'])}}">
                                        <a class="nav-link" href="{{route('club_points.index')}}">{{__('User Points')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null)
                            <li>
                                <a href="#">
                                    <i class="fa fa-mobile"></i>
                                    <span class="menu-title">{{__('OTP System')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['otp.configconfiguration'])}}">
                                        <a class="nav-link" href="{{route('otp.configconfiguration')}}">{{__('OTP Configurations')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['otp_credentials.index'])}}">
                                        <a class="nav-link" href="{{route('otp_credentials.index')}}">{{__('Set OTP Credentials')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                            @php
                                $support_ticket = DB::table('tickets')
                                            ->where('viewed', 0)
                                            ->select('id')
                                            ->count();
                            @endphp
                        <li class="{{ areActiveRoutes(['support_ticket.admin_index', 'support_ticket.admin_show'])}}">
                            <a class="nav-link" href="{{ route('support_ticket.admin_index') }}">
                                <i class="fa fa-support"></i>
                                <span class="menu-title">{{__('Support Ticket')}} @if($support_ticket > 0)<span class="pull-right badge badge-info">{{ $support_ticket }}</span>@endif</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('11', json_decode(Auth::user()->staff->role->permissions)))
                        <li class="{{ areActiveRoutes(['seosetting.index'])}}">
                            <a class="nav-link" href="{{ route('seosetting.index') }}">
                                <i class="fa fa-search"></i>
                                <span class="menu-title">{{__('SEO Setting')}}</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('10', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-user"></i>
                                <span class="menu-title">{{__('Staffs')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li class="{{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}">
                                    <a class="nav-link" href="{{ route('staffs.index') }}">{{__('All staffs')}}</a>
                                </li>
                                <li class="{{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
                                    <a class="nav-link" href="{{route('roles.index')}}">{{__('Staff permissions')}}</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(Auth::user()->user_type == 'admin' || in_array('15', json_decode(Auth::user()->staff->role->permissions)))
                            <li class="{{ areActiveRoutes(['addons.index', 'addons.create'])}}">
                                <a class="nav-link" href="{{ route('addons.index') }}">
                                    <i class="fa fa-wrench"></i>
                                    <span class="menu-title">{{__('Addon Manager')}}</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
        <!--================================-->
        <!--End menu-->

    </div>
</nav>
