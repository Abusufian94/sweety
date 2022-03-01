@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
            <div class="card-box mb-30">
                <h2 class="h4 pd-20">Invoie Billing Data </h2>
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
                        <button type="button" class="btn btn-warning online">Online</button>&nbsp;
                        <button class="btn btn-success" id="cash">Cash</button>

                    </div>
                </div><br>
                <table id="example1" class="table nowrap responsive">
                    <thead>
                        <tr>
                            <th class="all">SL</th>
                            <th class="all">Invpoice Number</th>
                            <th class="all">Price</th>

                            <th class="all">Pay Mode</th>
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
            $('#start_date').on('change', function() {


                loadDataTable()
            });
            $('#end_date').on('change', function() {




                loadDataTable()
            });

        });
        $(document).ready(function() {


            loadDataTable();


        });
        $(document).ready(function() {
            $("#cash").click(function() {

                var status = 'cash';

                $('#example1').DataTable().clear().destroy();

                loadDataTable(status);
            });
            $('#all').click(function() {

                var status = '';

                $('#example1').DataTable().clear().destroy();

                loadDataTable(status);
            });

            $(".online").click(function() {

                var status = 'online';

                $('#example1').DataTable().clear().destroy();

                loadDataTable(status);
            });
        });

        function loadDataTable(status = '') {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

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
                "lengthMenu": [
                    [100, 250, 500],
                    [100, 250, 500]
                ],
                "ajax": {
                    "url": "{{ url('api/v1/invoice/list?status=') }}" + status + '&start_date=' + start_date +
                        '&end_date=' + end_date,
                    "type": "GET",
                    headers: {
                        'Authorization': 'Bearer ' + x.token
                    },
                },
                destroy: true,
                "columns": [{
                        "data": "id",
                        "orderable": false,
                        render: function(data, type, full, meta) {

                            return i++;
                        },
                    },
                    {
                        "data": "invoice_number",
                        "orderable": false,
                        render: function(data, type, full, meta) {
                            return data;
                        }
                    },
                    {
                        "data": "payment_method",
                        "orderable": true,
                        render: function(data, type, full, meta) {
                            return data;
                        }
                    },
                    {
                        "data": "total_price",
                        "orderable": false,
                        render: function(data, type, full, meta) {
                            return data;
                        }
                    },




                    {
                        "data": "updated_at",
                        "orderable": false,
                        render: function(data, type, full, meta) {
                            return data;
                        }
                    },
                    {
                        "data": "Action",
                        "orderable": false,
                        render: function(data, type, full, meta) {
                           // console.log(full.file)
                            return full.invoice_url != null ? `<a href=${full.invoice_url} download>Download</a>`:'N/A';
                        }
                    },

                    // {
                    //     "data": "status","orderable": false,
                    //     render: function (data, type, full, meta) {
                    //     return  (data == 0) ?`<div class="dropdown">
                //                                     <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                //                                         <i class="dw dw-more"></i>
                //                                      </a>
                //                                      <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                //                                          <a class="dropdown-item" onclick="changeStatus(${full.product_retail_assign_log_id},1)"><i class="dw dw-edit2"></i> Aprrove!</a>


                //                                          </div>
                //                                  </div>`:'';

                    //     }
                    // }
                ],
            });
        }

        function changeStatus(id, status) {
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
                    product_status: status,

                },
                success: function(data) {
                    swal({
                        position: 'top-end',
                        type: 'success',
                        title: "Approved Successfully",
                        showConfirmButton: false,
                        timer: 1000
                    });
                    $('#example1').DataTable().clear().destroy();
                    loadDataTable();
                }
            });

        }
    </script>
@endsection
