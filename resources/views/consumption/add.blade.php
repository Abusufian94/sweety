@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">

            <div class="pd-20 card-box mb-30">

                <form id="myform" method="POST">
                    <div class="form-group row">
                            <label class="col-sm-12 col-md-2 col-form-label">Select Raw Material<small
                                    style="color:red">*</small></label>
                            <div class="col-sm-12 col-md-10" style="width:100%;">

                                <select  data-width="100%"  name="raw_id" id="raw_id" required="required"
                                     class="form-control">
                                    <option value="">Select Raw Material</option>
                                </select>
                            </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Unit<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">

                            <select name="unit" id="unit" class="form-control" required>
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
                        <label class="col-sm-12 col-md-2 col-form-label">Stock<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" placeholder="Stock" name="stock" id="stock" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{-- <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label> --}}
                        <div class="col-sm-12 col-md-10">
                            <input class="btn btn-primary" type="submit" value="Submit">
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
                    raw_id: {
                        required: true
                    },
                    unit: {
                        required: true
                    },
                    stock: {
                        required: true
                    }
                },

                submitHandler: function(form) {
                    const obj = $(form).serializeArray();
                    const token = JSON.parse(localStorage.getItem('loginUser'));
                    const user_id = token.id;


                    var raw_id = $("#raw_id").val();
                    var unit = $('[name="unit"]').val();
                    var stock = $('[name="stock"]').val();

                    var form_data = new FormData(form);
                    form_data.append("user_id", user_id);



                    $.ajax({
                        url: "{{ url('api/v1/cn/add') }}",
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token.token
                        },
                        type: form.method,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json", // what to expect back from the server
                        data: form_data,
                        success: function(response) {
                            $(form)[0].reset()
                            window.location = "{{ route('consumption.home') }}"
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

                    $('#raw_id').append(
                        `<option value="" disabled>Select Raw Material</option>${html}`);

                }
            });

        }
    </script>

@endsection
