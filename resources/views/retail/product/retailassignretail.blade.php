@extends('layouts.retailer')
@section('content')
<div class="main-container">
    <div class="pd-ltr-20">
        <div class="card-box mb-30">
            <h2 class="h4 pd-20"> Retail Product Assign  </h2>
               <div class=" row  pd-20">
                    <div class="col-md-8 row ">
                        <div class="form-group row  col-md-4">
                           <div class="form-group">
                                    <label>FROM</label>
                                    <input class="form-control" placeholder="End Date" type="date" id="start_date">
                            </div>
                        
                        </div>
                         
                        <div class="form-group row  col-md-4">
                           <div class="form-group">
                                    <label>TO</label>
                                    <input class="form-control" placeholder="End Date" type="date" id="end_date">
                            </div>
                        
                        </div>
                    </div>
                <div class="col-md-4">
                    <br><br>
                      <label>Status:</label>
                    <button type="button" class="btn btn-warning pending" >Pending</button>&nbsp;
                    <button class="btn btn-success" id="approved" >Approved</button>
                    &nbsp;
                      <button class="btn btn-secondary" id="all" >All</button>
                </div>
              </div><br>
            <table id="example1" class="table nowrap responsive">
                <thead>
                    <tr>
                        <th class="all">SL</th>
                        <th class="all">Image</th>
                        <th class="all">Product Name</th>
                        <th class="all">Retailer Name</th>
                        <th class="all">Quantity</th>
                        <th class="all">Updated By</th>
                        <th class="all">Status</th>
                        <th class="all">Created At</th>
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
             $('#start_date').on('change', function () {
               
               
                loadDataTable()
             });
             $('#end_date').on('change', function () {
               
                 
                 
                
                   loadDataTable()
             });
            
         });    
        $(document).ready(function() {
            

                  loadDataTable();


                });
            $(document).ready(function(){
          $("#approved").click(function(){
           
            var status=1;

            $('#example1').DataTable().clear().destroy();

            loadDataTable(status);
          });
          $('#all').click(function(){
           
            var status='';

            $('#example1').DataTable().clear().destroy();

            loadDataTable(status);
          });

           $(".pending").click(function(){
           
            var status=0;

            $('#example1').DataTable().clear().destroy();

             loadDataTable(status);
          });
        });
        function loadDataTable(status='') {
               var start_date= $('#start_date').val();
                    var end_date= $('#end_date').val();

            var x = localStorage.getItem("loginUser");
            x = JSON.parse(x);
           

            $.ajaxSetup({
                headers: {
                    'Authorization': 'Bearer ' + x.token
                }
            });

                    
                     var i = 1;
                            $('#example1').DataTable({
                            "destroy": true,
                "processing": true,
                "serverSide": true,
                "searching": true,
                "iDisplayLength": 100,
                "lengthMenu": [[100, 250, 500], [100, 250, 500]],
              "ajax": {
                            "url": "{{ url('api/v1/retail-assigned-product-list/?status=') }}"+status+'&start_date='+start_date+'&end_date='+end_date,
                            "type": "GET",
                             headers: {
                    'Authorization': 'Bearer ' + x.token
                },
                        },
                         destroy: true,
              "columns": [
                {
                    "data": "product_retail_assign_log_id", "orderable": false,      
                    render: function (data, type, full, meta) {
                      
                      return i++;
                },},
                {
                    "data": "products.product_image",  "orderable": false,     
                    render: function (data, type, full, meta) {
                        return  ` <img src="{!! asset('documents/${data}') !!}" />`;
                    }
                },
                {
                    "data": "products.product_name",  
                    "orderable": true,     
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                {
                    "data": "retails.retail_name",       
                    "orderable": false,
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },

              
                {
                    "data": "quantity",    
                    "orderable": false,   
                    render: function (data, type, full, meta) {
                    return  `${full.quantity}(${full.unity})`;
                       
                    }
                },
                {
                    "data": "users.name",    
                    "orderable": false,   
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                 {
                    "data": "status",    
                    "orderable": false,   
                    render: function (data, type, full, meta) {
                        return  (data==0)?'<span class="badge badge-warning">Pending</span>':'<span class="badge badge-success">Approved</span>';
                    }
                },
              
                 {
                    "data": "updated_at",  "orderable": false,     
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                
                {
                    "data": "status","orderable": false,
                    render: function (data, type, full, meta) {
                    return  (data == 0) ?`<div class="dropdown">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                        <i class="dw dw-more"></i>
                                                     </a>
                                                     <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                         <a class="dropdown-item" onclick="changeStatus(${full.product_retail_assign_log_id},1)"><i class="dw dw-edit2"></i> Aprrove!</a>
                                                        

                                                         </div>
                                                 </div>`:'';
       
                    }
                }
              ],
          });
        }

        function changeStatus(id,status) {
                var id = id;
                const token = JSON.parse(localStorage.getItem('loginUser'));
                $.ajax({
                    type: "POST",
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token.token
                    },
                    url: "{{ url('api/v1/retail-products-approve') }}",
                    dataType: "JSON",
                    data: {
                        product_retail_assign_log_id: id,
                        user_id: token.id,
                        product_status:status,

                    },
                    success: function(data) {
                    swal(
                    {
                        position: 'top-end',
                        type: 'success',
                        title: "Approved Successfully",
                        showConfirmButton: false,
                        timer: 1000
                    }
                        );
                    $('#example1').DataTable().clear().destroy();
                    loadDataTable();
                    }
                });

            }
    </script>

@endsection
