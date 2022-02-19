@extends('layouts.retailer')
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
                <h2 class="h4 pd-20">Retail Product List</h2>
                <table id="example1" class="table nowrap responsive ">
                    <thead>
                        <tr>
                            <th class="all">SL</th>
                          
                            <th class="all">Image</th>
                              <th class="all"> Name</th>
                            <th class="all">Unit</th>
                            <th class="all">Stock</th>
                            <th class="all"> Price</th>
                            <th class="all"> Retail Name</th>
                          

                        </tr>
                    </thead>
                    <tbody id="demo">





                    </tbody>
                </table>
            </div>

        </div>
    </div>

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

                

      $('#example1').DataTable({
          "processing": true,
          "serverSide": true,
          "destroy": true,
        //"paging": false,
           "ajax": {
                            "url": "{{ route('retail.assign.products') }}",
                            "type": "GET",
                            dataSrc: 'data',
                        },
       
          "columns": [
            {
                "data": "id",  
                   "orderable": false, 
                render: function (data, type, full, meta) {
                    return  meta.row+1;
                }
            },
         
            {
                "data": "product_image",      
                "orderable": true, 
                render: function (data, type, full, meta) {
                    return  `<img src="{!! asset('documents/${data}') !!}" / style="height:100px;width:100px;">`;
                }
            },
               {
                "data": "product_name",  
                "orderable": true,     
                render: function (data, type, full, meta) {
                    return  data;
                }
            },
            {
                "data": "product_unit",  
                "orderable": false,     
                render: function (data, type, full, meta) {
                    return  data;
                }
            },
            
            {
                "data": "quantity",  
                "orderable": false,     
                render: function (data, type, full, meta) {
                    return  data;
                }
            },
            {
                "data": "product_price",  
                "orderable": false,     
                render: function (data, type, full, meta) {
                    return  data;
                }
            },
              {
                "data": "retail_name",  
                "orderable": false,     
                render: function (data, type, full, meta) {
                    return  data;
                }
            },
            
           

          ],

          "drawCallback": function (settings) { 
        // Here the response
      
    },
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
