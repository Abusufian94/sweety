@extends('layouts.admin')
@section('content')
    <div class="main-container">
        <div class="pd-ltr-20">
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
                {{-- <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Default Basic Forms</h4>
                        <p class="mb-30">All bootstrap element classies</p>
                    </div>
                    <div class="pull-right">
                        <a href="#basic-form1" class="btn btn-primary btn-sm scroll-click" rel="content-y"
                            data-toggle="collapse" role="button"><i class="fa fa-code"></i> Source Code</a>
                    </div>
                </div> --}}
                <form id="myform" method="POST">
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Name<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="text" name="name" placeholder="Name" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Email<small style="color:red">*</small></label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" type="email" name="email" placeholder="Email" required/>
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
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <input type="hidden" name='roles' value="3"/>
                    <div class="form-group row">
                        {{-- <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label> --}}
                        <div class="col-sm-12 col-md-10">
                            <input class="btn btn-primary" type="submit" value="Submit">
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Search</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" placeholder="Search Here" type="search">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Email</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" value="bootstrap@example.com" type="email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">URL</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" value="https://getbootstrap.com" type="url">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Telephone</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" value="1-(111)-111-1111" type="tel">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Password</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" value="password" type="password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Number</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" value="100" type="number">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-datetime-local-input" class="col-sm-12 col-md-2 col-form-label">Date and
                            time</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control datetimepicker" placeholder="Choose Date anf time" type="text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Date</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control date-picker" placeholder="Select Date" type="text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Month</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control month-picker" placeholder="Select Month" type="text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Time</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control time-picker" placeholder="Select time" type="text">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Select</label>
                        <div class="col-sm-12 col-md-10">
                            <select class="custom-select col-12">
                                <option selected="">Choose...</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Color</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" value="#563d7c" type="color">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-2 col-form-label">Input Range</label>
                        <div class="col-sm-12 col-md-10">
                            <input class="form-control" value="50" type="range">
                        </div>
                    </div> --}}
                </form>
                <div class="collapse collapse-box" id="basic-form1">
                    <div class="code-box">
                        <div class="clearfix">
                            <a href="javascript:;" class="btn btn-primary btn-sm code-copy pull-left"
                                data-clipboard-target="#copy-pre"><i class="fa fa-clipboard"></i> Copy Code</a>
                            <a href="#basic-form1" class="btn btn-primary btn-sm pull-right" rel="content-y"
                                data-toggle="collapse" role="button"><i class="fa fa-eye-slash"></i> Hide Code</a>
                        </div>
                        <pre><code class="xml copy-pre" id="copy-pre">

                                    <form>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Text</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control" type="text" placeholder="Johnny Brown">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Search</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control" placeholder="Search Here" type="search">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Email</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control" value="bootstrap@example.com" type="email">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">URL</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control" value="https://getbootstrap.com" type="url">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Telephone</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control" value="1-(111)-111-1111" type="tel">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Password</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control" value="password" type="password">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Number</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control" value="100" type="number">
      </div>
     </div>
     <div class="form-group row">
      <label for="example-datetime-local-input" class="col-sm-12 col-md-2 col-form-label">Date and time</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control datetimepicker" placeholder="Choose Date anf time" type="text">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Date</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control date-picker" placeholder="Select Date" type="text">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Month</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control month-picker" placeholder="Select Month" type="text">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Time</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control time-picker" placeholder="Select time" type="text">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Select</label>
      <div class="col-sm-12 col-md-10">
       <select class="custom-select col-12">
        <option selected="">Choose...</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
       </select>
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Color</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control" value="#563d7c" type="color">
      </div>
     </div>
     <div class="form-group row">
      <label class="col-sm-12 col-md-2 col-form-label">Input Range</label>
      <div class="col-sm-12 col-md-10">
       <input class="form-control" value="50" type="range">
      </div>
     </div>
    </form>
           </code></pre>
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
                window.location.replace("{{ url('/login') }}");
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
                url: "{{url('api/v1/warehose/create')}}",
                headers: {
                    'Accept':'application/json',
                    'Authorization':'Bearer '+token.token
                     },
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                   $(form)[0].reset()
                   window.location ="{{route('warehouse.home')}}"
                }
             });

       }
      })

});
    </script>

@endsection
