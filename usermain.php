<!DOCTYPE html>
<html lang="en">
<?php
require_once('mongoconnect.php');
?>
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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>


    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700" rel="stylesheet">

    <!-- Template Styles -->
    <link rel="stylesheet" href="css/font-awesome.min.css">

    <!-- CSS Reset -->
    <link rel="stylesheet" href="css/normalize.css">

    <!-- Milligram CSS minified -->
    <link rel="stylesheet" href="css/milligram.min.css">

    <!-- Main Styles -->
    <link rel="stylesheet" href="css/styles.css">
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
                    Welcome &nbsp;<?php print $_POST['username']; ?>
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
                <div class="row grid-responsive">
                    The System has &nbsp;<strong id="book_num"><?php $book_num = count(find('bookinfo',['catagory'=>['$ne'=>'']])); print $book_num;?></strong>&nbsp; books met the requirement.
                </div>
                <div class="row grid-responsive">
                    <input class="column column-20 col-search" type="text" id="book_search" placeholder="Search Book...">
                    &nbsp;
                    <button class="fa fa-search" onclick="paginate(1)"></button>
                    &nbsp;
                    <button class="fa fa-plus" onclick="book_operation('book_insert')"></button>
                </div>
                <div class="row grid-responsive">
                    <table class="table" id="book_table">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Intro</th>
                        </tr>
                        </thead>
                        <tbody id="book_tbody">
                        <?php
                        $book_display = find('bookinfo',[],['limit'=>10]);
                        $max_page = (int)($book_num/10)+1;
                        foreach ($book_display as $book){
                            print<<<_HTML
                        <tr id="{$book['_id']}">
                            <td contenteditable="true">{$book['title']}</td>
                            <td contenteditable="true">{$book['catagory']}</td>
                            <td contenteditable="true">{$book['author']}</td>
                            <td contenteditable="true">{$book['intro']}</td>
                        </tr>
_HTML;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="row grid-responsive">
                    <div class="column"></div>
                    <div class="column">
                        <style>
                            .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
                                background-color: #35cebe;
                                border-color: #35cebe;
                            }
                            .pagination>li>a, .pagination>li>span {
                                color:#35cebe;
                            }
                        </style>
                        <ul class="pagination" id="pagination">
                            <li class="disabled"><a href="javascript:void(0);"><</a></li>
                            <li class="active" id="active"><a href="javascript:void(0);" onclick="paginate(1)">1</a></li>
                            <li class="disabled" style="display: none"><a>...</a></li>
                            <li><a href="javascript:void(0);" onclick="paginate(2)">2</a></li>
                            <li><a href="javascript:void(0);" onclick="paginate(3)">3</a></li>
                            <li><a href="javascript:void(0);" onclick="paginate(4)">4</a></li>
                            <li class="disabled"><a>...</a></li>
                            <li><a href="javascript:void(0)" onclick="paginate(<?php print $max_page; ?>)"><?php print $max_page; ?></a></li>
                            <li><a href="javascript:void(0);" onclick="paginate('next')">></a></li>
                        </ul>
                    </div>
                </div>
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
                    setTimeout("jump('usermain.html',username,password)",3000);
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

