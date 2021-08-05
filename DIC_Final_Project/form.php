<?PHP
  //Report PHP errors
  error_reporting(E_ALL);
  //Handle file uploading
  include("file.php");   
  //Server parameters
  $server = "aa188jxr5rvfjhm.cv9svum4pqtk.us-east-1.rds.amazonaws.com";
  $user = "vchitnen";
  $password = "Allah786";
  $database = "aum";
  //Get form values from html form submit
  $name = $_POST['name'];
  $email = $_POST['email'];
  $number = $_POST['number'];
  $message = $_POST['message'];
  $fileuploaded = basename( $_FILES["fileToUpload"]["name"]);
  $flag = 0;
  
  //Connect to database
  $mysqli = new mysqli($server,$user,$password,$database);
  if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
  if(empty($ok)){  //Check is there is an error sending the file if not proceed
	  //Insert values to table students
	  $querytxt = "INSERT INTO students VALUES('0', '$name', '$email', '$number', '$fileuploaded', '$message')";
	  try{
	  $r = $mysqli->query($querytxt);
	  if (!$r)
		{
		  throw new Exception($mysqli->error);
		}
	  $ok = "Your application was submitted. We will contact you soon.";
	  $flag = 1;
	  }
	  catch(Exception $e){
		$ok = "An error has ocurred while processing your application. Try again later.";  
		$ok .= $ok." ".$e->getMessage();
		
	  }
  }
  
  
  
  $mysqli->close(); 
?>
<!DOCTYPE html>
<html  >
<head>
  
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="assets/images/mbr-favicon.png" type="image/x-icon">
  <meta name="description" content="">
  
  
  <title>form</title>
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons/mobirise-icons.css">
  <link rel="stylesheet" href="assets/web/assets/mobirise-icons2/mobirise2.css">
  <link rel="stylesheet" href="assets/tether/tether.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">
  <link rel="stylesheet" href="assets/dropdown/css/style.css">
  <link rel="stylesheet" href="assets/formstyler/jquery.formstyler.css">
  <link rel="stylesheet" href="assets/formstyler/jquery.formstyler.theme.css">
  <link rel="stylesheet" href="assets/datepicker/jquery.datetimepicker.min.css">
  <link rel="stylesheet" href="assets/socicon/css/styles.css">
  <link rel="stylesheet" href="assets/theme/css/style.css">
  <link rel="stylesheet" href="assets/recaptcha.css">
  <link rel="preload" as="style" href="assets/mobirise/css/mbr-additional.css"><link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">
  
  
  

</head>
<body>
  <section class="menu cid-rVQ3D5AQQa" once="menu" id="menu1-l">

    

    <nav class="navbar navbar-dropdown align-items-center navbar-toggleable-sm">
        
        <div class="menu-bottom">


            <div class="menu-logo">
                <div class="navbar-brand">
                    <span class="navbar-logo">
                        
                            <img src="assets/images/logo-170x92.png" alt="" title="" style="height: 4rem;">
                        
                    </span>
                    <span class="navbar-caption-wrap"><a href="http://sciences.aum.edu/" class="brand-name mbr-black mbr-bold text-white display-5" target="_blank">AUBURN<br></a></span>
                </div>
            </div>



            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-dropdown js-float-line" data-app-modern-menu="true"><li class="nav-item">
                        <a class="nav-link link mbr-black text-white display-4" href="index.html">
                            Home</a>
                    </li><li class="nav-item"><a class="nav-link link mbr-black text-white display-4" href="page3.html#menu1-y">
                            Academic Advising</a></li>
                    

                    

                    <li class="nav-item">
                        <a class="nav-link link mbr-black text-white display-4" href="page4.html#menu1-1a" aria-expanded="true">Programs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link mbr-black text-white display-4" href="page5.html#menu1-1q">
                            Career</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link mbr-black text-white display-4" href="page6.html#menu1-1v">
                            Scholarships</a>
                    </li><li class="nav-item"><a class="nav-link link mbr-black text-white display-4" href="page8.html#menu1-2t">
                            Events</a></li></ul>

                <div class="navbar-buttons mbr-section-btn"><a class="btn btn-md btn-white display-4" href="page9.html"> ABOUT</a></div>

            </div>
            <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>







        </div>
    </nav>
