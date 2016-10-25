<?php

require_once('session.php');
require_once('class.user.php');
$user = new USER();
$login = new USER();

if($user->is_loggedin()!=""){
  $tid = $_SESSION['user_session'];
}

if(isset($_POST['btn-mem-data'])){
  $MemberName = $_POST["member-name"];
  $MemberPhone = $_POST["member-phone"];
  $MemberEmail = $_POST["member-email"];

  if($MemberName=="") {
    $error[] = "Provide name!"; 
  }
  else if($MemberPhone=="")  {
    $error[] = "Provide Phone Number!"; 
  }
  else if($MemberEmail=="")  {
    $error[] = "Provide Email-ID!"; 
  }
  else{
      $stmt = $user->runQuery("SELECT * FROM team_members WHERE mem_phone=:mem_phone OR mem_email=:email");
      $stmt->execute(array(':mem_phone'=>$MemberPhone, ':email'=>$MemberEmail));
      $row=$stmt->fetch(PDO::FETCH_ASSOC);
      
      if($row['mem_email']==$MemberEmail) {
        $error[] = "User with given Email-ID is already registered!";
      }
      else if($row['mem_phone']==$MemberPhone) {
        $error[] = "User with given Phone Number is already registered!";
      }
      else
      {
        if($user->registerMember($tid , $MemberName , $MemberPhone , $MemberEmail)){  
          $user->redirect('index.php');
        }
      }
  }

}

if(isset($_POST['upload'])){
  $UploadName = $_FILES["fileToUpload"]["name"];
  $UploadTemp = $_FILES["fileToUpload"]["tmp_name"];
  $UploadType = $_FILES["fileToUpload"]["type"];
  $UploadName = preg_replace("#[^a-z0-9.]#i","",$UploadName);

  if(!$UploadTemp){
    die("No File Selected, Please Upload Again.");
  }
  else{
    move_uploaded_file($UploadTemp, "Upload/$UploadName");
  }
}

if(isset($_POST['btn-login']))
{
  $tname = strip_tags($_POST['txt_teamname']);
  $lmail = strip_tags($_POST['txt_leadermail']);
  $pass = strip_tags($_POST['txt_pass']);

  if($login->doLogin($tname,$lmail,$pass))
  {
    $login->redirect('index.php');
  }
  else
  {
    $error = "Wrong Details !";
  } 
}

