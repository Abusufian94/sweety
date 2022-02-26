<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>RETAIL DASHBOARD</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('deskapp/vendors/images/logo.png')}}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('deskapp/vendors/images/logo.png')}}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('deskapp/vendors/images/logo.png')}}">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('deskapp/vendors/styles/core.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('deskapp/vendors/styles/icon-font.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('deskapp/vendors/styles/style.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('deskapp/src/plugins/sweetalert2/sweetalert2.css')}}">

	<link rel="stylesheet" type="text/css" href="{{ asset('deskapp/src/plugins/datatables/css/dataTables.bootstrap4.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('deskapp/src/plugins/datatables/css/responsive.bootstrap4.min.css')}}">

	<!-- Global site tag (gtag.js) - Google Analytics -->

</head>
<body>
	
	<div class="header">
		<div class="header-left">
			<div class="menu-icon dw dw-menu"></div>
			<div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
			<div class="header-search">

			</div>
		</div>
		<div class="header-right">
			<div class="dashboard-setting user-notification">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
						<i class="dw dw-settings2"></i>
					</a>
				</div>
			</div>
			<div class="user-notification">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
						<i class="icon-copy dw dw-notification"></i>
						<span class="badge notification-active"></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<div class="notification-list mx-h-350 customscroll">
							<ul>
								<li>
									<a href="#">
										<img src="{{ asset('deskapp/vendors/images/img.jpg')}}" alt="">
										<h3>John Doe</h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="{{ asset('deskapp/vendors/images/photo1.jpg')}}" alt="">
										<h3>Lea R. Frith</h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="{{ asset('deskapp/vendors/images/photo2.jpg')}}" alt="">
										<h3>Erik L. Richards</h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="{{ asset('deskapp/vendors/images/photo3.jpg')}}" alt="">
										<h3>John Doe</h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="{{ asset('deskapp/vendors/images/photo4.jpg')}}" alt="">
										<h3>Renee I. Hansen</h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
								<li>
									<a href="#">
										<img src="{{ asset('deskapp/vendors/images/img.jpg')}}" alt="">
										<h3>Vicki M. Coleman</h3>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed...</p>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							<img src="{{ asset('deskapp/vendors/images/photo1.jpg')}}" alt="">
						</span>
						<span class="user-name">Ross C. Lopez</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="profile.html"><i class="dw dw-user1"></i> Profile</a>
						<a class="dropdown-item" href="profile.html"><i class="dw dw-settings2"></i> Setting</a>
						<a class="dropdown-item" href="faq.html"><i class="dw dw-help"></i> Help</a>
						<a class="dropdown-item logOut" href="#"><i class="dw dw-logout "></i> Log Out</a>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="right-sidebar">
		<div class="sidebar-title">
			<h3 class="weight-600 font-16 text-blue">
				Layout Settings
				<span class="btn-block font-weight-400 font-12">User Interface Settings</span>
			</h3>
			<div class="close-sidebar" data-toggle="right-sidebar-close">
				<i class="icon-copy ion-close-round"></i>
			</div>
		</div>
		<div class="right-sidebar-body customscroll">
			<div class="right-sidebar-body-content">
				<h4 class="weight-600 font-18 pb-10">Header Background</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light ">White</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
				<div class="sidebar-radio-group pb-10 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-1" checked="">
						<label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-2" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-2">
						<label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-3" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-3">
						<label class="custom-control-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
					</div>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
				<div class="sidebar-radio-group pb-30 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-1" name="menu-list-icon" class="custom-control-input" value="icon-list-style-1" checked="">
						<label class="custom-control-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-2" name="menu-list-icon" class="custom-control-input" value="icon-list-style-2">
						<label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-3" name="menu-list-icon" class="custom-control-input" value="icon-list-style-3">
						<label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-4" name="menu-list-icon" class="custom-control-input" value="icon-list-style-4" checked="">
						<label class="custom-control-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-5" name="menu-list-icon" class="custom-control-input" value="icon-list-style-5">
						<label class="custom-control-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-6" name="menu-list-icon" class="custom-control-input" value="icon-list-style-6">
						<label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
					</div>
				</div>

				<div class="reset-options pt-30 text-center">
					<button class="btn btn-danger" id="reset-settings">Reset Settings</button>
				</div>
			</div>
		</div>
	</div>

	<div class="left-side-bar">
		<div class="brand-logo">
			<a href="index.html">
				<img src="{{ asset('deskapp/vendors/images/logo.png')}}" alt="" class="dark-logo"  style="width:200px;padding-left: 20px;">
				<img src="{{ asset('deskapp/vendors/images/logo.png')}}" alt="" class="light-logo" style="width:200px;padding-left: 20px;" >
			</a>
			<div class="close-sidebar" data-toggle="left-sidebar-close">
				<i class="ion-close-round"></i>
			</div>
		</div>
		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				<ul id="accordion-menu">
					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-house-1"></span><span class="mtext">Home</span>
						</a>
						<ul class="submenu">
							<li><a href="{{url('admin/dashboard')}}">Dashboard</a></li>

						</ul>
					</li>

					<li class="dropdown">
						<a href="javascript:;" class="dropdown-toggle">
							<span class="micon dw dw-factory1"></span><span class="mtext">Product</span>
						</a>
						<ul class="submenu">
							<li><a href="{{route('retail.product.list')}}">Product List</a></li>
						</ul>
                        <ul class="submenu">
							<li><a href="{{route('retail.product.billings')}}">Billings</a></li>
						</ul>
                        <ul class="submenu">
							<li><a href="{{route('retail.product.billings')}}">Billings</a></l      <script src="{{ asset('js/jquery-min.js')}}"></script>
	  <script src="{{ asset('deskapp/vendors/scripts/core.js')}}"></script>
	<script src="{{ asset('deskapp/vendors/scripts/script.min.js')}}"></script>
	<script src="{{ asset('deskapp/vendors/scripts/process.js')}}"></script>
	<script src="{{ asset('deskapp/vendors/scripts/layout-settings.js')}}"></script>
		<script src="{{ asset('deskapp/src/plugins/sweetalert2/sweetalert2.all.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/sweetalert2/sweet-alert.init.js')}}"></script>


	<script type="text/javascript">
			$(document).ready(function(){
				var x = JSON.parse(localStorage.getItem("loginUser"));
				$('.user-name').html(x.name);


		$(".logOut").click(function(){
			localStorage.removeItem("loginUser");
			 window.location.replace("{{ url('/') }}");
		});



	});
     async function apiCall(apiUrl,method,body={})
     {
        const token = JSON.parse(localStorage.getItem('loginUser'));
        const response = await $.ajax({
            url:apiUrl,
            headers:{
                'Accept':'application/json',
                'Authorization':'Bearer '+token.token
            },
            type:method,
            data:body

        });
        return response;
     }
	</script>

<script src="{{ asset('deskapp/src/plugins/datatables/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/datatables/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/datatables/js/responsive.bootstrap4.min.js')}}"></script>

	<script src="{{ asset('deskapp/src/plugins/datatables/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/datatables/js/buttons.bootstrap4.min.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/datatables/js/buttons.print.min.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/datatables/js/buttons.html5.min.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/datatables/js/buttons.flash.min.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/datatables/js/pdfmake.min.js')}}"></script>
	<script src="{{ asset('deskapp/src/plugins/datatables/js/vfs_fonts.js')}}"></script>

	<script src="{{ asset('deskapp/vendors/scripts/datatable-setting.js')}}"></script></body>
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>

</body>
</html>
