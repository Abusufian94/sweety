<!DOCTYPE html>
<html>
<head>
  <!-- Basic Page Info -->
  

  <meta charset="utf-8">
  <title>Sweety</title>

  <!-- Site favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('deskapp/vendors/images/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('deskapp/vendors/images/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('deskapp/vendors/images/favicon-16x16.png')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('deskapp/src/plugins/sweetalert2/sweetalert2.css')}}">
  <!-- Mobile Specific Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

   <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <link rel="stylesheet" type="text/css" href="{{ asset('deskapp/vendors/styles/core.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('deskapp/vendors/styles/icon-font.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('deskapp/vendors/styles/style.css')}}">

 
</head>
<body class="login-page">
  <div class="login-header box-shadow">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="brand-logo">
        <a href="{{url('/')}}">
          <img src="{{ asset('deskapp/vendors/images/deskapp-logo.svg')}}" alt="">sweety
        </a>
      </div>
      <div class="login-menu">
        <ul>

        </ul>
      </div>
    </div>
  </div><!-- Main Content -->
  <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6 col-lg-7">
         <!--  <img src="{{ asset('deskapp/vendors/images/login-page-img.png')}}" alt=""> -->
        </div>
        <div class="col-md-6 col-lg-5">
          <div class="login-box bg-white box-shadow border-radius-10">
            <div class="login-title">
              <h2 class="text-center text-primary">Login To Sweety</h2>
            </div>


              <div class="input-group custom">
                <input type="email" class="form-control form-control-lg" placeholder="email" name="email" id="email" required>
                <div class="input-group-append custom">
                  <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                </div>
              </div>
              <div class="input-group custom">
                <input type="password" class="form-control form-control-lg" placeholder="**********" name="password" id="password">
                <div class="input-group-append custom" required>
                  <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <div class="input-group mb-0">
                    <!--
                      use code for form submit
                      <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
                    -->
                    <button class="btn btn-primary btn-lg btn-block" href="#" id="onsign">Sign In</button>
                    <span class="load"></span>
                  </div>

                </div>
              </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- js -->
  <script src="{{ asset('deskapp/vendors/scripts/core.js')}}"></script>
  <script src="{{ asset('deskapp/vendors/scripts/script.min.js')}}"></script>
  <script src="{{ asset('deskapp/vendors/scripts/process.js')}}"></script>
  <script src="{{ asset('deskapp/vendors/scripts/layout-settings.js')}}"></script>
  <script src="{{ asset('deskapp/src/plugins/sweetalert2/sweetalert2.all.js')}}"></script>
  <script src="{{ asset('deskapp/src/plugins/sweetalert2/sweet-alert.init.js')}}"></script>
</body>
</html>



<script>

    $(document).ready(function(){
      var x = localStorage.getItem("loginUser");
      console.log(x)
      

        x = JSON.parse(x);
           if(x && x.role===1)
           {
             window.location.replace("{{ url('/admin/dashboard') }}");
           }
           if(x && x.role===2)
           {
             window.location.replace("{{ url('/retail/dashboard') }}");
           }

             if(x && x.role===3)
          {
            window.location.replace("{{ url('/warehouse/dashboard') }}");
          }




$('#onsign').click(function(){
  document.getElementById("onsign").disabled = true;
  var email = $('#email').val();
  var password = $('#password').val();
 
   
  
  $('.load').html(`<img src="{{url('/loaders.gif')}}" style="height:30px"> <strong id="nd" style="color:orange"><i>Loading ....</i></strong>`);


 $.ajax({
     headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
             url: "{{ url('api/login') }}",
             method: 'post',
             data: {
                email: email,
                 password: password,


             },

             success: function(data)
             {
                // alert(data);
             // var perform= data.changedone;
             var x = data.success

             $('.load').html(`<strong id="nd" style="color:green"><i class="icon-copy fa fa-check" aria-hidden="true"></i><i>Success: You are logeed in</i></strong> `);


    localStorage.setItem("loginUser", JSON.stringify(data.success));
    localStorage.setItem("unAuthorizedMessage", " Sorry, You are not authorized");
    localStorage.setItem("loggedInMessage", " welcome back to the Sweety");
    setCookie('loginUser',data.success.role,'10');
    

   if(x!=null && x.token)
      {
          if(x.role===1)
           {
             window.location.replace("{{ url('/admin/dashboard') }}");
           }
          if(x.role===2)
           {

             window.location.replace("{{ url('/retail/dashboard') }}");
           }
          if(x.role==3)
          {
            window.location.replace("{{ url('/warehouse/dashboard') }}");
          }



      }
     //  alert(perform.product_name);
               // jQuery('.alert').html(result.success);
             },
             error: function (request, status, error) {


         document.getElementById("onsign").disabled = false;
         var errorMsg= ` <strong id="nd" style="color:red"><i class="icon-copy fa fa-warning" aria-hidden="true"></i><i>${request.responseJSON.error}</i></strong>`
          $('.load').html(errorMsg);
           swal(
                {
                    position: 'top-end',
                    type: 'error',
                    
                    title: 'Oops...',
                    text: request.responseJSON.error,
                    showConfirmButton: false,
                    timer: 3000
                }
            );

    }



});


});
})
      function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

</script>