<script>
    function book_operation (type,id=""){
        var aj;
        var admin = document.getElementById('admin').innerText;
        var search = document.getElementById('book_search').value;
        var page = document.getElementById('active').innerText;
        if (window.XMLHttpRequest){
            aj = new XMLHttpRequest();
        }
        else {
            aj = new ActiveXObject('Microsoft.XMLHTTP');
        }
        if (id){
            var title = document.getElementById(id).cells[0].innerHTML;
            var category = document.getElementById(id).cells[1].innerHTML;
            var author = document.getElementById(id).cells[2].innerHTML;
            var intro = document.getElementById(id).cells[3].innerHTML;
            document.getElementById(id+'status').innerHTML = 'Working...';
        }
        aj.onreadystatechange = function () {
            if (aj.readyState ==4 && aj.status == 200){
                var jsonObj = JSON.parse(aj.responseText);
                if (type == 'book_update'){
                    document.getElementById(id).cells[1].innerHTML = jsonObj.category;
                }
                else {
                    document.getElementById('book_tbody').innerHTML = jsonObj.tr_response;
                }
                document.getElementById('book_num').innerHTML = jsonObj.booknum;
                if (id) {
                    document.getElementById(id+'status').innerHTML = "<font color='green'>Success</font>"
                }
            }
        }
        aj.open('post','user_search.php');
        aj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        aj.send('search='+search);
    }

    function paginate(page){
        var aj;
        var target;
        var search = document.getElementById('book_search').value;
        var now = parseInt(document.getElementById('active').innerText);
        if (page == 'previous') {
            target = now-1;
        }
        else if(page == 'next') {
            target = now+1;
        }
        else {
            target = page;
        }
        var max_page = parseInt(parseInt(document.getElementById('book_num').innerText)/10)+1;
        var page_li = document.getElementById('pagination').getElementsByTagName('li');
        for (var i=0;i<9;i++){
            page_li[i].removeAttribute('class');
            page_li[i].removeAttribute('id');
            page_li[i].removeAttribute('style');
        }
        page_li[0].getElementsByTagName('a')[0].setAttribute('onclick',"paginate('previous')");
        if (target == 1) {
            page_li[0].className = 'disabled';
            page_li[0].getElementsByTagName('a')[0].removeAttribute('onclick');
            page_li[1].className = 'active';
            page_li[1].setAttribute('id','active');
            page_li[2].setAttribute('style','display: none');
            page_li[3].getElementsByTagName('a')[0].innerText = 2;
            page_li[3].getElementsByTagName('a')[0].setAttribute('onclick','paginate(2)');
            page_li[4].getElementsByTagName('a')[0].innerText = 3;
            page_li[4].getElementsByTagName('a')[0].setAttribute('onclick','paginate(3)');
            page_li[5].getElementsByTagName('a')[0].innerText = 4;
            page_li[5].getElementsByTagName('a')[0].setAttribute('onclick','paginate(4)');
            page_li[6].className = 'disabled';
        }
        else if (target == 2){
            page_li[2].setAttribute('style','display: none');
            page_li[3].className = 'active';
            page_li[3].setAttribute('id','active');
            page_li[3].getElementsByTagName('a')[0].innerText = 2;
            page_li[3].getElementsByTagName('a')[0].setAttribute('onclick','paginate(2)');
            page_li[4].getElementsByTagName('a')[0].innerText = 3;
            page_li[4].getElementsByTagName('a')[0].setAttribute('onclick','paginate(3)');
            page_li[5].getElementsByTagName('a')[0].innerText = 4;
            page_li[5].getElementsByTagName('a')[0].setAttribute('onclick','paginate(4)');
            page_li[6].className = 'disabled';
        }
        else if (target == 3){
            page_li[2].setAttribute('style','display: none');
            page_li[4].className = 'active';
            page_li[4].setAttribute('id','active');
            page_li[3].getElementsByTagName('a')[0].innerText = 2;
            page_li[3].getElementsByTagName('a')[0].setAttribute('onclick','paginate(2)');
            page_li[4].getElementsByTagName('a')[0].innerText = 3;
            page_li[4].getElementsByTagName('a')[0].setAttribute('onclick','paginate(3)');
            page_li[5].getElementsByTagName('a')[0].innerText = 4;
            page_li[5].getElementsByTagName('a')[0].setAttribute('onclick','paginate(4)');
            page_li[6].className = 'disabled';
        }
        else if (target > 3 && target < max_page-2){
            page_li[2].className = 'disabled';
            page_li[4].className = 'active';
            page_li[4].setAttribute('id','active');
            page_li[3].getElementsByTagName('a')[0].innerText = target-1;
            page_li[3].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(target-1)+')');
            page_li[4].getElementsByTagName('a')[0].innerText = target;
            page_li[4].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+target+')');
            page_li[5].getElementsByTagName('a')[0].innerText = target+1;
            page_li[5].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(target+1)+')');
            page_li[6].className = 'disabled';
        }
        else if (target == max_page-2){
            page_li[2].className = 'disabled';
            page_li[4].className = 'active';
            page_li[4].setAttribute('id','active');
            page_li[3].getElementsByTagName('a')[0].innerText = target-1;
            page_li[3].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(target-1)+')');
            page_li[4].getElementsByTagName('a')[0].innerText = target;
            page_li[4].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+target+')');
            page_li[5].getElementsByTagName('a')[0].innerText = target+1;
            page_li[5].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(target+1)+')');
            page_li[6].setAttribute('style','display:none');
        }
        else if (target == max_page-1){
            page_li[6].setAttribute('style','display: none');
            page_li[5].className = 'active';
            page_li[5].setAttribute('id','active');
            page_li[3].getElementsByTagName('a')[0].innerText = max_page-3;
            page_li[3].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(max_page-3)+')');
            page_li[4].getElementsByTagName('a')[0].innerText = max_page-2;
            page_li[4].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(max_page-2)+')');
            page_li[5].getElementsByTagName('a')[0].innerText = max_page-1;
            page_li[5].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(max_page-1)+')');
            page_li[2].className = 'disabled';
        }
        else if (target == max_page){
            page_li[8].className = 'disabled';
            page_li[8].getElementsByTagName('a')[0].removeAttribute('onclick');
            page_li[6].setAttribute('style','display: none');
            page_li[7].className = 'active';
            page_li[7].setAttribute('id','active');
            page_li[3].getElementsByTagName('a')[0].innerText = max_page-3;
            page_li[3].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(max_page-3)+')');
            page_li[4].getElementsByTagName('a')[0].innerText = max_page-2;
            page_li[4].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(max_page-2)+')');
            page_li[5].getElementsByTagName('a')[0].innerText = max_page-1;
            page_li[5].getElementsByTagName('a')[0].setAttribute('onclick','paginate('+(max_page-1)+')');
            page_li[2].className = 'disabled';
        }
        if (window.XMLHttpRequest){
            aj = new XMLHttpRequest();
        }
        else {
            aj = new ActiveXObject('Microsoft.XMLHTTP');
        }
        aj.onreadystatechange = function () {
            if (aj.readyState == 4 && aj.status == 200){
                var jsonObj = JSON.parse(aj.responseText);
                document.getElementById('book_tbody').innerHTML = jsonObj.tr_response;
                document.getElementById('book_num').innerHTML = jsonObj.booknum;
            }
        }
        aj.open('post','user_search.php');
        aj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        aj.send('search='+search+'&page='+target)
    }
</script>

</body>

</html>
