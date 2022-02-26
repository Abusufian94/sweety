@extends('layouts.retailer')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
      

            

            <div class="card-box mb-30">
                <h2 class="h4 pd-20"> Retail Product List</h2>
                <table id="example1" class="table nowrap responsive">
                    <thead>
                        <tr>
                            <th class="all">SL</th>
                            <th class="all"> Image</th>
                            <th class="all"> Name</th>
                            <th class="all"> Unit</th>
                            <th class="all"> Stock</th>
                            <th class="all"> Price</th>
                            <th class="all  datatable-nosort">Store</th>
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
            var i=1;


             $('#example1').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                "searching": true,
                "iDisplayLength": 100,
                "lengthMenu": [[100, 250, 500], [100, 250, 500]],
              "ajax": {
                              "url": "{{ route('retail.assign.products') }}",
                            "type": "GET",
                             headers: {
                    'Authorization': 'Bearer ' + x.token
                },
                        },
              "columns": [
                {
                    "data": "id",       
                    render: function (data, type, full, meta) {
                      
                      return i++;
                },},
               
                {
                    "data": "product_image",       
                    render: function (data, type, full, meta) {
                        return    `<div>
                                            <img src="{!! asset('documents/${data}') !!}" />
                                    </div>`;
                    }
                },
                 {
                    "data": "product_name",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                {
                    "data": "product_unit",       
                    render: function (data, type, full, meta) {
                        console.log(full)
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
                    "data": "product_price",       
                    render: function (data, type, full, meta) {
                        return  data;
                    }
                },
                 {
                    "data": "retail_name",       
                    render: function (data, type, full, meta) {
                        return  `${full.retail_name}(${full.street_name})`;
                    }
                },

                 
                
              ],
          });




                });



      
    </script>

@endsection
