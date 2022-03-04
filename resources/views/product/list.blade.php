@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
            <!-- 	<div class="card-box pd-20 height-100-p mb-30">
                <div class="row align-items-center">
                 <div class="col-md-4">
                  <img src="{{ asset('deskapp/vendors/images/banner-img.png') }}" alt="">
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
                            <a class="btn btn-primary " href="{{ route('product.create') }}">
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
                <h2 class="h4 pd-20">Product</h2>
                <table id="example1" class="table nowrap responsive">
                    <thead>
                        <tr>
                            <th class="all">SL</th>
                            <th class="all">Product Name</th>
                            <th class="all">Product Image</th>
                            <th class="all">Product Unit</th>
                            <th class="all">Product Stock</th>
                            <th class="all">Product Price</th>
                            <th class="all">Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th class="all datatable-nosort">Action</th>

                        </tr>
                    </thead>
                    <tbody id="demo">





                    </tbody>
                </table>
            </div>
           
        </div>
    </div>
    <script src="{{ asset('js/jquery-min.js') }}"></script>
    <script>

    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            var x = localStorage.getItem("loginUser");
            x = JSON.parse(x);
            var i=1;

            // $.ajaxSetup({
            //     headers: {
            //         'Authorization': 'Bearer ' + x.token
            //     }
            // });



             $('#example1').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                "searching": true,
                "iDisplayLength": 100,
                "lengthMenu": [[100, 250, 500], [100, 250, 500]],
              "ajax": {
                               "url": "{{ route('product.list') }}",
                             "type": "GET",
                             headers: {
                    'Authorization': 'Bearer ' + x.token
                },
                        },
              "columns": [
                {
                    "data": "id",       
                    render: function (data, type, full, meta) {
                      
                      return i++;
                },},
                {
                    "data": "product_name",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
               
                {
                    "data": "product_image",       
                    render: function (data, type, full, meta) {
                        return    `<div>
                                            <img src="{!! asset('documents/${data}') !!}" />
                                    </div>`;
                    }
                },
                 
                {
                    "data": "product_unit",       
                    render: function (data, type, full, meta) {
                        console.log(full)
                        return  data;
                    }
                },

                {
                    "data": "product_quantity",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                {
                    "data": "product_price",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                 {
                    "data": "status",       
                    render: function (data, type, full, meta) {
                        return  data==1?'Active':'InActive';
                    }
                },
                 {
                    "data": "created_at",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                 {
                    "data": "updated_at",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                {
                    "data": "id",       
                    render: function (data, type, full, meta) {
                        return `<div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">

                                            <a class="dropdown-item" href="{{ url('/product/edit/?id=${data}') }}"><i class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item" onclick="remove(${data})"><i class="dw dw-delete-3"></i> Delete</a>
                                        </div>
                                    </div>`;
                    }
                },
                 

                 
                
              ],
          });

                });

        function remove(id) {
            var confirms = confirm("Are you sure want to delete this?");
            if (confirms) {
                var id = id;
                const token = JSON.parse(localStorage.getItem('loginUser'));
                $.ajax({
                    type: "DELETE",
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token.token
                    },
                    url: "{{ url('api/v1/pro/delete') }}/" + id,
                    dataType: "JSON",
                    data: {
                        id: id,
                        user_id: token.id
                    },
                    success: function(data) {
                        console.log(data)
                        window.location.reload();
                    }
                });

            }


        }
    </script>

@endsection
