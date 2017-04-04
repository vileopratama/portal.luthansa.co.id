<!DOCTYPE html>
<html lang="en">
<head>
	<title>{!! Lang::get('global.title') !!}</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
    <meta name="csrf_token" content="{!! csrf_token() !!}" />
    <link rel="shortcut icon" sizes="196x196" href="{!! Theme::asset('administrator::images/logo.png') !!}" />
    <link rel="stylesheet" href="{!! Theme::asset('libs/bower/font-awesome/css/font-awesome.min.css') !!}" />
    <link rel="stylesheet" href="{!! Theme::asset('libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.css') !!}" />
	<link rel="stylesheet" href="{!! Theme::asset('libs/bower/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') !!}" />
    <link rel="stylesheet" href="{!! Theme::asset('css/app.min.css') !!}" />
    <link rel="stylesheet" href="{!! Theme::asset('libs/bower/jquery-confirm/jquery-confirm.min.css') !!}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300" />
	
	@stack('css')
    <script src="{!! Theme::asset('libs/bower/breakpoints.js/dist/breakpoints.min.js') !!}"></script>
	
    <script>Breakpoints();</script>
</head>
<body class="menubar-left menubar-unfold menubar-light theme-primary">
<nav id="app-navbar" class="navbar navbar-inverse navbar-fixed-top primary">
    <div class="navbar-header">
        <button type="button" id="menubar-toggle-btn"
                class="navbar-toggle visible-xs-inline-block navbar-toggle-left hamburger hamburger--collapse js-hamburger">
            <span class="sr-only">Toggle navigation</span> <span class="hamburger-box"><span
                        class="hamburger-inner"></span></span></button>
        <button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse"
                data-target="#app-navbar-collapse" aria-expanded="false"><span class="sr-only">Toggle navigation</span>
            <span class="zmdi zmdi-hc-lg zmdi-more"></span></button>
        <!--<button type="button" class="navbar-toggle navbar-toggle-right collapsed" data-toggle="collapse"
                data-target="#navbar-search" aria-expanded="false"><span class="sr-only">Toggle navigation</span> <span
                    class="zmdi zmdi-hc-lg zmdi-search"></span></button>-->
        <a href="{!! url('/') !!}" class="navbar-brand visible-lg visible-md visible-sm"><span class="brand-icon"><img src="{!! Theme::asset('images/logo.png') !!}" /></span></a>
    </div>

    <div class="navbar-container container-fluid">
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="nav navbar-toolbar navbar-toolbar-left navbar-left">
                <li class="hidden-float hidden-menubar-top"><a href="javascript:void(0)" role="button"
                                                               id="menubar-fold-btn"
                                                               class="hamburger hamburger--arrowalt is-active js-hamburger"><span
                                class="hamburger-box"><span class="hamburger-inner"></span></span></a></li>
                <li><h5 class="page-title hidden-menubar-top hidden-float">{!! $title !!} </h5></li>
            </ul>
            <ul class="nav navbar-toolbar navbar-toolbar-right navbar-right">
				<li class="nav-item dropdown hidden-float"><a href="{!! url('/') !!}" target="_blank" aria-expanded="false"><i class="fa fa-globe"></i> {!! Lang::get('global.visit frontend') !!}</a></li>
                <!--<li class="nav-item dropdown hidden-float"><a href="javascript:void(0)" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false"><i class="zmdi zmdi-hc-lg zmdi-search"></i></a></li>-->
                <li class="dropdown">
					<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<i class="zmdi zmdi-hc-lg zmdi-notifications"></i> 
						@if(App::count_sales_due_date()>0)
							<span class="label label-info">{!! App::count_sales_due_date() !!}</span>
						@endif
					</a>
					<div class="media-group dropdown-menu animated flipInY">
						@if(App::sales_order_due_date())
							@foreach(App::sales_order_due_date() as $key => $row)
								<a href="{!! url('/sales-order/view/'.Crypt::encrypt($row->id)) !!}" class="media-group-item">
									<div class="media">
										<div class="media-body">
											<h5 class="media-heading">{!! Lang::get('global.invoice')!!} : #{!! $row->number !!}</h5>
											<small class="media-meta">{!! Lang::get('global.due date')!!} : {!! $row->due_date !!}</small>
										</div>
									</div>
								</a> 
							@endforeach
						@endif
						@if(App::sales_invoice_due_date())
							@foreach(App::sales_invoice_due_date() as $key => $row)
								<a href="{!! url('/sales-invoice/view/'.Crypt::encrypt($row->id)) !!}" class="media-group-item">
									<div class="media">
										<div class="media-body">
											<h5 class="media-heading">{!! Lang::get('global.invoice')!!} : #{!! $row->number !!}</h5>
											<small class="media-meta">{!! Lang::get('global.due date')!!} : {!! $row->due_date !!}</small>
										</div>
									</div>
								</a> 
							@endforeach
						@endif
					</div>
                </li>
                <!--<li class="dropdown">
					<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="zmdi zmdi-hc-lg zmdi-settings"></i></a>
                    <ul class="dropdown-menu animated flipInY">
						@if(App::access('r','setting'))
						<li><a href="{!! url('/setting') !!}"><i class="fa fa-gear"></i> {!! Lang::get('global.setting') !!}</a></li>
                        @endif
						@if(App::access('r','profile'))
						<li><a href="{!! url('/profile') !!}"><i class="fa fa-user"></i> {!! Lang::get('global.my profile') !!}</a></li>
                        <li><a href="{!! url('/profile/password') !!}"><i class="fa fa-key"></i> {!! Lang::get('global.change password') !!}</a></li>
                        @endif
						<li><a href="{!! url('/session/logout') !!}"><i class="fa fa-power-off"></i> {!! Lang::get('global.logout') !!}</a></li>
                    </ul>
                </li>-->

            </ul>
        </div>
    </div>
