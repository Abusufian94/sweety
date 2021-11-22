<!DOCTYPE html>
<html>
<head>
  <!-- Basic Page Info -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <meta charset="utf-8">
  <title>Sweety</title>

  <!-- Site favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('deskapp/vendors/images/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('deskapp/vendors/images/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('deskapp/vendors/images/favicon-16x16.png')}}">

  <!-- Mobile Specific Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

   <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('deskapp/vendors/styles/core.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('deskapp/vendors/styles/icon-font.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('deskapp/vendors/styles/style.css')}}">

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-119386393-1');
  </script>
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
                <input type="email" class="form-control form-control-lg" placeholder="email" name="email" id="email">
                <div class="input-group-append custom">
                  <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                </div>
              </div>
              <div class="input-group custom">
                <input type="password" class="form-control form-control-lg" placeholder="**********" name="password" id="password">
                <div class="input-group-append custom">
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
</body>
</html>



<script>

    $(document).ready(function(){

$('#onsign').click(function(){
  alert("ok");


  var email = $('#email').val();
  var password = $('#password').val();
 

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

             success: function(data){
                // alert(data);
             // var perform= data.changedone;
  console.log(dataLayer.success);
    localStorage.setItem("loginUser", JSON.stringify(data.success));
    localStorage.setItem("unAuthorizedMessage", " Sorry, You are not authorized");
    localStorage.setItem("loggedInMessage", " welcome back to the Sweety");
    setCookie('token',data.success.token,'10');

   window.location.replace("{{ url('/profile') }}");
     //  alert(perform.product_name);
               // jQuery('.alert').html(result.success);
             }

             });



});
});
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