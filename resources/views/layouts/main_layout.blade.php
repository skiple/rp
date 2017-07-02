<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rentuff People</title>

    <!-- add jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    </script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>

    <!-- datepicker css js -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- CSS And JavaScript -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Rentuff People</a>
            </div>
            <ul class="nav navbar-nav">
            @if(isset(Auth::user()->email))
                <!-- a already has default padding top 14px. so if padding-top isn't declared here, the padding will be doubled (28px) -->
                <li><a style="padding-top:0px" href="/logout">logout</a></li>
            @else
                <li class="login">Log In</li>
            @endif
            </ul>
        </div>
    </nav>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
      <!-- Modal content -->
      <div class="modal-content">
        <span class="close">&times;</span>
        <br><br>
        <form id="login-form" action="{{ route('sign_in') }}" method="post" role="form" style="display: block; font-family:Arial !important;">
            <div class="form-group">
                <input type="text" name="email" tabindex="1" class="form-control" placeholder="Email Address" value="">
            </div>
            <div class="form-group">
                <input type="password" name="password" tabindex="2" class="form-control" placeholder="Password">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                        <input type="submit" class="form-control btn btn-login" value="Log In">
                    </div>
                </div>
            </div>
        </form>
        <div class="parent">
            <p class="left">
                Don't have an account yet?
            </p>
            <p id="signup" class="right">
                Sign Up
            </p>
        </div>
      </div>
    </div>

    <!-- Signup Modal -->
    <div id="signupModal" class="modal">
        <!-- Modal content -->
      <div class="modal-content">
        <span class="close">&times;</span>
        <br><br>
        <form id="signup-form" action="{{ route('sign_up') }}" method="post" role="form" style="display: block; font-family:Arial !important;">
            <div class="form-group">
                <input type="text" name="first_name" tabindex="1" class="form-control" placeholder="First Name" value="">
            </div>
            <div class="form-group">
                <input type="text" name="last_name" tabindex="1" class="form-control" placeholder="Last Name" value="">
            </div>
            <div class="form-group">
                <input type="text" name="email" tabindex="1" class="form-control" placeholder="Email Address" value="">
            </div>
            <div class="form-group">
                <input type="text" name="phone" tabindex="1" class="form-control" placeholder="Phone Number" value="">
            </div>
            <div class="form-group">
                <input type="text" id="datepicker" name="birthday" tabindex="1" class="form-control" placeholder="Birthday" value="">
            </div>
            <div class="form-group">
                <input type="password" name="password" tabindex="2" class="form-control" placeholder="Create Password">
            </div>
            <div class="form-group">
                <input type="password" name="password_confirmation" tabindex="2" class="form-control" placeholder="Confirm Password">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                        <input type="submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
                    </div>
                </div>
            </div>
        </form>
        <div class="parent">
            <p class="left">
                Already have an account?
            </p>
            <p class="login right">
                Log In
            </p>
        </div>
      </div>
    </div>

    <div class="container">
        @yield('content')
    </div>
</body>
<script>
    // Get the modal
    var loginModal = document.getElementById('loginModal');
    var signupModal = document.getElementById('signupModal');

    // Get the login menu that opens the modal
    var loginMenu = document.getElementsByClassName("login")[0];
    var loginMenu_su = document.getElementsByClassName("login")[1]; //this is login menu from signup
    var signupMenu = document.getElementById("signup");

    // Get the <span> element that closes the modal
    var close = document.getElementsByClassName("close")[0];
    var close_su = document.getElementsByClassName("close")[1]; //this is close menu from signup

    // When the user clicks the button, open the modal 
    loginMenu.onclick = function() {
        loginModal.style.display = "block";
    }

    loginMenu_su.onclick = function() {
        loginModal.style.display = "block";
        signupModal.style.display = "none";
    }

    signupMenu.onclick = function(){
        loginModal.style.display = "none";
        signupModal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    close.onclick = function() {
        loginModal.style.display = "none";
        signupModal.style.display = "none";
    }

    // When the user clicks on <span> (x), close the modal
    close_su.onclick = function() {
        loginModal.style.display = "none";
        signupModal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
        else if (event.target == signupModal) {
            signupModal.style.display = "none";
        }

    }
</script>
<script>
    $( function() {
        $( "#datepicker" ).datepicker({
            dateFormat: "dd MM yy"
        });
    } );
</script>
</html>