</nav>
<aside id="menubar" class="menubar light">
    <div class="app-user">
        <div class="media">
            <div class="media-left">
                <div class="avatar avatar-md avatar-circle">
                    <a href="javascript:void(0)">
                        <img class="img-responsive" src="{!! Theme::asset('images/221.jpg') !!}" alt="avatar">
                    </a>
                </div>
            </div>
            <div class="media-body">
                <div class="foldable">
                    <h5><a href="javascript:void(0)" class="username">{!! Auth::user()->first_name !!}</a></h5>
                    <ul>
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle usertitle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <small>{!! Auth::user()->email !!} <span class="caret"></span></small> 
                            </a>
                            <ul class="dropdown-menu animated flipInY">
                                <li><a class="text-color" href="{!! url('/') !!}"><span class="m-r-xs"><i class="fa fa-home"></i></span> <span>{!! Lang::get('global.dashboard') !!}</span></a></li>
                                @if(App::access('r','profile'))
								<li><a class="text-color" href="{!! url('/profile') !!}"><span class="m-r-xs"><i class="fa fa-user"></i></span> <span>{!! Lang::get('global.my profile') !!}</span></a></li>
								<li><a class="text-color" href="{!! url('/profile/password') !!}"><span class="m-r-xs"><i class="fa fa-key"></i></span> <span>{!! Lang::get('global.change password') !!}</span></a></li>
                                @endif
								@if(App::access('r','setting'))
								<li><a class="text-color" href="{!! url('/setting') !!}"><span class="m-r-xs"><i class="fa fa-gear"></i></span> <span>{!! Lang::get('global.setting') !!}</span></a></li>
                                <li role="separator" class="divider"></li>
                                @endif
								<li><a class="text-color" href="{!! url('session/logout') !!}"><span class="m-r-xs"><i class="fa fa-power-off"></i></span> <span>{!! Lang::get("global.logout") !!}</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="menubar-scroll">
        <div class="menubar-scroll-inner">
            <ul class="app-menu">
				@if(App::access('r','dashboard'))
                <li class="has-submenu"><a href="javascript:void(0)" class="submenu-toggle"><i
                                class="menu-icon zmdi zmdi-view-dashboard zmdi-hc-lg"></i> <span
                                class="menu-text">{!! Lang::get("global.dashboard") !!}</span> <i
                                class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu">
                        <li><a class="dashboard" href="{!! url('/dashboard') !!}"><span class="menu-text">{!! Lang::get("global.web statistic") !!}</span></a></li>
                    </ul>
                </li>
				@endif
				
				@if(App::access('r','armada') || App::access('r','armada-category') || App::access('r','company'))
                <li class="has-submenu">
                    <a href="javascript:void(0)" class="submenu-toggle"  >
                        <i class="menu-icon zmdi zmdi-car zmdi-hc-lg"></i> <span class="menu-text">{!! Lang::get("global.armada") !!}</span>
                        <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu">
						@if(App::access('r','armada'))
                        <li><a class="armada" href="{!! url('/armada') !!}"><span class="menu-text">{!! Lang::get("global.armada") !!}</span></a></li>
						@endif
						@if(App::access('r','armada-category'))
						<li><a class="armada-category" href="{!! url('/armada-category') !!}"><span class="menu-text">{!! Lang::get("global.transportation type") !!}</span></a></li>
						@endif
						@if(App::access('r','company'))
						<li><a class="company" href="{!! url('/company') !!}"><span class="menu-text">{!! Lang::get("global.otobus company") !!}</span></a></li>
						@endif
					</ul>
                </li>
				@endif
				
				@if(App::access('r','customer'))
                <li class="has-submenu"><a href="javascript:void(0)" class="submenu-toggle"><i
                                class="menu-icon zmdi zmdi-account-box zmdi-hc-lg"></i> <span class="menu-text">{!! Lang::get("global.customers") !!}</span> <i
                                class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu">
                        <li><a class="customer" href="{!! url('/customer') !!}"><span class="menu-text">{!! Lang::get("global.customers") !!}</span></a></li>
                        <li><a class="opportunity" href="{!! url('/customer/opportunity') !!}"><span class="menu-text">{!! Lang::get("global.opportunity") !!}</span></a></li>
                    </ul>
                </li>
				@endif
				
				@if(App::access('r','department') || App::access('r','employee'))
				<li class="has-submenu">
                    <a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-card zmdi-hc-lg"></i>
                        <span class="menu-text">{!! Lang::get("global.human resource") !!}</span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu">
						@if(App::access('r','department'))
                        <li><a class="department" href="{!! url('/department') !!}"><span class="menu-text">{!! Lang::get("global.department") !!}</span></a></li>
                        @endif
						@if(App::access('r','employee'))
						<li><a class="employee" href="{!! url('/employee') !!}"><span class="menu-text">{!! Lang::get("global.employee") !!}</span></a></li>
						@endif
					</ul>
                </li>
				@endif
				
				@if(App::access('r','sales-order') || App::access('r','sales-invoice') || App::access('r','sales-spj'))
                <li class="has-submenu">
                    <a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-book zmdi-hc-lg"></i>
                        <span class="menu-text">{!! Lang::get("global.sales") !!}</span> <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu">
						@if(App::access('r','sales-order'))
                        <li><a class="sales-order" href="{!! url('sales-order') !!}"><span class="menu-text">{!! Lang::get("global.sales order") !!}</span></a></li>
                        @endif
						@if(App::access('r','sales-confirm-payment'))
                        <li><a class="sales-confirm-payment" href="{!! url('sales-confirm-payment') !!}"><span class="menu-text">{!! Lang::get("global.confirm payment") !!}</span></a></li>
                        @endif
						@if(App::access('r','sales-invoice'))
						<li><a class="sales-invoice" href="{!! url('sales-invoice') !!}"><span class="menu-text">{!! Lang::get("global.sales invoice") !!}</span></a></li>
						@endif
						
					</ul>
                </li>
				@endif
				@if(App::access('r','sales-spj'))
				<li><a href="{!! url('sales-spj') !!}"><i class="menu-icon zmdi zmdi-book zmdi-hc-lg"></i> 
							<span class="menu-text">{!! Lang::get("global.spj") !!}</span> 
					</a>
                    
                </li>
				@endif
                
				<li class="has-submenu">
                    <a href="javascript:void(0)" class="submenu-toggle">
						<i class="menu-icon zmdi zmdi-file zmdi-hc-lg"></i>
                        <span class="menu-text"> {!! Lang::get("global.reports") !!} </span> 
						<i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i>
					</a>
                    <ul class="submenu">
                        @if(App::access('r','report-bus-schedule'))
                            <li><a class="report-bus-schedule" href="{!! url('report-bus-schedule') !!}"><span class="menu-text">{!! Lang::get("global.bus schedule") !!}</span></a></li>
                        @endif

                        @if(App::access('r','report-schedule-order'))
                                <li><a class="report-schedule-order" href="{!! url('report-schedule-order') !!}"><span class="menu-text">{!! Lang::get("global.schedule order") !!}</span></a></li>
                            @endif

                            @if(App::access('r','report-income-expense'))
                                <li><a class="report-income-expense" href="{!! url('report-income-expense') !!}"><span class="menu-text">{!! Lang::get("global.income & expense") !!}</span></a></li>
                            @endif

					</ul>
                </li>
                
                <li class="has-submenu"><a href="javascript:void(0)" class="submenu-toggle"><i class="menu-icon zmdi zmdi-settings zmdi-hc-lg"></i> <span class="menu-text">{!! Lang::get("global.administration") !!}</span> <span
                                <i class="menu-caret zmdi zmdi-hc-sm zmdi-chevron-right"></i></a>
                    <ul class="submenu">
						@if(App::access('r','account-bank'))
						<li><a class="account-bank" href="{!! url('/account-bank') !!}"><span class="menu-text">{!! Lang::get("global.account bank")!!}</span></a></li>
						@endif
						@if(App::access('r','bank'))
						<li><a class="bank" href="{!! url('/bank') !!}"><span class="menu-text">{!! Lang::get("global.bank")!!}</span></a></li>
                        @endif
						@if(App::access('r','user'))
						<li><a class="user" href="{!! url('/user') !!}"><span class="menu-text">{!! Lang::get("global.user")!!}</span></a></li>
                        @endif
						@if(App::access('r','user-group'))
						<li><a class="user-group" href="{!! url('/user-group') !!}"><span class="menu-text">{!! Lang::get("global.user group")!!}</span></a></li>
						@endif
						@if(App::access('r','setting'))
						<li><a class="setting" href="{!! url('setting') !!}"><span class="menu-text">{!! Lang::get("global.setting")!!}</span></a></li>
						@endif
					</ul>
                </li>
            </ul>
        </div>
    </div>
