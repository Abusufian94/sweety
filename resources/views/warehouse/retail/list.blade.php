@extends('layouts.admin')
@section('content')
<div class="main-container">
    <div class="pd-ltr-20">
     

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <div class="dropdown">
                        <a class="btn btn-primary " href="{{ route('warehouse.retail.create') }}">
                            Create
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-box mb-30">
            <h2 class="h4 pd-20">Retail Assign Log List</h2>
              <div class=" row  " style="margin-left: 900px">
                    <button type="button" class="btn btn-warning pending" >Pending</button>&nbsp;
                    <button class="btn btn-success" id="approved">Approved</button>
                         </div><br>
            <table id="example1" class="table  responsive">
              
                <thead>
                    
                    <tr>
                        <th class="">SL</th>
                        <th class="">Product Name</th>
                        <th class="">Retailer Name</th>
                        <th class=""> Unit</th>
                        <th class="">Quantity</th>
                        <th class="">Updated By</th>
                        <th class="">Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th class="">Action</th>

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
           
         
            loadDataTable();

            

                });

        $(document).ready(function(){
          $("#approved").click(function(){
           
            var status=1;

            $('#example1').DataTable().clear().destroy();

             loadDataTable(status);
          });

           $(".pending").click(function(){
           
            var status=0;

            $('#example1').DataTable().clear().destroy();

             loadDataTable(status);
          });
        });


         function loadDataTable(status)
                {
                   
                  var i = 1;

                       var x = localStorage.getItem("loginUser");
                    x = JSON.parse(x);

                         $('#example1').DataTable({
                            "destroy": true,
                "processing": true,
                "serverSide": true,
                "searching": false,
                "iDisplayLength": 100,
                "lengthMenu": [[100, 250, 500], [100, 250, 500]],
              "ajax": {
                            "url": `{{ url('api/v1/product-retail-list/${status}')}}`,
                            "type": "GET",
                             headers: {
                    'Authorization': 'Bearer ' + x.token
                },
                        },
              "columns": [
                {
                    "data": "product_retail_assign_log_id",       
                    render: function (data, type, full, meta) {
                      
                      return i++;
                },},
                {
                    "data": "products.product_name",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                {
                    "data": "retails.retail_name",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                {
                    "data": "unity",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },

                {
                    "data": "quantity",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                {
                    "data": "users.name",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                 {
                    "data": "status",       
                    render: function (data, type, full, meta) {
                        return  (data==0)?'Pending':'Approved';
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
                    "data": "status",
                    render: function (data, type, full, meta) {
                    return  (data == 0) ?`<div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="{{ url('warehouse/edit/retail?id=${full.product_retail_assign_log_id}') }}"><i class="dw dw-edit2"></i> Edit</a>
                                        </div>
                                    </div>`:'';
       
                    }
                }
              ],
          });

           
                 
                }

             
            </script>

@endsection
