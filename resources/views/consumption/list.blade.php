@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">



            <div class="card-box mb-30">
                <h2 class="h4 pd-20">Consumption Entries</h2>
                <table id='example' class="data-table table nowrap responsive example">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Stock</th>
                            <th>Product</th>
                            <th>Updated By User</th>
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

            apiCall("{{ url('api/v1/consumption/all/') }}", "Get")
                .then(function(data) {

                    console.log(data.data.data)
                    $('#example').dataTable({
                        processing: true,
                        data: data.data.data,
                        destroy: true,
                        data: data.data.data,
                        columns: [{
                                data:  null,"sortable": false, 
									render: function (data, type, row, meta) {
												return meta.row + meta.settings._iDisplayStart + 1;
									}
                            },
                            {
                                data: 'raw.raw_name'
                            },
                            {
                                data: 'unit'
                            },
                            {
                                data: 'stock'
                            },
                            {
                                data: 'product_id'
                            },
                            {
                                data: 'users.name'
                            },
                        ]
                    });

                });
        });
    </script>

@endsection