</aside>
<!--
<div id="navbar-search" class="navbar-search collapse">
    <div class="navbar-search-inner">
        <form action="#"><span class="search-icon"><i class="fa fa-search"></i></span> <input class="search-field"
                                                                                              type="search"
                                                                                              placeholder="search...">
        </form>
        <button type="button" class="search-close" data-toggle="collapse" data-target="#navbar-search"
                aria-expanded="false"><i class="fa fa-close"></i></button>
    </div>
    <div class="navbar-search-backdrop" data-toggle="collapse" data-target="#navbar-search" aria-expanded="false"></div>
</div>-->
<main id="app-main" class="app-main">
    <div class="wrap">
        <section class="app-content">
        @yield("content")
		<div id="divLoading"></div>
        </section>
    </div>
    <div class="wrap p-t-0">
        <footer class="app-footer">
            <div class="clearfix">
                <ul class="footer-menu pull-right">
                    <li><a href="{!! url('/dashboard') !!}">{!! Lang::get('global.dashboard') !!}</a></li>
                    <li><a href="{!! url('/setting') !!}">{!! Lang::get('global.setting') !!}</a></li>
                </ul>
                <div class="copyright pull-left">Copyright &copy; {!! date('Y') !!}</div>
            </div>
        </footer>
    </div>