</section>

<section class="form cid-rVQ3O1rrpk" id="formbuilder-r">
    
    
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto mbr-form" data-form-type="formoid">
				<?PHP if($flag==1) {?>
				<div data-form-alert="" class="alert alert-success col-12"><H3><?PHP echo $ok;?></H3></div>
				<?PHP } else { ?>
                <div data-form-alert-danger="" class="alert alert-danger col-12"><H3><?PHP echo $ok;?></H3></div>
                <?PHP } ?>
                
                
            </div>
        </div>
    </div>
</section>

<section class="extFooter cid-rVQ3DbvBu5" id="extFooter18-p">

    

    


    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1380px" height="760px" viewBox="0 0 1380 760" preserveAspectRatio="xMidYMid meet">
        <defs id="svgEditorDefs">
            <polygon id="svgEditorShapeDefs" style="fill:khaki;stroke:black;vector-effect:non-scaling-stroke;stroke-width:0px;"></polygon>
        </defs>
        <rect id="svgEditorBackground" x="0" y="0" width="1380" height="760" style="fill: none; stroke: none;"></rect>
        <path d="M0.3577131120350206,0.819491525482845h-1.5000000000000355ZM0.3577131120350206,-3.1805084745172603h-1.5000000000000355ZM-0.14228688796500222,-4.180508474517258h5.000000000000002a5,5,0,0,1,0,6.00000000000003h-5.000000000000025a5,5,0,0,0,0,-6.00000000000003ZM5.8577131120349835,-1.1805084745172634h1.0000000000000249Z" style="fill:khaki; stroke:black; vector-effect:non-scaling-stroke;stroke-width:0px;" id="e2_shape" transform="matrix(1.01506 82.3743 -245.478 0.34062 392.311 526.125)"></path>
    </svg>


    <div class="container">
        <div class="media-container-row content text-white">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="media-wrap align-left">
                    <a href="http://www.aum.edu/">
                        <img src="assets/images/logo-128x69.png" alt="" title="">
                    </a>
                </div>

                <p class="mbr-text align-left text1 mbr-fonts-style display-4"><a href="http://sciences.aum.edu/academic-programs" target="_blank" class="text-white">Academic Programs</a>
