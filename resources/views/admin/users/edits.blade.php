@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
           <input type="hidden" id="id" value="{{$id}}"/>
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
                    <input type="hidden" name="w_id" id="w_id" value="{{$id}}"/>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Name<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" id="name" name="name" placeholder="Name" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Email<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="email" id="email" name="email" placeholder="Email" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Password<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="password" placeholder="Password" name="password" id="password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Confirm Password<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password" >
                        </div>
                    </div>
                    <input type="hidden" name='roles' value="3"/>
                    <div class="form-group row">
                        {{-- <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label> --}}
                        <div class="col-sm-12 col-md-10">
                            <input class="btn btn-primary" type="submit" value="Submit"/>
                        </div>
                    </div>

                </form>

           
        </div>
    </div>
    <script src="{{ asset('js/jquery-min.js') }}"></script>

    <script>
        $(document).ready(function(){

        $("#myform").validate({
            rules : {
                password : {
                    minlength : 5
                },
                password_confirmation : {
                    minlength : 5,
                    equalTo : "#password"
                }
            },
        submitHandler: function(form){
            const obj =  $(form).serializeArray();
            const token = JSON.parse(localStorage.getItem('loginUser'));
            $.ajax({
                url: "{{url('api/v1/warehose/update')}}",
                headers: {
                    'Accept':'application/json',
                    'Authorization':'Bearer '+token.token
                     },
                type: 'patch',
                data: $(form).serialize(),
                success: function(response) {

                   window.location ="{{route('warehouse.home')}}"
                }
             });

       }
     })
            let id = $("#id").val();
            apiCall("{{url('api/v1/warehouse')}}/"+id,"Get")
            .then(function(data){
                console.log(data)
                $("#name").val(data.data.name)
                $("#email").val(data.data.email)
                $("#password").val(data.data.password_as)
              //  $("#password").val(data.data.password_as)
            })
        })

    </script>


@endsection
