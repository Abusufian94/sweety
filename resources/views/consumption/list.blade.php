@extends('layouts.'.$extend)

@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">


            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <div class="dropdown">
                            <a class="btn btn-primary " href="{{ route('consumption.create') }}">
                                Create
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-box mb-30">
                <h2 class="h4 pd-20">Consumption Entries</h2>
                <table id='example' class="table nowrap responsive example">
                    <thead>
                        <tr>
                            <th class="all">SL</th>
                            <th class="all">Name</th>
                            <th class="all">Unit</th>
                            <th class="all">Stock</th>
                            <th class="all">Created By User</th>
                            <th class="all">Created At</th>
                            <th class="all">Updated At</th>
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
                        data: 'name'
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
                    
                ],
                "order": [[0, "desc" ]]
            });

            // });
        });
    </script>

@endsection
