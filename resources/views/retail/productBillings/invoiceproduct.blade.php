@extends('layouts.'.$extend)
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
           

            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        
                    </div>
                    
                </div>
            </div>

            <div class="card-box mb-30">
                <h2 class="h4 pd-20">Invoice Product</h2>
                <table id="example1" class="table nowrap responsive">
                    <thead>
                        <tr>
                            <th class="all">SL</th>
                            <th class="all">Invoice Number</th>
                            <th class="all"> Name</th>
                            <th class="all">Unit</th>
                            <th class="all"> Quantity</th>
                            <th class="all"> Price</th>
                            <th class="all">Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th class="all datatable-nosort" ></th>

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
                            "url": "{{ url('/api/v1/invoice/sold/products') }}/{{$id}}",
                            "type": "GET",
                        },
                        destroy: true,
                        columns: [{
                                data: 'id'
                            },
                             {
                                data: 'invoice_number'
                            },
                            {
                                data: 'product_name'
                            },
                           
                            {
                                data: 'product_unit'
                            },
                            {
                                data: 'quantity'
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



                                    return `<div class="dropdown" style="display:none;">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list" >


                                        </div>
                                    </div>`
                                }

                            },
                            {
                                "targets": 6,
                                "render": function(data, type, row, meta) {

                                    return row.status==1?'Active':'Active';

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