</main>

<script src="{!! Theme::asset('js/core.min.js') !!}"></script>
<script src="{!! Theme::asset('libs/bower/jquery-cookie/jquery.cookie.js') !!}"></script>
<script type="text/javascript">
    $(function(){
        var menu_active = '.' + $.cookie('menu_active');
        //alert(menu_active);
        $(menu_active).closest('li').addClass('active');
        $(menu_active).closest('ul').css("display","block");
        //$.removeCookie('menu_active'); // remove cookie

        $('.app-menu a').on('click', function(event) {
            event.preventDefault();
            $.cookie('menu_active',$(this).attr("class"));
            $(location).attr('href',$(this).attr("href"));
        });
    });
</script>
<script src="{!! Theme::asset('libs/bower/moment/moment.js') !!}"></script>
<!--<script src="{!! Theme::asset('libs/bower/fullcalendar/dist/fullcalendar.min.js') !!}"></script>
<script src="{!! Theme::asset('assets/js/fullcalendar.js') !!}"></script>-->
<script src="{!! Theme::asset('libs/bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') !!}"></script>
<script src="{!! Theme::asset('libs/bower/jquery-confirm/jquery-confirm.min.js') !!}"></script>
<script src="{!! Theme::asset('js/app.min.js') !!}"></script>
<script type="text/javascript">
	$(function() {
		$(document).ajaxStart(function() {
	        $.get('{!! url("session/is_login") !!}', function(response) {
	           if(response.isLoggedIn == false){        	 
	                window.location.replace(response.redirect);
	            };
	        },'json');
		});
	});
</script>

@stack('scripts')
@stack('scripts-extra')
</body>
</html>