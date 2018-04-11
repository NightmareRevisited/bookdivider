<!DOCTYPE html>
<html lang="en">

<style>
    .col-center-block {
        float: none;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BookSepper</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Cabin:700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/grayscale.min.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Start BookSepper</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#login">Login</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Intro Header -->
    <header class="masthead">
      <div class="intro-body">
        <div class="container">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <h1 class="brand-heading">BookSepper</h1>
              <p class="intro-text">A free, intelligent, book auto-supervisory system.
                <br>Created by TheVile.</p>
              <a href="#about" class="btn btn-circle js-scroll-trigger">
                <i class="fa fa-angle-double-down animated"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- About Section -->
    <section id="about" class="content-section text-center">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <h2>About BookSepper</h2>
            <p>BookSepper is a free book auto-supervisory system created by TheVile. It can help you distinguish the books will be saved in database, simply sign up on
              <a class="nav-link js-scroll-trigger" href="#login">the registration page</a>. The system is open source, and you can use it for any purpose, personal or commercial.</p>
            <p>BookSepper includes full HTML, CSS, PHP and Python files along with SASS and LESS files for easy experience!</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Download Section -->
    <section id="login" class="download-section content-section text-center">
      <div class="container">
        <div class="col-lg-8 mx-auto">
          <h2>Login</h2>
            <div class="row myCenter">
                <div class="col-xs-6 col-md-4 col-center-block">
                    <form class="form-signin">
                        <label for="username" class="sr-only">Username</label>
                        <input type="text" id="username" class="form-control" placeholder="Username" required autofocus>
                        <div id="usernameResponse"></div>
                        <br/>
                        <label for="inputPassword" class="sr-only">Password</label>
                        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                        <div id="passwordResponse"></div>
                    </form>
                    <br/>
                    <a class="btn btn-default btn-lg" onclick="validate('login')">Login</a>
                    <a class="btn btn-default btn-lg" onclick="validate('signup')">Sign up</a>
                </div>
            </div>
      </div>
    </section>


    <!-- Footer -->
    <footer>
      <div class="container text-center">
        <p>Copyright &copy; TheVile 2018</p>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script>
        function jump(url,username,password) {
            document.write("<form method='post' action='"+url+"' name='hiddenpost' style='display:none'>");
            document.write("<input type=hidden name='username' value='" +username+"'/>" );
            document.write("<input type=hidden name='password' value='" +password+"'/>" );
            document.write("</form>");
            document.hiddenpost.submit();
        }
        function validate(sta) {
            var aj;
            if (window.XMLHttpRequest){
                aj = new XMLHttpRequest();
            }
            else{
                aj = new ActiveXObject("Microsoft.XMLHTTP")
            }
            username = document.getElementById("username").value;
            password = document.getElementById("inputPassword").value;
            aj.onreadystatechange=function(){
                if (aj.readyState == 4 && aj.status == 200){
                    var jsonObj = JSON.parse(aj.responseText);
                    document.getElementById("usernameResponse").innerHTML = jsonObj.usernameResponse;
                    document.getElementById("passwordResponse").innerHTML = jsonObj.passwordResponse;
                    if (String(jsonObj.method) == 'user') {
                        setTimeout("jump('usermain.php',username,password)",3000);
                    }
                    else if (String(jsonObj.method) == 'admin'){
                        setTimeout("jump('adminmain.php',username,password)",3000);
                    }
                }
            }
            if (username.length < 5) {
                document.getElementById("usernameResponse").innerHTML = "<font color='red'>The name must be formed by at least 5 characters.</font>";
            }
            if (password.length < 6) {
                document.getElementById("passwordResponse").innerHTML = "<font color='red'>The password must be formed by at least 6 characters.</font>";
            }
            else {
                aj.open('post', 'validate.php');
                aj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                aj.send("username=" + username + "&password=" + password + "&status="+sta);
            }
        }

    </script>

    <!-- Custom scripts for this template -->
    <script src="js/grayscale.min.js"></script>

  </body>

</html>
