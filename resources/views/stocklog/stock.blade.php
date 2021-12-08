@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">



            <div class="card-box mb-30">
                <h2 class="h4 pd-20">Stocks</h2>
                <table id='example' class="data-table table nowrap responsive example">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Log Type</th>
                            <th>Operation</th>
                        </tr>
                    </thead>

                </table>
            </div>
            <div class="footer-wrap pd-20 mb-20 card-box">
                De By <a href="https://github.com/dropways" target="_blank">Ankit Hingarajiya</a>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery-min.js') }}"></script>
    <script>

    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            apiCall("{{ url('api/v1/log/all/') }}", "Get")
                .then(function(data) {
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

                    $('#example').dataTable({
                        processing: true,
                        data: data.data.data,
                        destroy: true,
                        data: data.data.data,
                        columns: [{
                                data: 'raw_stock_log_id'
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

                });
        });
    </script>

@endsection
