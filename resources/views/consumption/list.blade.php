@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">



            <div class="card-box mb-30">
                <h2 class="h4 pd-20">Consumption Entries</h2>
                <table id='example' class="table nowrap responsive example">
                    <thead>
                        <tr>
                            <th class="all">SL</th>
                            <th class="all">Name</th>
                            <th class="all">Unit</th>
                            <th class="all">Stock</th>
                            <th class="all">Product</th>
                            <th class="all">Updated By User</th>
                        </tr>
                    </thead>

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


            // apiCall("{{ url('api/v1/consumption/all/') }}", "Get")
            //     .then(function(data) {

            //         console.log(data.data.data)
            $('#example').dataTable({
                processing: true,
                serverSide: true,
                bRetrieve: true ,
                "ajax": {
                    "url": "{{ route('consumption.list') }}",
                    "type": "GET",
                },
                destroy: true,
                columns: [{
                        data: 'consumption_id',
                        "sortable": false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'raw_name'
                    },
                    {
                        data: 'unit'
                    },
                    {
                        data: 'stock'
                    },
                    {
                        data: 'product_name'
                    },
                    {
                        data: 'name'
                    },
                ]
            });

            // });
        });
    </script>

@endsection
