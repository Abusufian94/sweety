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
                            <a class="btn btn-primary " href="{{ route('warehouse.create') }}">
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
                <h2 class="h4 pd-20">Warehouse User list</h2>
                <table id="example1" class="table nowrap responsive">

                    <thead>
                        <tr>
                            <th class="all">SL</th>
                            <th class="all">Name</th>
                            <th class="all">Email</th>
                            <th class="all">Status</th>
                            <th class="datatable-nosort all">Action</th>
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
          var x = localStorage.getItem("loginUser");
            x = JSON.parse(x);
        $(document).ready(function() {
          
          loadDataTable();
        });
        function remove(id) {
            var confirms = confirm("Are you sure want to delete this?");
            if (confirms) {
                apiCall("{{ url('api/v1/warehose/delete') }}", "Delete", {
                        "w_id": id
                    })
                    .then(function(data) {
                      
                        window.location.reload();
                    })
            }
        }
       
          
    function  changeStatus(id){
      
   $('#example1').DataTable().clear().destroy();
 apiCall("{{ url('api/v1/change-user-status') }}", "get", {
                      
                        "id": id,
                    })
                    .then(function(data) {
                       
                       //window.location.reload();
                       loadDataTable();
                    })
    

    }
    function  loadDataTable()
    {
          // $.ajaxSetup({
          //       headers: {
          //           'Authorization': 'Bearer ' + x.token
          //       }
          //   });
          //   $('#example1').dataTable({
          //       processing: true,
          //       serverSide: true,
          //       bRetrieve: true,
          //       "ajax": {
          //           "url": "{{ route('warehouse.list') }}",
          //           "type": "GET",
          //       },
          //       destroy: true,
          //       columns: [{
          //               data: 'id',
          //               "sortable": false,
          //               render: function(data, type, row, meta) {
          //                   return meta.row + meta.settings._iDisplayStart + 1;
          //               }
          //           },
          //           {
          //               data: 'name'
          //           },
          //           {
          //               data: 'email'
          //           },
          //           {
          //               data: 'status'
          //           },
          //           {
          //               data: 'status'
          //           }
          //       ],
          //       "columnDefs": [{
          //               "targets": 4,
          //               "render": function(data, type, row, meta) {
          //                   return `<div class="dropdown">
          //                               <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
          //                                   <i class="dw dw-more"></i>
          //                               </a>
          //                               <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
          //                                   <a class="dropdown-item" href="{{ url('/warehouse/edit/?id=${ row.id}') }}"><i class="dw dw-edit2"></i> Edit</a>
          //                                   <a class="dropdown-item" onclick="remove(${ row.id})"><i class="dw dw-delete-3"></i> Delete</a>
          //                               </div>
          //                           </div>`
          //               }
          //           },
          //           {
          //               "targets": 3,
          //               "render": function(data, type, row, meta) {
          //                  console.log(row.id);
          //                   return row.status == 1 ? `<a href="#" class="badge badge-success " onclick="changeStatus(${ row.status},${row.id})" data-status="${row.status}">Active</a>` :`<a href="#" class="badge badge-warning changeStatus" onclick="changeStatus(${ row.status})" data-status="${row.status}">Inactive</a>` ;
          //               }
          //           },
          //           {
          //               "orderable": false,
          //               "targets": 0
          //           }
          //       ],
          //       'aaSorting': [
          //           [1, 'asc']
          //       ]
          //   });
            var i = 1;

             $('#example1').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                "searching": true,
                "iDisplayLength": 100,
                "lengthMenu": [[100, 250, 500], [100, 250, 500]],
              "ajax": {
                               "url": "{{ route('warehouse.list') }}",
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
                    "data": "name",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
               
                {
                    "data": "email",       
                    render: function (data, type, full, meta) {
                        return    data;
                    }
                },
                 
                {
                    "data": "status",       
                    render: function (data, type, full, meta) {
                      //console.log(full);
                         return data == 1 ? `<a href="#" class="badge badge-success " onclick="changeStatus(${ full.id})" data-status="${full.status}">Active</a>` :`<a href="#" class="badge badge-warning changeStatus" onclick="changeStatus(${ full.id})" data-status="${data}">Inactive</a>` 
                    }
                },

             
                 
                
                {
                    "data": "id",       
                    render: function (data, type, full, meta) {
                        return  `<div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="{{ url('/warehouse/edit/?id=${ full.id}') }}"><i class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item" onclick="remove(${ full.id})"><i class="dw dw-delete-3"></i> Delete</a>
                                        </div>
                                    </div>`;
                    }
                },
                 

                 
                
              ],
          });

    }

    $(document).ready(function(){
  $("p").click(function(){
    alert("The paragraph was clicked.");
  });
});
    </script>

@endsection
