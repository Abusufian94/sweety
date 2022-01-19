@extends('layouts.admin')
@section('content')
<div class="main-container">
    <div class="pd-ltr-20">
        <div class="card-box mb-30">
            <h2 class="h4 pd-20">Product</h2>
            <table id="example1" class="table nowrap responsive">
                <thead>
                    <tr>
                        <th class="all">SL</th>
                        <th class="all">Product Name</th>
                        <th class="all">Retailer Name</th>
                        <th class="all"> Unit</th>
                        <th class="all">Quantity</th>
                        <th class="all">Updated By</th>
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
            var status = 0;

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
                            "url": "{{ url('api/v1/product-retail-list') }}/" + status,
                            "type": "GET",

                        },
                        destroy: true,
                        columns: [{
                                data: 'product_retail_assign_log_id'
                            },
                            {
                                data: 'products.product_name'
                            },
                            {
                                data: 'retails.retail_name'
                            },
                            {
                                data: 'unity'
                            },
                            {
                                data: 'quantity'
                            },
                            {
                                data: 'users.name'
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
                                data: 'product_status'
                            },
                        ],
                        "columnDefs": [{
                                "targets": 9,
                                "render": function(data, type, row, meta) {
                                        return row.status ==0?`<div class="dropdown">
                                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                                        <i class="dw dw-more"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                        <a class="dropdown-item" onclick="changeStatus(${row.product_retail_assign_log_id},1)"><i class="dw dw-edit2"></i> Aprroved</a>
                                                        <a class="dropdown-item" onclick="changeStatus(${row.product_retail_assign_log_id},2)"><i class="dw dw-edit2"></i> Returned</a>

                                                        </div>
                                                </div>`:'';
                                    
                                }

                            },
                            {
                                "targets": 6,
                                "render": function(data, type, row, meta) {

                                    return (row.status == 0) ? "Pending" : ((row.status == 1)  ? "Approved" : "Rejected");

                                }

                            },
                            { "orderable": false, "targets": 0 }
                        ],
                        'aaSorting': [[1, 'asc']] ,
                        "order": [[0, "desc" ]]
                    });

                });

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
                        product_status:status
                    },
                    success: function(data) {
                        console.log(data)
                        window.location.reload();
                    }
                });

            }
    </script>

@endsection
