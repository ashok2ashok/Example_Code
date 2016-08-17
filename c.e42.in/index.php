<?php 
	session_start(); 
	$_script_st_tm=round(microtime(true), 2);

	/******************************************************************/
	/* STANDARD INCLUDES                                              */
	/******************************************************************/
	include("tConf.php");
	include("lib/db.php");
	include("lib/utils.php");
	include("modules/utils/utils.php");
	include("modules/user/model.php");
	include("modules/user/db.php");

	// Site offline
	// if (ONLINE == "false") { include("lib/down.html"); exit; }

	/******************************************************************/
	/* GLOBALS                                                        */
	/******************************************************************/
	/******************************************************************/
	/* GLOBALS                                                        */
	/******************************************************************/
	// Navigation
	global $MOD;
	global $ENTITY;
	global $AJAX_MODE;

	// Error handling
	global $ERROR_MESSAGE;
	global $SUCCESS_MESSAGE;

	// DB data
	global $ROW;

	// Initialise globals
	$ROW=array();
	$ROW[0]=array();
	$ERROR_MESSAGE="";
	$SUCCESS_MESSAGE="";

	// Initialise globals
	$ROW=array();
	$GO=get_arg($_GET,"go");
	$DO=get_arg($_POST,"do");
	$MOD=get_arg($_GET,"mod");
	if ( $GO === '' ) { $GO='search'; }

	db_connect();

	// DO
	switch($DO) {
		case 'user_register':
			do_user_register();
		return;
		case 'user_login':
			do_user_login();
		return;
		case 'forgot_pass':
			do_user_forgot_pass_otp_json();
		return;
		case 'verify_otp':
			do_user_verify_otp();
		return;
	}

	// GO
	switch($GO) {
		case 'activate':
			do_user_activate();
		return;
		case 'logout':
			do_user_logout();
		break;
	}


	// Set output mode (a->AJAX, j->JSON)
	$output_mode=get_arg($_GET,'mode');
	if ($output_mode !== '') define('AJAX_MODE','j');

	LOG_ARR("INFO","GO",$_GET);
	LOG_ARR("INFO","GO",$_POST);

	if ( defined('AJAX_MODE') ) LOG_MSG('INFO',"^^^^^^^^^^^^^^^^^^^^^^^ AJAX START ^^^^^^^^^^^^^^^^^^^^^^^");

	// Go to login page 
	if( $GO != 'verify' && !is_loggedin() && $GO != 'insert' ) { 
		include("modules/user/login.php"); 
		return;
	}
?>

<?php if ( !defined('AJAX_MODE') ) { ?>
<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>
<?php include 'inc/page_head.php'; ?>
<?php } ?>

<?php if ( $MOD == '' && !defined('AJAX_MODE') ) { ?>
<!-- Page content -->
<div id="page-content">
    <!-- Dashboard Header -->
    <!-- For an image header add the class 'content-header-media' and an image as in the following example -->
    <div class="content-header content-header-media">
        <div class="header-section">
            <div class="row">
                <!-- Main Title (hidden on small devices for the statistics to fit) -->
                <div class="col-md-4 col-lg-6 hidden-xs hidden-sm">
                    <h1>Welcome <strong><?php echo $_SESSION['name']; ?></strong></h1>
                </div>
                <!-- END Main Title -->
            </div>
        </div>
        <!-- For best results use an image with a resolution of 2560x248 pixels (You can also use a blurred image with ratio 10:1 - eg: 1000x100 pixels - it will adjust and look great!) -->
        <img src="img/placeholders/headers/dashboard_header.jpg" alt="header image" class="animation-pulseSlow">
    </div>

	<!-- Search Form -->
	<input style="width:50%;" type="text" id="usn-search" name="usn" class="form-control" placeholder="Enter USN">
	<input style="width:50%;" type="text" id="roll-no-search" name="roll_no" class="form-control" placeholder="Enter Roll Number">
	<button type="submit" id="certificate-search" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
    <!-- END Dashboard Header -->
</div>
<!-- END Page Content -->
<?php } else { ?>
	<?php include("modules/".$MOD."/controller.php"); ?>
<?php } ?>
 
<?php if ( !defined('AJAX_MODE') ) { ?>
<?php include 'inc/page_footer.php'; ?>

<!-- Remember to include excanvas for IE8 chart support -->
<!--[if IE 8]><script src="js/helpers/excanvas.min.js"></script><![endif]-->

<?php include 'inc/template_scripts.php'; ?>

<!-- Form Validations -->
<script src="<?php echo BASEURL; ?>js/pages/formsValidation.js"></script>
<script>$(function(){ FormsValidation.init(); });</script>

<!-- Google Maps API + Gmaps Plugin, must be loaded in the page you would like to use maps (Remove 'http:' if you have SSL) -->
<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script src="<?php echo BASEURL; ?>js/helpers/gmaps.min.js"></script>

<!-- Load and execute javascript code used only in this page -->
<script src="<?php echo BASEURL; ?>js/pages/index.js"></script>
<script>$(function(){ Index.init(); });</script>

<?php include 'inc/template_end.php'; ?>
<?php } ?>
<?php
if ( defined('AJAX_MODE') ) {LOG_MSG('INFO',"^^^^^^^^^^^^^^^^^^^^^^^ AJAX END ^^^^^^^^^^^^^^^^^^^^^^^");exit;}

$_script_tm=round(microtime(true)-$_script_st_tm, 2);
db_close();

LOG_ARR("INFO","GO",$_GET);
LOG_ARR("INFO","DO",$_POST);

LOG_MSG('INFO',"===== END: TIME=[${_script_tm}s] =====\n"); 

?>
