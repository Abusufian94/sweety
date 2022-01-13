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


            $.ajaxSetup({
                headers: {
                    'Authorization': 'Bearer ' + x.token
                }
            });

                    $('#example1').dataTable({
                        processing: true,
                        serverSide: true,
                        bRetrieve: true ,
                        "ajax": {
                            "url": "{{ route('product.list') }}",
                            "type": "GET",
                        },
                        destroy: true,
                        columns: [{
                                data: 'id'
                            },
                            {
                                data: 'product_name'
                            },
                            {
                                data: 'product_image'
                            },
                            {
                                data: 'product_unit'
                            },
                            {
                                data: 'product_quantity'
                            },
                            {
                                data: 'product_price'
                            },
                            {
                                data: 'status'
                            },

                            {
                                data: 'created_on', "render": function (value) {
                                    if (value === null) return "";
                                    return moment(value).format('DD/MM/YYYY :hh:mm:ss A');
                                }
                            },
                            {
                                data: 'updated_on', "render": function (value) {
                                    if (value === null) return "";
                                    return moment(value).format('DD/MM/YYYY :hh:mm:ss A');
                                }
                            },
                            {
                                data: 'status'
                            },
                        ],
                        "columnDefs": [{
                                "targets": 9,
                                "render": function(data, type, row, meta) {

                                    //return '<a class="dropdown-item" href="{{ url('/stock/edit/?id=${value.raw_id}') }}"><i class="dw dw-edit2"></i> Edit</a>';
                                   //  return "<a href='/stock/edit/?id="+ row.raw_id +"'>" + 'Edit' + "</a> | <a onclick='remove("+ row.raw_id+")'>" + 'Delete' + "</a>"
                                    // return "<a onclick='remove("+ row.raw_id+")'>" + row.raw_id + "</a>"

                                    return `<div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="{{ url('warehouse/edit/product?id=${row.id}') }}"><i class="dw dw-edit2"></i> Edit</a>
                                        </div>
                                    </div>`
                                }

                            },
                            {
                                "targets": 6,
                                "render": function(data, type, row, meta) {

                                    return row.status==1?'Active':'InActive';

                                }

                            },
                            {
                                "targets": 2,
                                "render": function(data, type, row, meta) {

                                    return `<div>
                                            <img src="{!! asset('documents/${row.product_image}') !!}" />
                                    </div>`
                                }

                            },
                            { "orderable": false, "targets": 0 }
                        ],
                        'aaSorting': [[1, 'asc']] ,
                        "order": [[0, "desc" ]]
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
