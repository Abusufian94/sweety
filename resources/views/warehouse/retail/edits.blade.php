@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">

            <div class="pd-20 card-box mb-30">
                <form id="myform" method="post" enctype="multipart/form-data">

                    <input type="hidden" id="id" value="{{ $id }}" />

                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Unit<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10" style="width:100%;">

                            <select name="product_id" id="product_id" class="form-control" required="required">
                                <option value="" disabled selected>Select Product</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Unit<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10" style="width:100%;">

                            <select name="unity" id="unity" disabled class="form-control" required="required">
                                <option value="">Select Unit</option>
                                <option value="kg">KG</option>
                                <option value="mg">Mg</option>
                                <option value="li">Litre</option>
                                <option value="ml">Mili Liter</option>
                                <option value="pcs">Pcs</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Avaliable Quantity<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" placeholder="Quantity" name="aval_quantity"
                                id="aval_quantity" required="required" disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Select Retail User<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10" style="width:100%;">

                            <select name="retail_id" id="retail_id" class="form-control" required="required">
                                <option value="" disabled selected>Select Retail User</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Product Quantity<small
                                style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="number" placeholder="Quantity" name="quantity" min="1" 
                                id="quantity" required="required" />
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
             loadRetailUser();
             loadProduct();
            $("#myform").validate({
                rules: {
                    unity: {
                        required: true
                    },
                    retail_id: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    quantity: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    const obj = $(form).serializeArray();
                    const token = JSON.parse(localStorage.getItem('loginUser'));
                    const user_id = token.id;
                    let unity = $("#unity").val();
                    let id = $("#id").val();

                    var form_data = new FormData(form);
                    form_data.append("user_id", user_id);
                    form_data.append("unity", unity);
                    form_data.append("status", 0);
                    form_data.append("id", id);


                    $.ajax({
                        url: "{{url('api/v1/productretail/update') }}",
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token.token
                        },
                        type: form.method,
                        enctype: 'multipart/form-data',
                        contentType: false,
                        method:'Post',
                        cache: false,
                        processData: false,
                        dataType: "json", // what to expect back from the server
                        data: form_data,
                        success: function(response) {
                            $(form)[0].reset()
                            window.location = "{{ route('warehouse.retail.list') }}"
                        }
                    });

                }
            });

            let id = $("#id").val();
            apiCall("{{ url('api/v1/product-retail-details') }}/" + id, "Get")
                .then(function(data) {

                    $("#id").val(data.data.product_retail_assign_log_id)
                    $("#product_id").val(data.data.product_id).attr('selected','selected');
                    $("#unity").val(data.data.unity).attr('selected','selected');
                    $("#retail_id").val(data.data.retail_id).attr('selected','selected');
                    $("#aval_quantity").val(data.data.products.product_quantity);
                    $("#quantity").val(data.data.quantity);

                });



        });

        async function loadRetailUser() {
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const response = await $.ajax({
                url: "{{ url('api/v1/retail-users') }}" ,
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
                    $('#retail_id').empty();

                    const data = response.data;
                    let html = '';
                    for (var i = 0; i < data.length; i++) {
                        html +=
                            `<option value="${data[i].id}">${data[i].name}</option> `;

                    }

                    $('#retail_id').append(
                        `<option value="">Select Retail User</option>${html}`);

                }
            });

        }

        async function loadProduct() {
            const token = JSON.parse(localStorage.getItem('loginUser'));
            const response = await $.ajax({
                url: "{{ url('api/v1/pro/all') }}" ,
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
                    $('#product_id').empty();

                    const data = response.data;
                    let html = '';
                    for (var i = 0; i < data.length; i++) {
                        html +=
                            `<option value="${data[i].id}" data-unit="${data[i].product_unit}" data-quantity="${data[i].product_quantity}">${data[i].product_name}</option> `;

                    }

                    $('#product_id').append(
                        `<option value="">Select Product</option>${html}`);

                }
            });

        }

        $('#product_id').change(function() {

                let unit = $(':selected', $(this)).data('unit');
                let quantity = $(':selected', $(this)).data('quantity');
                $("#unity").val(unit).attr("selected","selected");
                $("#aval_quantity").val(quantity);
                $("#quantity").attr('max', quantity);

        });

        $("#product_id").trigger("change");

      
    </script>

@endsection
