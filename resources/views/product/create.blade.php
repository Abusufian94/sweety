@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">

            <div class="pd-20 card-box mb-30">
                <form id="myform" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Product Name<small
                            style="color:red">*</small></label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" type="text" name="product_name" id="product_name" required="required"
                            placeholder="Name"  />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Product Unit<small
                            style="color:red">*</small></label>
                    <div class="col-sm-12 col-md-10" style="width:100%;">

                        <select 
                            name="product_unit" id="product_unit" class="form-control"  required="required">
                            <option value="" disabled selected>Select Unit</option>
                            <option value="kg">KG</option>
                            <option value="mg">Mg</option>
                            <option value="li">Litre</option>
                            <option value="ml">Mili Liter</option>
                            <option value="pcs">Pcs</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Select Raw Material<small
                            style="color:red">*</small></label>
                    <div class="col-sm-12 col-md-10" style="width:100%;">

                        <select  data-width="100%" multiple name="raw" required="required"
                            id="raw" class="form-control"   onchange="get_raw(this)">
                            <option value="" disabled>Select Raw Material</option>
                        </select>
                    </div>
                </div>

                <div id="outputArea"></div>


                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Product Quantity<small
                            style="color:red">*</small></label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" type="text" placeholder="Quantity" name="product_quantity"
                            id="product_quantity" required="required" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Product Price<small
                            style="color:red">*</small></label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" type="text" name="product_price" placeholder="Price" required="required"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-2 col-form-label">Product Image</label>
                    <div class="col-sm-12 col-md-10">
                        <input class="form-control" type="file" name="product_image" id="product_image" required="required" />
                    </div>
                </div>
                <div class="form-group row">
                    {{-- <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label> --}}
                    <div class="col-sm-12 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Submit" />
                    </div>
                </div>
            </form>

            </div>
        </div>
    </div>
    
    </div>
    </div>
    <script src="{{ asset('js/jquery-min.js') }}"></script>
    <script>
        $(document).ready(function() {

            var x = localStorage.getItem("loginUser");

            x = JSON.parse(x);
            if (x == null) {
                window.location.replace("{{ url('/') }}");
            }
            if (!x.token && x.role != 1) {
                localStorage.setItem("unAuthorized", " Sorry, You are not authorized");
                window.location.replace("{{ url('/') }}");
            } else {
                swal({
                    position: 'top-end',
                    type: 'success',
                    title: localStorage.getItem("loggedInMessage"),
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            loadattribute();
            $("#myform").validate({
                rules: {
                    product_unit: {
                        required: true
                    },
                    product_price: {
                        required: true
                    },
                    product_name: {
                        required: true
                    },
                    product_quantity: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    const obj = $(form).serializeArray();
                    const token = JSON.parse(localStorage.getItem('loginUser'));
                    const user_id = token.id;


                    var raw_name = $("#raw_name").val();
                    var unit = $('[name="unit"]').val();
                    var stock = $('[name="stock"]').val();
                    var price = $('[name="price"]').val();

                    var form_data = new FormData();
                    form_data.append("raw_name", raw_name);
                    form_data.append("unit", unit);
                    form_data.append("stock", stock);
                    form_data.append("price", price);
                    form_data.append("user_id", user_id);


                    // const obj = $(form).serializeArray();
                    // const token = JSON.parse(localStorage.getItem('loginUser'));
                    // const user_id = token.id;


                    var product_name = $("#product_name").val();
                    var product_unit = $('[name="product_unit"]').val();
                    var product_quantity = $('[name="product_quantity"]').val();
                    var product_price = $('[name="product_price"]').val();
                    var product_image = $('[name="product_image"]').val();

                    var form_data = new FormData(form); 
                    form_data.append("product_name", product_name);
                    form_data.append("product_unit", product_unit);
                    form_data.append("product_quantity", product_quantity);
                    form_data.append("product_price", product_price);
                    form_data.append("user_id", user_id);
                 
                     var product_price = $('[name="product_price"]').val();
                    $.ajax({
                        url: "{{ url('api/v1/pro/create') }}",
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token.token
                        },
                        type: form.method,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json", // what to expect back from the server
                        data: form_data,
                        success: function(response) {
                            $(form)[0].reset()
                            window.location = "{{ route('product.home') }}"
                        }
                    });

                }
            })



        });

        async function loadattribute() {
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const response = await $.ajax({
                url: "{{ route('rawstock.rawlist') }}",
                type: 'get',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token.token
                },
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json", // what to expect back from the server
                success: function(response) {
                    $('#raw').empty();

                    const data = response.data;
                    let html = '';
                    for (var i = 0; i < data.length; i++) {
                        console.log(data[i].raw_id)
                        html +=
                            `<option value="${data[i].raw_id}">${data[i].raw_name}</option> `;

                    }

                    $('#raw').append(
                        `<option value="" disabled>Select Raw Material</option>${html}`);

                }
            });

        }


        function get_raw(select)
        {
           // var result = [];
            var options = select && select.options;
            var html = '';
           
            $.each(options,function(index,item){
           
                if(item.selected)
                {
                  let names = $(item).text();
                  html +=  `<label>${names}</label> 
                                <input type ="hidden" name ="raw_id[]" value ="${item.value}"/>
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">Raw Unit</label>
                                        <div class="col-sm-12 col-md-10">
                                            <select name='unit[]'  class="form-control" required placeholder="Select">
                                                <option value="" disabled>Select Unit</option>
                                                <option value="kg">KG</option>
                                                <option value="mg">Mg</option>
                                                <option value="li">Litre</option>
                                                <option value="ml">Mili Liter</option>
                                                <option value="pcs">Pcs</option>
                                            </select>
                                        </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">Raw Stock</label>
                                        <div class="col-sm-12 col-md-10">
                                    <input class='form-control' type='text' placeholder='Stock' name='stock[]' />
                                    </div>
                                </div>`;
                                
                }
            });
            $('#outputArea').append(html);
        }
    </script>

@endsection
