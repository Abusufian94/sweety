@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">



            <div class="card-box mb-30">
                <h2 class="h4 pd-20">Stocks</h2>
                <table id='example3' class="table nowrap responsive example">
                    <thead>
                        <tr>
                            <th class="all">SL</th>
                            <th class="all">Name</th>
                            <th class="all">Unit</th>
                            <th class="all">Stock</th>
                            <th class="all">Price</th>
                            <th class="all">Log Type</th>
                            <th class="all">Operation</th>
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

            // apiCall("{{ url('api/v1/log/all/') }}", "Get")
            //     .then(function(data) {
            //             console.log(data.data.data)
            //             var html = ''
            //             $.each(data.data.data, function(index, value) {

            //                 html += `<tr>
        //     <td>${index + 1}</td>
        //      <td>
        //          <h5 class="font-16">${value.raw_name}</h5>

        //      </td>
        //      <td>${value.unit}</td>
        //      <td>${value.stock}</td>
        // 	 <td>${value.price}</td>
        //      <td>${value.log_type}</td>
        //      <td>${value.operation}</td>
        //  </tr>`
            //             });
            //             $("#demo").html(html)

            var x = localStorage.getItem("loginUser");
            x = JSON.parse(x);


            $.ajaxSetup({
                headers: {
                    'Authorization': 'Bearer ' + x.token
                }
            });
            var $table = $('#example3').dataTable({
                processing: true,
                serverSide: true,
                // ajax: "{{ route('stocklog.list') }}",
                // processing: true,
                // data: data.data.data,
                bRetrieve: true ,
                "ajax": {
                    "url": "{{ route('stocklog.list') }}",
                    "type": "GET",
                },
                destroy: true,
                columns: [{
                        data: 'raw_id',
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
                        data: 'price'
                    },
                    {
                        data: 'log_type'
                    },
                    {
                        data: 'operation'
                    }
                ]
            });

            // });
        });
    </script>

@endsection
