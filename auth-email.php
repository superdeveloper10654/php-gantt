<?php
session_start();
date_default_timezone_set('UTC');
include ("dbconfig.php");

$valid_auth = false;
$stmt = $db->prepare("SELECT * FROM users WHERE email_address = :email_address AND email_address_verified='0' AND invite_code = :invite_code");
$stmt->bindValue(':email_address', $_REQUEST['email_address'], PDO::PARAM_STR);
$stmt->bindValue(':invite_code', $_REQUEST['code'], PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $user['id'];
if ($stmt->rowCount() == 1)
{
	// Valid, update it and ok
	$valid_auth = true;
	
}
else 
{
	$valid_auth = false;
}
	
$valid_post = true;

if (isset($_POST['email_address']))
{
	// POSTED
	if ($_POST['password_1'] == $_POST['password_2'])
	{
		
			$hash = password_hash($_POST['password_1'], PASSWORD_DEFAULT);
			$new_code = rand(1000, 9999);
			$stmt = $db->prepare("UPDATE users SET invite_code = :new_code, hash = :hash, email_address_verified='1' WHERE email_address = :email_address AND invite_code = :invite_code");
			$stmt->bindValue(':email_address', $_POST['email_address'], PDO::PARAM_STR);
			$stmt->bindValue(':invite_code', $_POST['code'], PDO::PARAM_STR);
			$stmt->bindValue(':new_code', $new_code, PDO::PARAM_STR);
			$stmt->bindValue(':hash', $hash, PDO::PARAM_STR);
			$stmt->execute();
			
			$last_programme_id = $user['last_programme_id'];
			
			$user_id = $user['id'];
			$stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id_auth AND active='1'");
			$stmt->bindValue(':user_id_auth', $user_id, PDO::PARAM_STR);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['user'] = $user;
			
			
			header("Location: beta.php?id=" . $last_programme_id);
			die();
	}
	else 
	{
		$valid_post = false;
	}
}
	


	?>
  <!DOCTYPE html>
  <html>

  <head>
    <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="css/main.css" />
    <meta name="csrf-param" content="authenticity_token" />
    <meta name="csrf-token" content="" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/favicon.ico" />
    <meta name="viewport" content="width=device-width">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="ga-optimize-account-id" content="">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta id="environment" data-env="production" data-eventable="">
    <meta name="recaptcha-site-key" value="">
    <meta name="robots" content="index">
    <link rel="canonical" href="" />
    <meta name="twitter:site" content="@ibex-consulting">
    <title>Ibex Gantt | New Password</title>
    <meta name="description" content="" />
    <meta name="og:description" content="" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:card" content="summary" />
    <meta name="og:image" content="" />
    <meta name="twitter:image" content="" />
    <script src="js/modernizr.custom.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
    <script>
      window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  </head>

  <body class="body-public animated fadeIn">

    <style>
      
      /* Padding is added by Bootstrap upin the modal being shown */
      body.body-public {
    padding: 0 !important;
}
      div#modal_login {
    padding: 0 !important;
}
      
      div#index-kicker {
        background-image: url(../img/ibex.svg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
      }

      input#email_address {
        width: calc(100% - 85px);
        float: left;
        padding: 12px 30px 12px 12px;
        border-radius: 4px 0 0 4px;
        background-color: #fff;
        color: #3c3c3c;
        border: none;
        height: auto;
        line-height: unset;
      }

      .form-control:focus {
        box-shadow: none;
      }

      button.btn.btn-submit.next-step-1 {
        border-radius: 0 4px 4px 0;
        padding: 12px 15px;
        color: #fff;
        /*border: solid 1px #3c3c3c;*/
        cursor: pointer;
        font-size: 16px;
        margin-left: -1px;
        height: auto;
        background: #1ea69a;
        fill: none;
        transition: all .5s ease;
        line-height: unset;
      }

      button.btn.btn-submit.next-step-1:hover,
      focus {
        background: rgba(255, 255, 255, .4);
        transition: all .5s ease;
      }

      span.beta {
        font-size: 12px;
        padding: 5px;
        position: relative;
        top: -40px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 4px;
      }

      .loader {
        display: block;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #ffffff;
        background: -moz-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        background: -webkit-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        background: -o-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        background: -ms-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        background: linear-gradient(to right, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        position: relative;
        -webkit-animation: load3 1.4s infinite linear;
        animation: load3 1.4s infinite linear;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
      }

      .loader:before {
        width: 50%;
        height: 50%;
        background: #ffffff;
        border-radius: 100% 0 0 0;
        position: absolute;
        top: 0;
        left: 0;
        content: '';
      }

      .loader:after {
        background: #1ea69a;
        width: 75%;
        height: 75%;
        border-radius: 50%;
        content: '';
        margin: auto;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
      }

      @-webkit-keyframes load3 {
        0% {
          -webkit-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }

      @keyframes load3 {
        0% {
          -webkit-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(360deg);
          transform: rotate(360deg);
        }
      }

      p.sign-up-helper {
        color: #777;
        margin-top: 10px;
        font-size: 11px;
        line-height: initial;
      }

      img.feature-img {
        height: 50px;
        display: block;
        margin: auto;
      }

      .default-green-header {
        text-align: center;
      }

      .markdown-area {
        text-align: center;
      }

      .col-xs-12.col-md-6 img {
        margin: 0;
        height: 100px;
        float: left;
        padding: 20px;
      }

      .col-xs-12.col-md-6 .default-green-header {
        text-align: left;
        padding: 20px 0 0 0;
      }

      .col-xs-12.col-md-6 .markdown-area {
        text-align: left;
      }

      li {
        margin: 0 !important;
      }

      p {
        line-height: initial !important;
      }

      .hero-partial {
        margin: 0;
      }

      div#hero-3a {
        font-size: 28px;
        line-height: 34px;
      }

      a.sign-up {
        font-size: 16px;
        margin: 20px auto;
        padding: 12px 30px;
        text-decoration: none !important;
        border: none;
        border-radius: 30px;
        background: #1ea69a;
        fill: none;
        transition: all .5s ease;
        display: block;
        width: 182px;
      }

      button.btn.btn-submit.next-step-1.accept-terms {
        background: #1ea69a;
      }
      
      .modal-header {
    border: none;
        padding: 1rem;
}
      h4.modal-title {
    font-size: 1.5rem;
    padding: 10px 0 0 20px;
}
      button.close {
    top: 25px;
    right: 25px;
}
      input#login_email_address,
      input#login_password
      {
    padding: 12px 30px 12px 12px;
    border-radius: 4px 0 0 4px;
    color: #3c3c3c;
    border: none;
    height: auto;
    line-height: unset;
    background: #eee;
    box-shadow: none;
}
      button.btn.btn-submit.login {
    padding: 10px 25px;
    border-radius: 4px !important;
    font-size: 0.8rem !important;
    background-color: #fff !important;
    color: #3c3c3c !important;
    border: solid 1px #3c3c3c !important;
    cursor: pointer !important;
    display: block;
        float: right;
}
      input#password {
    width: calc(100% - 85px);
    float: left;
    padding: 12px 30px 12px 12px;
    border-radius: 4px 0 0 4px;
    background-color: #fff;
    color: #3c3c3c;
    border: none;
    height: auto;
    line-height: unset;
}
      button.btn.btn-submit {
    border-radius: 4px;
    padding: 12px 15px;
    color: #fff;
    cursor: pointer;
    font-size: 16px;
    margin: 10px 0;
    background: #1ea69a;
    line-height: unset;
}
    </style>

    <div class="cookie-banner vertically-align-contents hidden">
      <p>This website uses cookies and other tracking tools to provide you with the best experience. By using our site, you acknowledge that you understand this and agree to comply with our <a href="../cookies.php">cookies</a> policy.</p>
      <button><img src="img/close.svg" /></button>
    </div>

    <div id="top" style="position: relative; top: 0; height: 0;"></div>


    <?php include "nav.php"?>

    <section class="hero-partial">
      <input name="token" type="hidden" value="<?=bin2hex(openssl_random_pseudo_bytes(16))?>">
        <input id="valid_login" type="hidden" value="<?=$valid_login?>">
		<input id="action" type="hidden" value="<?=$_GET['action']?>">
      <div class="hero-div loaded" id="index-hero">
        <div class="container hero-container">
          <?php
		  if ($valid_auth == true)
		  {
			  ?>
          <div class="row">
            <div class="col-xs-12 vertically-align-contents-except-xs" style="padding: 0;">
              <div class="hero-text hero-text-left">
                <h1 id="hero-1">Thanks</span></h1>
                <h4 style="color: #999;">Your account has now been verified :)</h4>
              </div>
            </div>
          </div>
        <form action="" method="POST">
          <div class="row">
            <p style="color: #999; margin: 20px 0 10px 0;">Please enter and confirm your password</p>
            <div class="col-xs-12 vertically-align-contents-except-xs" style="padding: 0;">
              <div class="form-group">
						<input type="hidden" name="email_address" required value="<?=$_GET['email_address']?>">
						<input type="hidden" name="code" required value="<?=$_GET['code']?>">
              <input type="password" class="form-control signup-form-input" id="password" required placeholder="Enter password" name="password_1">
				  <input type="password" style="margin-top: 20px" class="form-control signup-form-input" id="password" required placeholder="Confirm password" name="password_2">
              </div>
            </div>
          </div>
              <div class="row">
                <button type="submit" class="btn btn-submit">Submit</button>
                  </div>
        </form>
            </div>
          </div>

        <?php
	  }
	  else {
	  	?>
		<h1 id="hero-1">Sorry</span></h1>
    <h4 style="color: #999;">Your account can't be verified :(</h4>
      <p style="color: #999;">Please send an email to support@ibex.software</p>
      <p style="color: #999;">We'll get back to you as soon as we can</p>

          <?php
  }
  ?>
        </div>
      </div>

    </section>

    <script src="/js/modernizr.js"></script>
    <script src="/js/jquery-2.1.4.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/retina.min.js"></script>
    <script src="/js/jquery.magnific-popup.min.js"></script>

  </body>

  </html>