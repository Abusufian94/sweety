
 @extends('layouts.admin')
 @section('content')
    <script src="{{ asset('js/jquery-min.js')}}"></script>
 <script src="{{ asset('deskapp/src/plugins/datatables/js/jquery.dataTables.min.js')}}"defer></script>

   <script>
   	 const token = JSON.parse(localStorage.getItem('loginUser'));
   	 $(document).ready(function(){
  $('#myTable').DataTable({
  	 "processing": true,
          "serverSide": true,
          // "destroy": true,
          "ajax": {
              url: "{{url('api/v1/warehose/all/')}}",
              type: 'get',
              dataSrc: 'data',
              headers: {
                    'Accept':'application/json',
                	'Authorization':'Bearer '+token.token
                },
          },

          "columns": [
          	{
          		"data": "id",
         		 render: function (data, type, row, meta) {
               return meta.row + meta.settings._iDisplayStart + 1;
          }
     		 },
            {
                "data": "name",       
                render: function (data, type, full, meta) {
                    return  data;
                }
            },
            {
                "data": "email",       
                render: function (data, type, full, meta) {
                    return  data;
                }
            },
            {
                "data": "password_as",       
                render: function (data, type, full, meta) {
                    return  data;
                }
            },
          

            {
                "data": "status",       
                render: function (data, type, full, meta) {
                    return  data;
                }
            },
           
            {
                "data": "status",
                render: function (data, type, full, meta) {
                   let cryptId = window.btoa(full.retail_bill_id);
                   let url = "{{URL::to('/retail-po-bill-preview')}}";
                  return '<a href="'+url+'/'+cryptId+'"><button type="button" class="btn btn-label-success btn-pill btn-tall btn-wide">View</button></a>';
   
                }
            }
          ],
  } );
 });
   </script>
<div class="main-container">
		<div class="pd-ltr-20">
		<!-- 	<div class="card-box pd-20 height-100-p mb-30">
				<div class="row align-items-center">
					<div class="col-md-4">
						<img src="{{asset('deskapp/vendors/images/banner-img.png')}}" alt="">
					</div>
					<div class="col-md-8">
						<h4 class="font-20 weight-500 mb-10 text-capitalize">
							Welcome back <div class="weight-600 font-30 text-blue">Nishan Paul</div>
						</h4>
						<p class="font-18 max-width-600"></p>
					</div>
				</div>
			</div> -->
			{{-- <div class="row">
				<div class="col-md-4 col-sm-12 mb-30 ">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart"></div>
							</div>
							<div class="widget-data">
								<div class="h2 mb-0"><span class="micon dw dw-house-11"></span>1</div>
								<div class="weight-600 font-14">KALAMANDIR</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-12 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart2"></div>
							</div>
							<div class="widget-data">
								<div class="h2 mb-0"><span class="micon dw dw-house-11"></span>2</div>
								<div class="weight-600 font-14">KALAMANDIR</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4  col-sm-12 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart4"></div>
							</div>
							<div class="widget-data">
								<div class="h2 mb-0"><span class="micon dw dw-factory1"></span></div>
								<div class="weight-600 font-14">WARHOUSE</div>
							</div>
						</div>
					</div>
				</div>
			</div> --}}

            <div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							{{-- <div class="title">
								<h4>Themify Icons</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Icons</li>
								</ol>
							</nav> --}}
						</div>
						<div class="col-md-6 col-sm-12 text-right">
							<div class="dropdown">
								<a class="btn btn-primary " href="{{route('warehouse.create')}}" >
									Create
								</a>
								{{-- <div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="#">Export List</a>
									<a class="dropdown-item" href="#">Policies</a>
									<a class="dropdown-item" href="#">View Assets</a>
								</div> --}}
							</div>
						</div>
					</div>
				</div>

			<div class="card-box mb-30">
				<h2 class="h4 pd-20">Best Selling Products</h2>
				<table class="data-table table nowrap responsive" id="myTable">
					<thead>
						<tr>
							<th>SL</th>
							<th>Name</th>
							<th>Email</th>
							<th>Password</th>
							<th>status</th>
							<th class="datatable-nosort">Action</th>
						</tr>
					</thead>
					<tbody id="demo">





					</tbody>
				</table>
			</div>
			<div class="footer-wrap pd-20 mb-20 card-box">
				De By <a href="https://github.com/dropways" target="_blank">Ankit Hingarajiya</a>
			</div>
		</div>
	</div>


	</script>

<script type="text/javascript">

 function remove(id)
 {
    var confirms = confirm("Are you sure want to delete this?");
    if(confirms)
    {
     apiCall("{{url('api/v1/warehose/delete')}}","Delete",{"w_id":id})
     .then(function(data){
         console.log(data)
         window.location.reload();
     })
    }

 }
    </script>

 @endsection