if(isset($_POST['btn-signup']))
{
  $teamname = strip_tags($_POST['txt_teamname']);
  $leadername = strip_tags($_POST['txt_leadername']);
  $leadermail = strip_tags($_POST['txt_leadermail']);
  $leaderphone = strip_tags($_POST['txt_leaderphone']);
  $leadercollege = strip_tags($_POST['txt_leadercollege']);
  $memcount = strip_tags($_POST['txt_memcount']);
  $pass = strip_tags($_POST['txt_pass']);
  $passconf = strip_tags($_POST['txt_passconf']); 
  
  if($teamname=="") {
    $error[] = "Provide username!"; 
  }
  else if($leadermail=="")  {
    $error[] = "Provide email id!"; 
  }
  else if($leadername=="")  {
    $error[] = "Provide team leader's name!"; 
  }
  else if($leaderphone=="") {
    $error[] = "Provide team leader's phone number!"; 
  }
  else if($leadercollege=="") {
    $error[] = "Provide team leader's college!";  
  }
  else if($memcount=="")  {
    $error[] = "Provide number of members in team!";  
  }
  else if(!filter_var($leadermail, FILTER_VALIDATE_EMAIL))  {
      $error[] = 'Please enter a valid email address!';
  }
  else if($pass=="")  {
    $error[] = "provide password !";
  }
  else if(strlen($pass) < 6){
    $error[] = "Password must be atlesat 6 characters"; 
  }
  else if($pass != $passconf){
    $error[] = "Passwords do not match!"; 
  }
  else
  {

    try
    {
      $stmt = $user->runQuery("SELECT * FROM teams WHERE team_name=:teamname OR leader_mail=:leadermail");
      $stmt->execute(array(':teamname'=>$teamname, ':leadermail'=>$leadermail));
      $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
      if($row['team_name']==$teamname) {
        $error[] = "sorry username already taken!";
      }
      else if($row['leader_mail']==$leadermail) {
        $error[] = "sorry email id already in use!";
      }
      else
      {

        if($user->register($teamname , $leadermail , $leadername , $leaderphone , $leadercollege , $memcount ,$pass)){  
          $user->redirect('index.php');
        }
      }
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }
  } 
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=0.3, maximum-scale=2.0, user-scalable=no"/>
  <title>Code-O-Soccer 2017</title>

  <!-- CSS  -->
  <link rel="stylesheet" type="text/css" href="css/jquery.fullPage.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/skew.css" type="text/css" rel="stylesheet">
  <link href="css/terminal.css" type="text/css" rel="stylesheet">
  <link href="http://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="js/scrolloverflow.min.js"></script>
  <script type="text/javascript" src="js/jquery.fullPage.js"></script>
  <script type="text/javascript" src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
  <script type="text/javascript" src="js/terminal.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/scripts.js"></script>
  <script src="js/skew.js"></script>

</head>
<body>
  <div id="fullpage">
    <div id="popup-wrapper" onclick="$('#popup-wrapper').fadeOut(400)">
      <div id="popup">
        <div class="cont_principal">
          <div class="cont_centrar">
            <div class="cont_login">
              <form method="post">
                <div class="cont_tabs_login">
                  <?php
                  if(isset($error))
                  {
                    foreach($error as $error)
                    {
                       ?>
                                 <div class="alert alert-danger">
                                    <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                                 </div>
                                 <?php
                    }
                  }
                  else if(isset($_GET['joined']))
                  {
                     ?>
                             <div class="alert alert-info">
                                  <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                             </div>
                             <?php
                  }
                  ?>
                  <ul class='ul_tabs'>
                    <li class="active"><a href="#" onclick="sign_in()">SIGN IN</a>
                      <span class="linea_bajo_nom"></span>
                    </li>
                    <li><a href="#up" onclick="sign_up()">SIGN UP</a><span class="linea_bajo_nom"></span>
                    </li>
                  </ul>
                </div>
                <div class="cont_text_inputs">
                  <input type="text" class="input_form_sign " placeholder="TEAM LEADER" name="txt_leadername" />
                  <input type="text" class="input_form_sign d_block active_inp" placeholder="TEAM NAME" name="txt_teamname" />
                  <input type="password" class="input_form_sign d_block  active_inp" placeholder="PASSWORD" name="txt_pass" />
                  <input type="password" class="input_form_sign" placeholder="CONFIRM PASSWORD" name="txt_passconf" />
                  <input type="email" class="input_form_sign " placeholder="LEADER'S E-MAIL" name="txt_leadermail" />
                  <input type="text" class="input_form_sign " placeholder="LEADER'S PHONE NO." name="txt_leaderphone" />
                  <input type="text" class="input_form_sign" placeholder="COLLEGE ( LEADER )" name="txt_leadercollege" />
                  <input type="text" class="input_form_sign" placeholder="NUMBER OF MEMBERS ( MAX 4 )" name="txt_memcount" />
                  <a href="#" class="link_forgot_pass d_block" >Forgot Password ?</a>
                  <div class="terms_and_cons d_none"></div>
                </div>
                <div class="cont_btn">
                  <button class="btn_sign" type="submit" name="btn-login">SIGN IN</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="parallax">
      <div data-depth="0.1" class="layer">
        <div class="some-space">
          <!--<h1>Code-O-Soccer</h1>-->
        </div>
      </div>
      <div data-depth="0.4" class="layer">
        <div id="particles-js"></div>
      </div>
      <div data-depth="0.3" class="layer">
        <!--<div class="some-more-space"><a href="http://codepen.io/dominickolbe" target="_blank">is it cool?</a></div>-->
      </div>
    </div>
    <div class="particle-overlay"></div>
    <div class="section" style="background-color: transparent;">
      <header id="home">
        <div class="hero">
          <!--<div class="overlay"></div>-->
          <img src="img/logo.png" style="position:absolute; top: 30vh; left: 50vw; width: 5.6vw; transform: translateY(-50%) translateX(-50%);">
          <img src="img/Krssg.png" style="position:absolute; top: 4vh; left: 5vw; width: 5.6vw;">
          <p style="position: absolute; top: 68vh; left: 51vw; transform: translateX(-50%); width: 12vw; text-align: center; height: 10vh; font-size: 14px;">In Association With</p>
          <img src="img/Kshitij.png" style="position: absolute; top: 75vh; left: 50vw; width: 12vw; transform: translateX(-50%);">
          <p style="position: absolute; top: 83vh; left: 51vw; transform: translateX(-50%); width: 15vw; text-align: center; height: 10vh; font-size: 8px;">The Annual Techno-Management Fest of <br> IIT Kharagpur</p>
          <img src="img/IIT_Kharagpur_Logo.png" style="position:absolute; top: 5vh; right: 7vw; height: 8vh;">
          <h1 style="width: 100vw; font-family: 'Raleway', sans-serif; position:absolute; color: white; top: 45%; left: 50%; transform: translateY(-50%) translateX(-50%); text-align: center; font-size: 80px;"><span style="font-weight: 600;">Code-O-Soccer</span>&nbsp<span style="font-weight: 100;">2017</span></h1>
          <p style="position:absolute; color: gray; top: 60%; left: 50%; transform: translateY(-50%) translateX(-50%); text-align: center; font-size: 28px;">Kharagpur RoboSoccer Students' Group</p>
        </div>
      </header>
      <div class="sticky-wrapper">
        <nav>
          <?php 
            if($user->is_loggedin()!="") {
              echo '<a style="position: absolute; left: 2vw; color:#A1A1A1; font-size: 14px; letter-spacing: 2px; font-weight: 700; text-transform: uppercase; text-decoration:none; height:52px; margin-top: -5px; cursor: pointer;"onclick="$.fn.fullpage.moveTo(7);"">DashBoard</a>';
            }
          ?>  
          <ul>
            <li><a onclick="$.fn.fullpage.moveTo(1);">Home</a></li>
            <li><a onclick="$.fn.fullpage.moveTo(5);">Guide</a></li>
            <li><a onclick="$.fn.fullpage.moveTo(6);">Gallery</a></li>
            <li><a href="#">Team</a></li>

          </ul>
          <?php 
            if($user->is_loggedin()!="") {
              echo '<a style="position: absolute; right: 2vw; color:#A1A1A1; font-size: 14px; letter-spacing: 2px; font-weight: 700; text-transform: uppercase; text-decoration:none; height:52px; margin-top: -5px;" href="logout.php?logout=true" >Sign-Out</a>';
            }
            else{
              echo'<a style="position: absolute; right: 2vw; color:#A1A1A1; font-size: 14px; letter-spacing: 2px; font-weight: 700; text-transform: uppercase; text-decoration:none; height:52px; margin-top: -5px;" href="#" onclick="$(\'#popup-wrapper\').fadeIn(400)">Sign-In</a>';
            }
          ?> 
        </nav>
      </div>
    </div>
    <div class="section" id="section1">
        <div id="about">
          <div class="box boxhover row">
            <div class="col l6 offset-l3"  id="info1">
              <h1 class="heading"><span>About</span></h1>
              <p class="center-align">
                Code-O-Soccer is a coding competition conducted by Kharagpur RoboSoccer Students' Group. This is a first of its kind competition in India where soccer strategies brewing within one's mind are implemented on robots.
              </p>
              <p class="center-align">
                The aim of the event is to introduce the concept of autonomous soccer playing robots to students and motivate them to create a challenging strategy using our API for a THREE vs THREE robot match.
              </p>
            </div>
          </div>
          <div id="laptop">
            <div id="l-lid">
              <div id="l-camera">
                <div id="l-camera-lense"></div>
                <div id="l-camera-light"></div>
              </div>
              <div id="l-lid-inner">
                <div id="terminal" onclick="document.getElementById('term-command').focus()">
                  <div id="term-inner">
                    <div id="term-user-bar">
                      <p>[<span class="term-font-green">User</span>]$ </p>
                      <input type="text" id="term-command"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="l-bottom">
              <div id="l-bottom-dent">
                <div id="l-bottom-dent-inner"></div>
              </div>
              <ul id="l-left" class="l-grill">
                <li> </li>
                <li> </li>
                <li> </li>
              </ul>
              <ul id="l-right" class="l-grill">
                <li> </li>
                <li> </li>
                <li> </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
    <div class="section"  id="section2">
      <div class="overlay-2"></div>
      <div class="lure center-align">
        <h1><b>What's in it for you?</b></h1>
        <p>Code-O-Soccer is one of a kind competition in India. You can get a taste of what Simurosot in RoboWorld Cup is like. You get to battle your way up through the best in the country, under the supervision of the team that won <strong>Bronze in Mirosot 2015</strong>.</p>
        <p style="font-size: 25px; font-weight: bold;">Additionally, there are prizes worth Rs. 50,000 up for grabs!</p>

      </div>

      <div class="info-container row">
        <div class="container">
          <section class="Gio-98">
            <div class="grid">
              <div class="col l4 center-align">
                <div class="infobox center-align">
                  <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                    <line class="top" x1="0" y1="0" x2="900" y2="0"/>
                    <line class="left" x1="0" y1="460" x2="0" y2="-920"/>
                    <line class="bottom" x1="300" y1="460" x2="-600" y2="460"/>
                    <line class="right" x1="300" y1="0" x2="300" y2="1380"/>
                  </svg>
                  <img src="img/who.png" style="max-width: 6vw;">
                  <h3>Who?</h3>
                  <span>Participation is open to all programming and football enthusiasts. The event is about planning Soccer strategies and framing them in your codes. Interested people without the required coding skills can team up with coders!</span>
                  <span></span>
                </div>
              </div>
              <div class="col l4 center-align">
                <div class="infobox center-align">
                  <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                    <line class="top" x1="0" y1="0" x2="900" y2="0"/>
                    <line class="left" x1="0" y1="460" x2="0" y2="-920"/>
                    <line class="bottom" x1="300" y1="460" x2="-600" y2="460"/>
                    <line class="right" x1="300" y1="0" x2="300" y2="1380"/>
                  </svg>
                  <img src="img/rules.png" style="max-width: 6vw;">
                  <h3>Rules</h3>
                  <span>Some easy do’s and don’ts that are simpler to follow than those of a real soccer game! So don’t dread them or tread over them. They are explained in the Tutorials section.</span>
                  <span></span>
                </div>
              </div>
              <div class="col l4 center-align" >
                <div class="infobox center-align">
                  <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                    <line class="top" x1="0" y1="0" x2="900" y2="0"/>
                    <line class="left" x1="0" y1="460" x2="0" y2="-920"/>
                    <line class="bottom" x1="300" y1="460" x2="-600" y2="460"/>
                    <line class="right" x1="300" y1="0" x2="300" y2="1380"/>
                  </svg>
                  <img src="img/judge.png" style="max-width: 6vw;">
                  <h3>Judging</h3>
                  <span>Your ranking will be up on the leader board. Your code will be up against that of your rival in a 3-on-3 RoboSoccer match. Good old knockout style tournament. Start coaching your bots already!</span>
                  <span></span>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
    <div class="section" id="dates">
      <div id="content">
        <h1>Important Dates</h1>
          <ul class="timeline">
            <li class="event" data-date="21-10-2016">
              <h3>Registration & Submissions Opens</h3>
              <p>Fill up the form already, and start working on your team! Every minute counts.</p>
            </li>
            <li class="event" data-date="30-12-2016">
              <h3>Submission Closed</h3>
              <p>Send us your hard work and hope for the best! Hopefully you'll get a chance off your code in the main event!</p>
            </li>
            <li class="event" data-date="05-01-2017">
              <h3>List Of Shortlisted Teams</h3>
              <p>Find out if you are among the chosen ones. The big stage awaits those who made it, for there the true winners may bask in their own glory.</p>
            </li>
            <li class="event" data-date="27-01-2017">
              <h3>Main Event</h3>
              <p>This is where it all goes down. You will compete head to head with your friends and rivals. Get ready!</p>
            </li>
          </ul>
      </div>
    </div>
    <div  class="section" id="event-description">
      <div  id="event-container">
        <h1>Problem Statement:</h1>
        <p id="ps-para">Develop and code the strategy for controlling a team of 3 soccer playing robots in simulator for a 3 vs 3 match .</p>

        <h1>User Guidelines:</h1>

        <ul class="accordion">
          <li>
            <a>Technical Setup:</a>
            <p>
              <b>-></b> Download the API and the Debugger for which links are provided aside<br>
              <b>-></b> Download Visual Studio 2012/13 from this site (for VS'12).<br>
              <b>-></b> Follow “Setting up the project “ in website for setting up the environment to code in Visual Studio</p>
          </li>
          <li>
            <a>Description (Follow "User Manual"):</a>
            <p>
              <b>-></b> The description of robot and code architecture is explained in “ User Manual “ .<br>
              <b>-></b> Open “ Game.hpp “ as described in “ User Manual “ .<br>
              <b>-></b> Three roles are presented as I) Attacker , II) Defender and III) Goalkeeper .<br>
              <b>-></b> The working of these roles would be coded in their respective function definition.<br>
              <b>-></b> Ex. : Open “ Attacker.hpp “ and you have to code inside “ attacker(state , botID )“ function. Same goes for other two roles.<br>
              <b>-></b> Main aim is to code in these three above mentioned files for respective roles.<br>
              <b>-></b> You can make your own skills or roles , the process is described in “User Manual“.<br>
              <b>-></b> The code requires state parameters and predicates , which has been discussed in “User Manual“.<br>
            </p>
          </li>
          <li>
            <a>Running the Code:</a>
            <p>
              <b>-></b> Follow “Running Simulator“ part from “User Manual“ or “Setting up the project“.<br>
              <b>-></b> Go to Simurosot folder , open "Run.bat" in text editor (ex. Notepad++) , change line 4 : from "Abhinav" to your "PC name"<br>
            </p>
          </li>
        </ul> <!-- / accordion -->

        <div id="download-links" class="center-align">
          <div><a href="https://docs.google.com/document/d/1jJPRXfhGM21KWYznIsjIciULpF541rDDwUE6RAQl9To/edit?usp=sharing" target="_blank"> Setting Up</a></div>
          <div><a href="https://docs.google.com/document/d/1gXZJWIIaIOr4U4_2rF2v_y6mUKs8o41apBreEcjFw6U/edit?usp=sharing" target="_blank">User Manual</a></div>
          <div><a href="https://docs.google.com/document/d/1HHjU9f4vFJ9Y6aJRiZAa3EJdiAwPMSrYJ44xJ_mb0f8/edit?usp=sharing" target="_blank"> Rules</a></div>
          <div><a href="https://drive.google.com/folderview?id=0B2EEwIADnp5jajROcmZidGdFUUU&usp=sharing" target="_blank"> API</a></div>
          <div><a href="https://drive.google.com/file/d/0B7ZQ5D-yntA5RWRsNk0zWnh2dnc/view?usp=sharing" target="_blank">Debugger</a></div>
          <div><a href="https://www.youtube.com/channel/UCgDu_L5XZAwVo31ex99p9mQ" target="_blank">Video</a></div>
        </div>

      </div>

    </div>
    <div class="section" id="item1">
      <div class="skw-pages normal">
        <div class="skw-page skw-page-1 active">
          <div class="skw-page__half skw-page__half&#45;&#45;left">
            <div class="skw-page__skewed">
              <div class="skw-page__content"></div>
            </div>
          </div>
          <div class="skw-page__half skw-page__half&#45;&#45;right">
            <div class="skw-page__skewed">
              <div class="skw-page__content">
                <h2 class="skw-page__heading">FIRA BOTS</h2>
                <p class="skw-page__description">Our FIRA bots, lined up and ready for Code-O-Soccer 2016!</p>
              </div>
            </div>
          </div>
        </div>
        <div class="skw-page skw-page-2">
          <div class="skw-page__half skw-page__half&#45;&#45;left">
            <div class="skw-page__skewed">
              <div class="skw-page__content">
                <h2 class="skw-page__heading">Winning Team!</h2>
                <p class="skw-page__description">Team Code-O-Rockers with their trophy!</p>
              </div>
            </div>
          </div>
          <div class="skw-page__half skw-page__half&#45;&#45;right">
            <div class="skw-page__skewed">
              <div class="skw-page__content"></div>
            </div>
          </div>
        </div>
        <div class="skw-page skw-page-3">
          <div class="skw-page__half skw-page__half&#45;&#45;left">
            <div class="skw-page__skewed">
              <div class="skw-page__content"></div>
            </div>
          </div>
          <div class="skw-page__half skw-page__half&#45;&#45;right">
            <div class="skw-page__skewed">
              <div class="skw-page__content">
                <h2 class="skw-page__heading">Best Visiting Team</h2>
                <p class="skw-page__description">Team Swegbois from NIT Trichy won the third position, and the appreciation of each KRSSG member for having written an outstanding code.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="skw-page skw-page-4">
          <div class="skw-page__half skw-page__half&#45;&#45;left">
            <div class="skw-page__skewed">
              <div class="skw-page__content">
                <h2 class="skw-page__heading">Intro-Seminar</h2>
                <p class="skw-page__description">Our Advisor, Prof. Jayanta Mukhopadhyay, motivating all participants before the event.</p>
              </div>
            </div>
          </div>
          <div class="skw-page__half skw-page__half&#45;&#45;right">
            <div class="skw-page__skewed">
              <div class="skw-page__content"></div>
            </div>
          </div>
        </div>
        <div class="skw-page skw-page-5">
          <div class="skw-page__half skw-page__half&#45;&#45;left">
            <div class="skw-page__skewed">
              <div class="skw-page__content"></div>
            </div>
          </div>
          <div class="skw-page__half skw-page__half&#45;&#45;right">
            <div class="skw-page__skewed">
              <div class="skw-page__content">
                <h2 class="skw-page__heading">The Organinising Team</h2>
                <p class="skw-page__description">
                  The team that made Code-O-Soccer 2016 possible. We're working hard to make this year's event alot more exciting!
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if($user->is_loggedin()!=""){
      echo'
    <div class="section" id="dashboard">
      <h1 id="dash-head">Team Dashboard</h1>
        <div class="member-data">
          <div class="wrapper">
            <ul class="tabs clearfix" data-tabgroup="first-tab-group">';}?>
              <?php
                $user -> createMemberTab($tid);
              ?> <?php if($user->is_loggedin()!="") echo'
            </ul>
            <section id="first-tab-group" class="tabgroup">'?>
              <?php
                $user -> createMemberForm($tid);
              ?> <?php if($user->is_loggedin()!="") echo'
            </section>
          </div>
        </div>

        <div id="zip-upload">
          <div class="file-upload">
            <button class="file-upload-btn" type="button" onclick="$(\'.file-upload-input\').trigger( \'click\' )">Add Zip File</button>

            <div class="image-upload-wrap">
              <form action="index.php" method="post" enctype="multipart/form-data">
                <input class="file-upload-input" name="fileToUpload" type=\'file\' onchange="readURL(this);" />
              <div class="drag-text">
                <h3>Drag and drop a file or select \'Add Zip File\'</h3>
              </div>
            </div>
            <div class="file-upload-content">
              <img class="file-upload-image" src="#" style="display: none;" />
              <div class="image-title-wrap">
                <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded File</span></button>
                <input type="submit" value="Upload Zip" name="upload">
              </form>
              
              </div>
            </div>
          </div>
        </div>
    </div>'; ?>
    <div class="section fp-auto-height" style="padding: 0;">
      <footer class="page-footer" style="margin: 0; background-color: rgb(4,5,25);">
        <div class="container">
          <div class="row">
            <div class="col l5 s12">
              <h5 class="white-text">About KRSSG:</h5>
              <p class="grey-text text-lighten-4" style="font-size: 15  px;">It is a research group comprising of a bunch of ardent robotics technocrats from the Indian Institute of Technology, Kharagpur working together to build autonomous soccer-playing robots. The research objective of the group is to build and study cooperative multi-agent systems in highly dynamic adversarial environments. Our Aim is to participate in two International events FIRA and RoboCup.</p>


            </div>
            <div class="col l3 offset-l1 s12">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3690.9235467484923!2d87.30252999999999!3d22.318730999999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a1d4407422c1675%3A0xa2d9d15d09ca4c4!2sTechnology+Student+Gymkhana!5e0!3m2!1sen!2sin!4v1433314583855" frameborder="0" style="border:0;width: 80%; height: 250px;">
              </iframe>
            </div>
            <div class="col l3 s12">
              <h5 class="white-text">Address:</h5>
              <ul>
                <p style="font-size: 14px;">KRSSG Lab, Technology Students Gymkhana<br>
                   IIT Kharagpur<br>
                   Kharagpur, West Bengal<br>
                   PIN: 721302
                </p>
                <h5 class="white-text">Contact:</h5>
                <li><a class="white-text" href="#!" style="font-size: 18px;">Abhinav Agarwalla : 7797436418</a></li>
                <li><a class="white-text" href="#!" style="font-size: 18px;">Ankit Lohani : 9933888782</a></li>
                
              </ul>
            </div>
          </div>
        </div>
        <div class="footer-copyright">
          <div class="container">
            <div class="row">
              <div class="col l3 s8" style="font-size: 16px;">
                Follow Us: <a class="white-text" href="https://www.facebook.com/krssg" target="_blank">&nbsp&nbsp<i class="ion-social-facebook"></i>&nbsp&nbsp</a>
                <a class="white-text" href="https://www.youtube.com/user/KRSSGIITKGP/" target="_blank">&nbsp&nbsp<i class="ion-social-youtube"></i>&nbsp&nbsp </a>
              </div>
              <div class="col l3 offset-l6 s8" style="font-size: 16px;">
                Website Credit: <a class="white-text" href="http://www.linkedin.com/in/rishit-sinha" target="_blank" style="cursor: pointer;">Rishit Sinha</a>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>

  </div>
  
  </body>
</html>
