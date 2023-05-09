<?php
  session_start();
  require_once '../dbconfig.php';
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  $valid_login = true;
  $login_email_address = "";
  if (isset($_POST['email_address']))
  {
    // Login
    $stmt = $db->prepare("SELECT id,hash,last_programme_id FROM users WHERE email_address = :email_address AND active='1'");
    $stmt->bindValue(':email_address', $_REQUEST['email_address'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() == 0)
    {
      $valid_login = false;
      $login_email_address = $_REQUEST['email_address'];
    }
    if ($stmt->rowCount() == 1)
    {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if (password_verify($_REQUEST['password'], $user['hash'])) 
      {
        $user_id = $user['id'];
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id_auth AND active='1'");
        $stmt->bindValue(':user_id_auth', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user'] = $user;
        $stmt = $db->prepare("SELECT * FROM logins WHERE user_id = :user_id_login ORDER BY created DESC");
        $stmt->bindValue(':user_id_login', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $login = $stmt->fetch(PDO::FETCH_ASSOC);
        // $login_last = $login['created'];
        $_SESSION['last_login'] = $login['created'];
        $time_now = time();
        $stmt = $db->prepare("INSERT INTO logins(user_id,created) VALUES (?,?)");
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $time_now);
        $stmt->execute();
      
        // Reset dates to default 2 months
        $date_range_start = date('Y-m-d', strtotime('-30 days'));
        $date_range_end = date('Y-m-d', strtotime('+30 days'));
        
        $prog_id = $user['last_programme_id'];
        $stmt = $db->prepare("UPDATE gantt_user_programme_links SET date_range_start='$date_range_start',date_range_end='$date_range_end' WHERE user_id = '$user_id' AND programme_id= '$prog_id'");
        $stmt->execute();
        $valid_login = true;
        $_SESSION['current_programme_id'] = $user['last_programme_id'];
        header("Location: beta.php?id=" . $user['last_programme_id']);
        die();
      } 
      else 
      {
        $valid_login = false;
        $login_email_address = $_REQUEST['email_address'];
      }
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
    <title>Ibex Gantt | Home</title>
    <meta property="og:title" content="Ibex Gantt | Home" />
    <meta property="twitter:title" content="Ibex Gantt | Home" />
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
        border-radius: 4px;
        background: #1ea69a;
        fill: none;
        transition: all .5s ease;
        display: block;
        width: 182px;
        color: #fff;
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
    </style>
    
    <form action="" method="post" enctype="multipart/form-data" style="padding: 20px;">
      <div class="alert alert-warning-login" style="display: none; color: red; text-align: center;">
        Please try again or reset your password
      </div>

      <div class="alert alert-account-exists" style="display: none; color: red; text-align: center;">
        No need to sign up again - please login
      </div>

      <div class="form-group">
        <input type="email" id="login_email_address" name="email_address" required class="form-control input-lg" placeholder="Email address" value="<?=$login_email_address?>">
      </div>
      <div class="form-group">
        <input type="password" id="login_password" name="password" required class="form-control input-lg" placeholder="Password">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-submit login">Login</button>
        <div style="float: left; padding: 10px;">
          <a href="../reset-password.php">
            <p>Reset Password</p>
          </a>
        </div>
      </div>
    </form>
    
    <div class="cookie-banner vertically-align-contents hidden">
      <p>This website uses cookies and other tracking tools to provide you with the best experience. By using our site, you acknowledge that you understand this and agree to comply with our <a href="../cookies.php">cookies</a> policy.</p>
      <button><img src="img/close.svg" /></button>
    </div>

    <div id="top" style="position: relative; top: 0; height: 0;"></div>

    <?php include "nav.php"?>

    <section class="hero-partial">
      <input name="token" type="hidden" value="<?=bin2hex(openssl_random_pseudo_bytes(16))?>">
      <input id="valid_login" type="hidden" value="<?=$valid_login?>">
      <!-- <input id="action" type="hidden" value="<?php //echo $_GET['action']?>"> -->
      <div class="hero-div loaded" id="index-hero">
        <div class="container hero-container">
          <div class="row">
            <div class="col-xs-12 vertically-align-contents-except-xs" style="padding: 0;">
              <div class="hero-text hero-text-left">
                <h1 id="hero-1">Ibex Gantt<span class="beta">beta</span></h1>
                <h4 style="color: #999;">Collaborative project management software<br> for subcontractors and trades</h4>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <h4 style="color: #999;">Sign up for your free account</h4>
            <div class="col-xs-12 vertically-align-contents-except-xs" style="padding: 0;">
              <div class="form-group">
                <input type="email" class="form-control email validate validate-email-1 signup-form-input" id="email_address" required="" placeholder="Email address" value="">
                <button class="btn btn-submit next-step-1 accept-terms" disabled>Sign up</button>
                <p class="sign-up-helper">
                  Refer to our <a href="/terms.php">Terms of Use</a>, <a href="/privacy.php">Privacy Policy</a>, <a href="/data.php">Data Processing</a> and <a href="/cookies.php">Cookies</a> policies
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <a href="#alpha">
        <div class="arrow animated bounce" style="display: block;"></div>
      </a>
    </section>

    <a name="alpha" style="display: block;"></a>

    <section class="six">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <h1 class="default-header">Realise the real-time benefits</h1>
            <h1>
            </h1>
            <h2 class="default-paragraph"></h2>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-4">
            <img class="feature-img" src="img/svg/no-scope-creep.svg">
            <div class="default-green-header">No Scope-Creep</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>Lock-in the agreed scope of work with our workload management features</p>
            </div>
          </div>
          <div class="col-xs-12 col-md-4">
            <img class="feature-img" src="img/svg/no-surprises.svg">
            <div class="default-green-header">No Nasty Surprises</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>See how your projects have evolved with our collaborative activity feed</p>
            </div>
          </div>
          <div class="col-xs-12 col-md-4">
            <img class="feature-img" src="img/svg/no-lost-time.svg">
            <div class="default-green-header">No Wasted Time</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>Optimise your activities with our robust automagical scheduling engine</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="six">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <h1 class="default-header">Don't work harder - be smarter</h1>
            <h1>
            </h1>
            <h2 class="default-paragraph">We've developed Ibex Gantt to bring enterprise-level features to your projects</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-4">
            <img class="feature-img" src="img/svg/powerful.svg">
            <div class="default-green-header">Powerful & Intuitive</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>Drag n drop automagical scheduling</p>
            </div>
          </div>
          <div class="col-xs-12 col-md-4">
            <img class="feature-img" src="img/svg/responsive.svg">
            <div class="default-green-header">Agile & Responsive</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>Integrated risk management tools</p>
            </div>
          </div>
          <div class="col-xs-12 col-md-4">
            <img class="feature-img" src="img/svg/protected.svg">
            <div class="default-green-header">Accessible & Secure</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>Private or collaborative access
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="six">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <h1 class="default-header">Manage your projects like a pro</h1>
            <h1>
            </h1>
            <h2 class="default-paragraph">Wether you're a seasoned project manager or a newbie, stay in control with us </h2>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-6">
            <img class="feature-img" src="img/svg/scheduling.svg">
            <div class="default-green-header">Automagical Scheduling</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>Everything you need to schedule your activities</p>
              <ul>
                <li>Projects, tasks/subtasks and milestones</li>
                <li>Calendars (working dates/overrides & times)</li>
                <li>Dependencies with lead & lag and critical path</li>
                <li>Workload management & progress monitoring </li>
              </ul>
            </div>
          </div>
          <div class="col-xs-12 col-md-6">
            <img class="feature-img" src="img/svg/excavator.svg">
            <div class="default-green-header">Resource Management</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>Everything you need to control your resources</p>
              <ul>
                <li>Resource groups with max/min output values</li>
                <li>Resource calendars and item availabilty</li>
                <li>Task allocation and cost tracking</li>
                <li>Resources pane aligned with scheduling data </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-6">
            <img class="feature-img" src="img/svg/collaboration.svg">
            <div class="default-green-header">Collaboration</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>Everything you need to work alone or together</p>
              <ul>
                <li>Team management with permission controls</li>
                <li>Activity feed for real-time updates & rollbacks</li>
                <li>Instant messaging, commenting and file sharing</li>
                <li>Multi-user clash detection & avoidance</li>
            </div>
          </div>
          <div class="col-xs-12 col-md-6">
            <img class="feature-img" src="img/svg/cog-square.svg">
            <div class="default-green-header">Custom Settings</div>
            <div class="default-paragraph-container default-paragraph-spacing markdown-area ">
              <p>Everything you need to make Ibex Gantt your own</p>
              <ul>
                <li>Site background image and colour scheme</li>
                <li>Selectable Gantt & resource pane columns</li>
                <li>Zoom in/out and set the viewable date range</li>
                <li>Show baselines, deadlines & critical path</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="hero-partial">
      <div class="hero-div loaded" id="index-kicker">
        <div class="container hero-container">
          <div class="row">
            <div class="col-xs-12 vertically-align-contents-except-xs">
              <div class="hero-text hero-text-left">
                <div id="hero-3a">Try our collaborative project management software for subcontractors and trades</div>
                <a class="sign-up" href="#top">Sign up - it's free</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

     
    <div class="modal fade" id="modal_login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-info" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Login</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="img/svg/close.svg"></span></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data" style="padding: 20px;">
                  <div class="alert alert-warning-login" style="display: none; color: red; text-align: center;">
                    Please try again or reset your password
                  </div>

                  <div class="alert alert-account-exists" style="display: none; color: red; text-align: center;">
                    No need to sign up again - please login
                  </div>

                  <div class="form-group">
                    <input type="email" id="login_email_address" name="email_address" required class="form-control input-lg" placeholder="Email address" value="<?=$login_email_address?>">
                  </div>
                  <div class="form-group">
                    <input type="password" id="login_password" name="password" required class="form-control input-lg" placeholder="Password">
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-submit login">Login</button>
                    <div style="float: left; padding: 10px;">
                      <a href="../reset-password.php">
                        <p>Reset Password</p>
                      </a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include "footer.php"?>

    <script src='http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.0.6/modernizr.min.js' type='text/javascript'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

    <script>
      $(".acetrnt-toggle").click(function() {
        $(this).toggleClass("active")
      })

      $("input#email_address").focus();
      $("input#email_address").focusout(function(event) {
        $(".next-step-1").removeAttr('disabled');
      });

      $('#modal_login').on('shown.bs.modal', function(e) {
        if ($("#valid_login").val().trim() != "1") {
          $("#login_password").focus();
        } else {
          $(".alert-warning-login").hide();
          if ($("#login_email_address").val() == "") {
            $("#login_email_address").focus();
          } else
            $("#login_password").focus();
        }
      })

      $(document).ready(function() {
        if ($("#valid_login").val().trim() != "1") {
          $(".alert-warning-login").show();
          $("#modal_login").modal('show');
        }
      });

      if ($("#action").val() == "login") {
        $("#modal_login").modal('show');
      }

      function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
        return pattern.test(emailAddress);
      };

      $(".accept-terms").click(function(event) {
        $.getJSON("data.ajax.php?action=check_user_exists&email_address=" + $("#email_address").val(), function(data) {
          if (data.user_exists == false) {
            $.getJSON("data.ajax.php?action=accept_terms&email_address=" + $("#email_address").val() + "&auth_code=" + $("#auth_code").val() + "&company_name=" + $("#company_name").val() + "&subdomain=" + $("#subdomain").val() + "&password=" + $("#password").val(), function(data) {
              window.location.href = "beta.php?id=" + data.programme_id + "&settings=true";
            });
          } else {
            $(".alert-account-exists").show();
            $("#modal_login").modal('show');
            $("#login_email_address").val($("#email_address").val());
            $("#login_password").focus();
          }
        });
      });

      $(".next-step-1").click(function(event) {
        event.preventDefault();
        $('.signup-form-input').each(function() {
          $(this).removeClass("form-submit-fail");
          $(this).css('border', '1px solid #ddd');
        });
        var valid = true;
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
        if (valid == true) {} else {
          $("#email_address").css('border', '1px solid #ff0000 !important');
        }
        $(".next-step-1").removeAttr('disabled');
        $(".next-step-1").html('<div class="loader"></div>');
        $(".loader").show();
      });
    </script>
  </body>
</html>