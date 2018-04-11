<?php
require_once("mongoconnect.php");
if (! "POST" == $_SERVER['REQUEST_METHOD']){
    header('Location:http://bookdivider.com');
}
else{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $account_validate = find('user',['username'=>$username,'password'=>$password,'indentity'=>'admin']);
    if (! $account_validate){
        header('Location:http://bookdivider.com');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>BookSepper Admin</title>

    <script src="js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/bootstrap.min.css">
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


	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
	<div class="navbar">
		<div class="row">
			<div class="column column-30 col-site-title"><a href="/" class="site-title float-left">BOOKSEPPER</a></div>
			<div class="column column-30">
				<div class="user-section"><a href="#">
					<img src="http://via.placeholder.com/50x50" alt="profile photo" class="circle float-left profile-photo" width="50" height="auto">
					<div class="username">
						<h4 id="admin">
							<?php

							if ("POST" == $_SERVER['REQUEST_METHOD']){
							    print $username;
                            };
							?>
						</h4>
						<p>Administrator</p>
					</div>
				</a></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="sidebar" class="column">
			<h5>Navigation</h5>
			<ul>
				<li><a href="#"><em class="fa fa-home"></em> Home</a></li>
				<li><a href="#users"><em class="fa fa-user"></em> Users</a></li>
				<li><a href="#book"><em class="fa fa-book"></em> BookInfo</a></li>
                <li><a href="#record"><em class="fa fa-history"></em> Record</a></li>
			</ul>
		</div>
		<section id="main-content" class="column column-offset-20">
			<div class="row grid-responsive">
				<div class="column page-heading">
					<div class="large-card">
						<h1>Hey there!</h1>
						<p class="text-large">This is BookSepper's admin page, you can use this for edit user-info or book-info.</p>
					</div>
				</div>
			</div>

			<h3>Users</h3><a class="anchor" name="users"></a>
			<div class="row grid-responsive">
                Now &nbsp;<div id="usernum"><?php print count(find('user',['username'=>['$ne'=>""]])); ?></div>&nbsp; users are using this system.
			</div>
            <div class="row grid-responsive">
                <input class="column column-20 col-search" type="text" id="user_search" placeholder="Search User...">
                &nbsp;
                <button class="fa fa-search" onclick="user_operation('user_search')"></button>
                &nbsp;
                <button class="fa fa-plus" onclick="user_operation('user_insert')"></button>
            </div>
            <div class="row grid-responsive">
                <table class="table" id="user_table">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Permission</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="user_tbody">
                    <?php
                    $identity = ['user','admin'];
                    $user_result = find('user');
                    foreach ($user_result as $user) {
                        $other_identity = $identity[1-array_search($user['indentity'],$identity)];
                        if ($user['username']) {
                            print<<<_HTML
                        <tr id="{$user['_id']}">
                            <td contenteditable="true">{$user['username']}</td >
                            <td contenteditable="true">{$user['password']}</td >
                            <td contenteditable = "true" >
                                <select id="select{$user['_id']}">
                                    <option >{$user['indentity']}</option >
                                    <option >{$other_identity}</option >
                                </select ></td >
                            <td >
                                <button class="fa fa-window-close" onclick="user_operation('user_delete','{$user['_id']}')"> Delete</button >
                                <button class="fa fa-save" onclick="user_operation('user_update','{$user['_id']}')"> Save</button >
                                <div id="response{$user['_id']}"></div>
                            </td >
                        </tr>
_HTML;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>


			<h3 class="mt-2">BookInfo</h3><a class="anchor" name="book"></a>
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
                        <th>Action</th>
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
                            <td >
                                <button class="fa fa-window-close" onclick="book_operation('book_delete','{$book['_id']}')">Delete</button>  
                                <button class="fa fa-save" onclick="book_operation('book_update','{$book['_id']}')">Save</button>
                                <div id="{$book['_id']}status"></div>
                            </td>
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

            <h3 class="mt-2">Record</h3><a class="anchor" name="record"></a>
            <?php
            $recent_record = find('record',[],['limit'=>3,'sort'=>['time'=>-1]]);
            ?>
            <div class="row grid-responsive mt-1">
                <div class="column">
                    <div class="card">
                        <div class="card-title">
                            <h2 class="float-left">Notifications</h2>
                            <div class="badge background-primary float-right"><?php print count(find('record')); ?></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="card-block">
                            <div class="mt-1">
                                <img src="http://via.placeholder.com/45x45" alt="profile photo" class="circle float-left profile-photo" width="45" height="auto">
                                <div class="float-left ml-1">
                                    <p class="m-0"><strong><?php print $recent_record[0]['admin'];?></strong> <span class="text-muted"><?php print $recent_record[0]['action'].' the '.$recent_record[0]['type'].'&nbsp;';?><strong style="color: #311bff"><?php print $recent_record[0]['object'];?></strong></span></p>
                                    <p class="text-small text-muted"><?php print $recent_record[0]['time'];?></p>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="m-0 mb-2" />
                            </div>
                            <div class="mt-1">
                                <img src="http://via.placeholder.com/45x45" alt="profile photo" class="circle float-left profile-photo" width="45" height="auto">
                                <div class="float-left ml-1">
                                    <p class="m-0"><strong><?php print $recent_record[1]['admin'];?></strong> <span class="text-muted"><?php print $recent_record[1]['action'].' the '.$recent_record[1]['type'].'&nbsp;';?><strong style="color: #311bff"><?php print $recent_record[1]['object'];?></strong></span></p>
                                    <p class="text-small text-muted"><?php print $recent_record[1]['time'];?></p>
                                </div>
                                <div class="clearfix"></div>
                                <hr class="m-0 mb-2" />
                            </div>
                            <div class="mt-1">
                                <img src="http://via.placeholder.com/45x45" alt="profile photo" class="circle float-left profile-photo" width="45" height="auto">
                                <div class="float-left ml-1">
                                    <p class="m-0"><strong><?php print $recent_record[2]['admin'];?></strong> <span class="text-muted"><?php print $recent_record[2]['action'].' the '.$recent_record[2]['type'].'&nbsp;';?><strong style="color: #311bff"><?php print $recent_record[2]['object'];?></strong></span></p>
                                    <p class="text-small text-muted"><?php print $recent_record[2]['time'];?></p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">

                </div>

            </div>
			

            <p class="credit">BookSepper by <font color="#3affca">TheVile</font></p>
		</section>
	</div>
	
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script>


	window.onload = function () {
		var chart1 = document.getElementById("line-chart").getContext("2d");
		window.myLine = new Chart(chart1).Line(lineChartData, {
		responsive: true,
		scaleLineColor: "rgba(0,0,0,.2)",
		scaleGridLineColor: "rgba(0,0,0,.05)",
		scaleFontColor: "#c5c7cc"
		});
		var chart2 = document.getElementById("bar-chart").getContext("2d");
		window.myBar = new Chart(chart2).Bar(barChartData, {
		responsive: true,
		scaleLineColor: "rgba(0,0,0,.2)",
		scaleGridLineColor: "rgba(0,0,0,.05)",
		scaleFontColor: "#c5c7cc"
		});
		var chart4 = document.getElementById("pie-chart").getContext("2d");
		window.myPie = new Chart(chart4).Pie(pieData, {
		responsive: true,
		segmentShowStroke: false
		});
		var chart5 = document.getElementById("radar-chart").getContext("2d");
		window.myRadarChart = new Chart(chart5).Radar(radarData, {
		responsive: true,
		scaleLineColor: "rgba(0,0,0,.05)",
		angleLineColor: "rgba(0,0,0,.2)"
		});
		var chart6 = document.getElementById("polar-area-chart").getContext("2d");
		window.myPolarAreaChart = new Chart(chart6).PolarArea(polarData, {
		responsive: true,
		scaleLineColor: "rgba(0,0,0,.2)",
		segmentShowStroke: false
		});

	};

	function user_operation(type,id="") {
        var aj;
        var admin = document.getElementById('admin').innerText;
        if (window.XMLHttpRequest) {
            aj = new XMLHttpRequest();
        }
        else {
            aj = new ActiveXObject("Microsoft.XMLHTTP")
        }
        try {
            var search_content = document.getElementById(type.replace(type.slice(5),'search')).value;
            var username = String(document.getElementById(id).cells[0].innerHTML);
            var password = String(document.getElementById(id).cells[1].innerHTML);
            var identity = document.getElementById('select' + id)[document.getElementById('select' + id).selectedIndex].text;
        }
        catch (e){
            var search_content = document.getElementById(type.replace(type.slice(5),'search')).value;
            var username = "";
            var password = "";
            var identity = "";
        }
        aj.onreadystatechange = function () {
            if (aj.readyState == 4 && aj.status == 200) {
                if (type == 'user_delete'){
                    document.getElementById('usernum').innerText = parseInt(document.getElementById('usernum').innerText)-1;
                }
                if (type != 'user_update'){
                    document.getElementById('user_tbody').innerHTML = aj.responseText;
                }
                else{
                    document.getElementById('usernum').innerText = aj.responseText;
                    document.getElementById("response"+id).innerHTML = "";
                }
            }
        }
        if (username.length < 5 && type == 'user_update') {
            document.getElementById("response"+id).innerHTML = "<font color='red'>The name must be formed by at least 5 characters.</font>";
        }
        else if (password.length < 6 && type == 'user_update') {
            document.getElementById("response"+id).innerHTML = "<font color='red'>The password must be formed by at least 6 characters.</font>";
        }
        else {
            aj.open('post','search.php');
            aj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            aj.send("type="+type+"&id="+id+"&username="+username+"&password="+password+"&identity="+identity+'&content='+search_content+'&admin='+admin);
        }
    }

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
        aj.open('post','book_search.php');
	    aj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	    aj.send('type='+type+'&id='+id+'&title='+title+'&category='+category+'&author='+author+'&intro='+intro+'&page='+page+'&search='+search+'&admin='+admin);
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
        aj.open('post','book_search.php');
        aj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        aj.send('search='+search+'&page='+target)
    }
	</script>			

</body>
</html> 