<?php
session_start();
require_once 'dbconfig.php';

// Login
$stmt = $db->prepare("SELECT * FROM users WHERE email_address = :email_address AND invite_code = :invite_code AND state ='1'");
$stmt->bindValue(':email_address', $_REQUEST['email_address'], PDO::PARAM_STR);
$stmt->bindValue(':invite_code', $_REQUEST['code'], PDO::PARAM_STR);
$stmt->execute();
if ($stmt->rowCount() == 1)
{
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
}
else 
{
	header("Location: index.php");
	die();
}

if (isset($_POST['token']))
{
	// Check still valid!
	$stmt = $db->prepare("SELECT * FROM users WHERE email_address = :email_address AND invite_code = :invite_code AND state ='1'");
	$stmt->bindValue(':email_address', $_POST['email_address'], PDO::PARAM_STR);
	$stmt->bindValue(':invite_code', $_POST['invite_code'], PDO::PARAM_STR);
	$stmt->execute();
	if ($stmt->rowCount() == 1)
	{
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		$user_id = $user['id'];
		
		$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$time = time();
		$stmt = $db->prepare("UPDATE users SET hash='$hash',state='2',email_address_verified='1',account_setup='1',terms_accepted='$time',first_name = :first_name,last_name = :last_name WHERE id = '$user_id'");
		$stmt->bindValue(':first_name', $_POST['first_name'], PDO::PARAM_STR);
		$stmt->bindValue(':last_name', $_POST['last_name'], PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id AND state ='2'");
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['user'] = $user;
		
		header("Location: beta.php?id=" . $user['last_programme_id']);
		die();
	
		
		
		
	}
	else 
	{
		header("Location: index.php");
		die();
	}


}
?>
  <!DOCTYPE html>

  <html>

  <head>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,300,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ibex | Setup your account</title>
    <meta name="description" content="Ibex">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="icon" href="img/favicon.png" type="image/x-icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/magnific-popup.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/bootstrap-switch.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <style>
      .col-md-4 {
        margin: 25px 0 25px 0;
      }

      .nav-left {
        float: left;
        padding-left: 20px;
      }
      .nav-right {
        float: right;
        padding-right: 20px;
      }
      .nav-left> h4:after {
    content: "beta";
    display: block;
    color: #999;
    font-size: 12px;
    text-align: right;
}
    </style>
	 
	 <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-125668819-1"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

 gtag('config', 'UA-125668819-1');
 </script>

  </head>

  <body>
    <nav class="navbar navbar-default stuck">
          <div class="nav-left">
            <img class="logo-nav" alt="logo" src="img/logo.png">
          </div>
       <div class="nav-left">
         <h4>Ibex Gantt</h4>
          </div>
			 
      </nav>

    <div class="container welcome-message">

      <div class="col-md-4">
        
        <h1>Welcome<br><strong><?=$user['first_name']?></strong></h1>
        <h4>Please enter a new password</h4>
        <p>All your details are stored securely</p>

      </div>
      <div class="col-md-4">

			<form action="" method="POST">
        <input name="token" type="hidden" value="<?=bin2hex(openssl_random_pseudo_bytes(16))?>">
    <input type="hidden" name="email_address" value="<?=$_REQUEST['email_address']?>">
	 <input type="hidden" name="invite_code" value="<?=$_REQUEST['code']?>">

        <!-- Step 1 -->
        <div class="form-step-1-container">
          <div class="form-group">
            <div>
              <input type="text" class="form-control validate signup-form-input" name="first_name" required placeholder="First name" value="<?=$user['first_name']?>">
            </div>
          </div>
          <div class="form-group">
            <div>
              <input type="text" class="form-control validate signup-form-input" name="last_name" required placeholder="Last name" value="<?=$user['last_name']?>">
            </div>
          </div>
          <div class="form-group">
            <div>
              <input type="email" class="form-control email validate signup-form-input" name="email_address" disabled placeholder="Email address" value="<?=$user['email_address']?>">
            </div>
          </div>
			  <div class="form-group">
            <div>
              <input type="password" class="form-control email validate signup-form-input" name="password" required placeholder="Password" value="">
            </div>
			</div>

          <div class="form-group">
            <div>
              <button type="submit" class="btn btn-submit">Done</button>
            </div>
          </div>
		 </form>

        </div>
        <!-- Step 1 -->

		  

      </div>

    </div>

    <!--site-footer-->
    <footer class="site-footer section-spacing text-center">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <p class="footer-links">
              <a href="terms.php">Terms of Use</a>
              <a href="privacy.html">Privacy Policy</a>
              <a href="" data-toggle="modal" data-target="#modal-contact-form">Contact us</a>
            </p>
          </div>
          <div class="col-md-6"> <small>&copy; 2018 Bishop Surveying Solutions Ltd. All rights reserved.</small></div>

        </div>
        <!--chat-btn-->
        <a href="" class="chat-btn" data-toggle="modal" data-target="#modal-contact-form"></a>
        <!--chat-btn end-->

      </div>
    </footer>
    <!--site-footer end-->

    <div class="modal fade" id="modal_login" tabindex="-1" role="dialog">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="https://dashboard.ibex.software//programmes/img/icons/close.png"></span></button>
          <div class="modal-body">
            <div class="contact-form text-center">
              <header class="section-header">
                <h3>Login</h3>
              </header>
              <form action="" method="post" enctype="multipart/form-data" style="padding: 20px;">
                <div class="alert alert-warning-login" style="display: none; background-color: #ff9999; padding: 20px; border-radius: 5px; margin-bottom: 15px; ">
                  Incorrect login details. Please try again or reset your password
                </div>
                <div class="form-group">
                  <input type="email" id="login_email_address" name="email_address" required class="form-control input-lg" placeholder="Email address" value="<?=$login_email_address?>">
                </div>
                <div class="form-group">
                  <input type="password" id="login_password" name="password" required class="form-control input-lg" placeholder="Password">
                    </div>
                <button type="submit" class="btn btn-submit">Login</button><br>
                <!--<a href="reset-password.php"><small class="text-muted">Forgotten your password?</small></a>-->
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-contact-form" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="https://dashboard.ibex.software//programmes/img/icons/close.png"></span></button>
          <div class="modal-body">
            <div class="contact-form text-center">
              <header class="section-header">
                <h2>Contact us</h2>
                <p>Please use the form below or email to<br>richard@ibex.software</p>
              </header>
              <div class="form-group">
                <input type="text" name="name" class="contact-name form-control input-lg" placeholder="Your name" id="contact-name">
              </div>
              <div class="form-group">
                <input type="text" name="email" class="contact-email form-control input-lg" placeholder="Your email address" id="contact-email">
              </div>
              <div class="form-group">
                <textarea name="message" class="contact-message form-control input-lg" rows="4" placeholder="Your message" id="contact-message"></textarea>
              </div>
              <button type="submit" class="btn btn-submit">Submit</button>
            </div>
            <p class="contact-form-success"><i class="fa fa-check"></i><span>Thanks for contacting us.</span> We will get back to you very soon.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_terms" tabindex="-1" role="dialog">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="https://dashboard.ibex.software//programmes/img/icons/close.png"></span></button>
          <div class="modal-body">
            <div class="contact-form text-center">
              <header class="section-header">
                <h3>Terms of Use</h3>
              </header>
              <div class="modal-body">
                <p>By clicking on the 'I Agree' button below, you confirm that you accept and agree to the following <a href="terms.php" target="_blank">Terms of Use</a></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-submit accept-terms">I Agree</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_terms" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3>Terms of Use</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="https://dashboard.ibex.software//programmes/img/icons/close.png"></span></button>
            </button>
          </div>
          <div class="modal-body">
            <p>By clicking on the 'I Agree' button below, you confirm that you accept and agree to the following <a href="terms.php" target="_blank">Terms of Use</a></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-submit accept-terms">I Agree</button>
          </div>
        </div>
      </div>
    </div>


    <script src="js/modernizr.js"></script>
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/retina.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>

    <script>
      $('#modal_login').on('shown.bs.modal', function(e) {
        if ($("#valid_login").val().trim() != "1") {
          $("#login_password").focus();

        } else {
          $(".alert-warning-login").hide();
          $("#login_email_address").focus();
        }


      })


      $(document).ready(function() {
        if ($("#valid_login").val().trim() != "1") {
          $(".alert-warning-login").show();
          $("#modal_login").modal('show');

        }

      });





      $(".form-step-2-container").hide();
      $(".form-step-3-container").hide();
      $(".form-step-4-container").hide();

      function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
        return pattern.test(emailAddress);
      };


      $(".accept-terms").click(function(event) {
        $.getJSON("data.ajax.php?action=accept_terms&email_address=" + $("#email_address").val() + "&auth_code=" + $("#auth_code").val() + "&company_name=" + $("#company_name").val() + "&subdomain=" + $("#subdomain").val() + "&password=" + $("#password").val(), function(data) 
		  {
			  if (data.route == "production")
			 {
          window.location.href = "mmb-basic/beta.php?id=" + data.programme_id + "&demo=true";
		 }
		 else {
		 	 window.location.href = "mmb-basic/beta_demo.php?id=" + data.programme_id + "&demo=true";
		 }
        });

      });

      $("#company_name").focusout(function(event) {
        if ($("#subdomain").val().trim() == "") {
          var str = $("#company_name").val().trim();
          var strAdjusted = str.replace(/[_\W]+/g, "");
          $("#subdomain").val(strAdjusted.toLowerCase());
        }
      });

      $(".next-step-3").click(function(event) {
        $(".next-step-3").attr('disabled', 'disabled');
        $(".next-step-3").html('<i class="fas fa-spin fa-spinner"></i>');
        event.preventDefault();
        $('.signup-form-input').each(function() {
          $(this).removeClass("form-submit-fail");
          $(this).css('border', '1px solid #ddd');
        });

        var valid = true;
        $('.validate-text-3').each(function() {
          if ($(this).val().trim() == "") {
            valid = false;
            $(this).css('border', '1px solid #ff0000');
            $(this).addClass('form-submit-fail');
          } else {
            $(this).css('border', '1px solid #ddd');
            $(this).removeClass('form-submit-fail');
          }
        });

        $('.validate-text-4').each(function() {
          var str = $("#subdomain").val();
          var str = $("#company_name").val().trim();
          var strAdjusted = str.replace(/[_\W]+/g, "");
          $("#subdomain").val(strAdjusted.toLowerCase());

          if ($("#subdomain").val().trim() == "") {
            valid = false;
            $(this).css('border', '1px solid #ff0000');
            $(this).addClass('form-submit-fail');
          } else {
            $(this).css('border', '1px solid #ddd');
            $(this).removeClass('form-submit-fail');
          }
        });

        $('.validate-password-4').each(function() {
          if ($(this).val().trim().length < 6) {
            valid = false;
            $(this).css('border', '1px solid #ff0000');
            $(this).addClass('form-submit-fail');
          } else {
            $(this).css('border', '1px solid #ddd');
            $(this).removeClass('form-submit-fail');
          }
        });

        if (valid == true) {
          $("#modal_terms").modal('show');
        } else {
          $('html, body').animate({
            scrollTop: ($('.form-submit-fail').first().offset().top - 100)
          }, 500);
        }
      });

      $(".next-step-1").click(function(event) {
        $(".next-step-1").attr('disabled', 'disabled');
        $(".next-step-1").html('<i class="fas fa-spin fa-spinner"></i>');
        event.preventDefault();
        $('.signup-form-input').each(function() {
          $(this).removeClass("form-submit-fail");
          $(this).css('border', '1px solid #ddd');
        });

        var valid = true;
        $('.validate-text-1').each(function() {
          if ($(this).val().trim() == "") {
            valid = false;
            $(this).css('border', '1px solid #ff0000');
            $(this).addClass('form-submit-fail');
          } else {
            $(this).css('border', '1px solid #ddd');
            $(this).removeClass('form-submit-fail');
          }
        });
        $('.validate-email-1').each(function() {
          if (!isValidEmailAddress($(this).val().trim())) {
            valid = false;
            $(this).css('border', '1px solid #ff0000');
            $(this).addClass('form-submit-fail');
          } else {
            $(this).css('border', '1px solid #ddd');
            $(this).removeClass('form-submit-fail');
          }
        });

        if (valid == true) {
          $.getJSON("data.ajax.php?action=check_user_exists&email_address=" + $("#email_address").val() + "&first_name=" + $("#first_name").val() + "&last_name=" + $("#last_name").val(), function(data) {
            if (data.user_exists == false) {
              $(".form-step-1-container").hide();
              $(".form-step-2-container").show();
              $("#auth_code").focus();
            } else {
              $("#email_address").css('border', '1px solid #ff0000');
              $("#email_address").addClass('form-submit-fail');
            }
          });
        } else {
          $('html, body').animate({
            scrollTop: ($('.form-submit-fail').first().offset().top - 100)
          }, 500);
        }
        $(".next-step-1").removeAttr('disabled');
      });

      $(".next-step-2").click(function(event) {
        event.preventDefault();
        $(".next-step-2").attr('disabled', 'disabled');
        $(".next-step-2").html('<i class="fas fa-spin fa-spinner"></i>');
        $('.signup-form-input').each(function() {
          $(this).removeClass("form-submit-fail");
          $(this).css('border', '1px solid #ddd');
        });

        var valid = true;
        $('.validate-int-2').each(function() {
          if (!$.isNumeric($(this).val().trim()) || $(this).val().length != 4) {
            valid = false;
            $(this).css('border', '1px solid #ff0000');
            $(this).addClass('form-submit-fail');
          } else {

            $(this).css('border', '1px solid #ddd');
            $(this).removeClass('form-submit-fail');
            $.getJSON("data.ajax.php?action=verify_auth_code&code=" + $("#auth_code").val() + "&email_address=" + $("#email_address").val(), function(data) {
              if (data.code_valid == true) {
                $(".form-step-2-container").hide();
                $(".form-step-3-container").show();
                $("#company_name").focus();
              } else {
                $("#auth_code").css('border', '1px solid #ff0000');
                $("#auth_code").addClass('form-submit-fail');
              }
            });


          }
        });

      });
    </script>
  </body>

  </html>