<br><a href="http://sciences.aum.edu/student-resources" target="_blank" class="text-white">Student Resources
</a><br><a href="http://sciences.aum.edu/departments" target="_blank" class="text-white">Academic Departments
</a><br><a href="http://sciences.aum.edu/about/dean%27s-office" target="_blank" class="text-white">Dean's Office</a> 
<br><a href="http://www.aum.edu/imagine" target="_blank" class="text-white">Alumni &amp; Giving </a>
<br><a href="http://sciences.aum.edu/about/giving" target="_blank" class="text-white">Giving to the College of Sciences&nbsp;</a><br>About&nbsp;<br><a href="http://sciences.aum.edu/" target="_blank" class="text-white">Home</a></p>

                <div class="social-list align-left">
                    <div class="soc-item">
                        <a href="https://twitter.com/aumontgomery" target="_blank">
                            <span class="mbr-iconfont mbr-iconfont-social socicon-twitter socicon" style="color: rgb(87, 70, 139); fill: rgb(87, 70, 139);"></span>
                        </a>
                    </div>
                    <div class="soc-item">
                        <a href="https://www.facebook.com/auburnmontgomery" target="_blank">
                            <span class="mbr-iconfont mbr-iconfont-social socicon-facebook socicon" style="color: rgb(87, 70, 139); fill: rgb(87, 70, 139);"></span>
                        </a>
                    </div>
                    <div class="soc-item">
                        <a href="https://www.youtube.com/AuburnMontgomery" target="_blank">
                            <span class="mbr-iconfont mbr-iconfont-social socicon-youtube socicon" style="color: rgb(87, 70, 139); fill: rgb(87, 70, 139);"></span>
                        </a>
                    </div>
                    <div class="soc-item">
                        <a href="https://www.instagram.com/auburnmontgomery/" target="_blank">
                            <span class="mbr-iconfont mbr-iconfont-social socicon-instagram socicon" style="color: rgb(87, 70, 139); fill: rgb(87, 70, 139);"></span>
                        </a>
                    </div>
                    

                </div>


            </div>
            <div class="col-12 col-md-6 col-lg-3 mbr-fonts-style display-4">
                <h5 class="pb-3 align-left">
                    Contact Info
                </h5>


                <div class="item">
                    <div class="card-img"><span class="mbr-iconfont img1 mobi-mbri-map-pin mobi-mbri"></span>
                    </div>
                    <div class="card-box">
                        <h4 class="item-title align-left mbr-fonts-style display-4">P.O. Box 244023 • Montgomery, AL 36124-4023</h4>
                    </div>
                </div>

                <div class="item">
                    <div class="card-img"><span class="mbr-iconfont img1 mobi-mbri-pin mobi-mbri"></span></div>
                    <div class="card-box">
                        <h4 class="item-title align-left mbr-fonts-style display-4">7430 East Drive • Montgomery, AL 36117</h4>
                    </div>
                </div>

                <div class="item">
                    <div class="card-img"><span class="mbr-iconfont img1 mobi-mbri-letter mobi-mbri"></span>
                    </div>
                    <div class="card-box">
                        <h4 class="item-title align-left mbr-fonts-style display-4">
                            admissions@aum.edu</h4>
                    </div>
                </div>

                <div class="item">
                    <div class="card-img"><span class="mbr-iconfont img1 mobi-mbri-phone mobi-mbri"></span>
                    </div>
                    <div class="card-box">
                        <h4 class="item-title align-left mbr-fonts-style display-4">334-244-3000 / 800-227-2649</h4>
                    </div>
                </div>

                


            </div>
            <div class="col-12 col-md-6 col-lg-3 mbr-fonts-style display-7">

                <p class="mbr-text quote align-left mbr-fonts-style display-4">Thank you for your interest in AUM!   Our admissions application is simple to complete.</p>

                <div class="item">
                    <div class="card-img2"><a href="http://www.aum.edu/content/apply-now-0" target="_blank"><span class="mbr-iconfont ico2 mbri-touch-swipe" style="color: rgb(255, 255, 255); fill: rgb(255, 255, 255);"></span></a></div>
                    <div class="card-box">
                        <h4 class="theme align-left mbr-fonts-style display-7">
                            <a href="http://www.aum.edu/content/apply-now-0" target="_blank" class="text-white">Apply Now</a></h4>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mbr-fonts-style display-7">
                <h5 class="pb-3 align-left">Support and Downloads</h5>
                <p class="mbr-text align-left text2 mbr-fonts-style display-4">
                    You can also download our applicaion either from App store or Google Play.</p>

                <div class="mbr-section-btn align-left"><a class="btn btn-md btn-white-outline display-4" href="https://www.vsaleup.com" target="_blank"><span class="socicon socicon-apple mbr-iconfont mbr-iconfont-btn"></span>App Store</a> <a class="btn btn-md btn-white-outline display-4" href="https://www.vsaleup.com" target="_blank"><span class="socicon socicon-play mbr-iconfont mbr-iconfont-btn"></span>Google Play</a></div>

            </div>
        </div>


    </div>
</section>

<section class="footer2 cid-rVQ3DdCymn" id="footer2-q">

    

    

    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <p class="mbr-text links mbr-fonts-style display-4">
                    Copyright (c) 2020  Auburn University at Montgomery</p>
            </div>
        </div>
    </div>
</section>


  <script src="assets/web/assets/jquery/jquery.min.js"></script>
  <script src="assets/popper/popper.min.js"></script>
  <script src="assets/tether/tether.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/dropdown/js/nav-dropdown.js"></script>
  <script src="assets/dropdown/js/navbar-dropdown.js"></script>
  <script src="assets/touchswipe/jquery.touch-swipe.min.js"></script>
  <script src="assets/formstyler/jquery.formstyler.js"></script>
  <script src="assets/formstyler/jquery.formstyler.min.js"></script>
  <script src="assets/datepicker/jquery.datetimepicker.full.js"></script>
  <script src="assets/smoothscroll/smooth-scroll.js"></script>
  <script src="assets/theme/js/script.js"></script>
  <script src="assets/formoid.min.js"></script>
  
  

</body>
</html>

