@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
            <input type="hidden" id="id" value="{{ $id }}" />
            <!-- 	<div class="card-box pd-20 height-100-p mb-30">
                    <div class="row align-items-center">
                     <div class="col-md-4">
                      <img src="{{ asset('deskapp/vendors/images/banner-img.png') }}" alt="">
                     </div>
                     <div class="col-md-8">
                      <h4 class="font-20 weight-500 mb-10 text-capitalize">
                       Welcome back <div class="weight-600 font-30 text-blue">Nishan Paul</div>
                      </h4>
                      <p class="font-18 max-width-600"></p>
                     </div>
                    </div>
                   </div> -->
            {{-- <div class="row">
				<div class="col-md-4 col-sm-12 mb-30 ">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart"></div>
							</div>
							<div class="widget-data">
								<div class="h2 mb-0"><span class="micon dw dw-house-11"></span>1</div>
								<div class="weight-600 font-14">KALAMANDIR</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-12 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart2"></div>
							</div>
							<div class="widget-data">
								<div class="h2 mb-0"><span class="micon dw dw-house-11"></span>2</div>
								<div class="weight-600 font-14">KALAMANDIR</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4  col-sm-12 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="progress-data">
								<div id="chart4"></div>
							</div>
							<div class="widget-data">
								<div class="h2 mb-0"><span class="micon dw dw-factory1"></span></div>
								<div class="weight-600 font-14">WARHOUSE</div>
							</div>
						</div>
					</div>
				</div>
			</div> --}}



            <div class="pd-20 card-box mb-30">

                <form id="myform" method="POST">
                     @method('PATCH') 

                    <input type="hidden" name="raw_id" id="raw_id" value="{{ $id }}" />
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Name<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" id="raw_name" name="raw_name" placeholder="Name"
                                required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Unit<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">

                            <select name="unit" id="unit" class="form-control" required>
                                <option value="">Select Unit</option>
                                <option value="kd">KD</option>
                                <option value="ua">UA</option>
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
                        <label class="col-sm-12 col-md-2 col-form-label">Price<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" name="price" placeholder="Price" id="price" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{-- <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label> --}}
                        <div class="col-sm-12 col-md-10">
                            <input class="btn btn-primary" type="submit" value="Submit">
                        </div>
                    </div>
                </form>

                <div class="footer-wrap pd-20 mb-20 card-box">
                    De By <a href="https://github.com/dropways" target="_blank">Ankit Hingarajiya</a>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/jquery-min.js') }}"></script>

        <script>
            $(document).ready(function() {

                $("#myform").validate({
                    rules: {
                        unit: {
                            required: true
                        },
                        price: {
                            required: true
                        },
                        raw_name: {
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
                        var raw_name = $("#raw_name").val();
                        var unit = $('[name="unit"]').val();
                        var stock = $('[name="stock"]').val();
                        var price = $('[name="price"]').val();

                        var form_data = new FormData();
                        form_data.append("raw_id", raw_id);
                        form_data.append("raw_name", raw_name);
                        form_data.append("unit", unit);
                        form_data.append("stock", stock);
                        form_data.append("price", price);
                        form_data.append("user_id", user_id);

                        $.ajax({
                            url: "{{ url('api/v1/raw/update') }}",
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + token.token
                            },
                            type: 'POST',
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json", // what to expect back from the server
                            data: form_data,
                            success: function(response) {
                                $(form)[0].reset();
                                window.location = "{{ route('stock.home') }}"
                            }
                        });

                    }
                })
                let id = $("#id").val();
                apiCall("{{ url('api/v1/raw/details') }}/" + id, "Get")
                    .then(function(data) {
                        console.log(data)
                        $("#raw_name").val(data.data.raw_name)
                        $("#unit").val(data.data.unit)
                        $("#price").val(data.data.price)
                        $("#stock").val(data.data.stock)
                    });
            })
        </script>


    @endsection
