<!DOCTYPE html>
<html>
<head>
	<title>Veckohandla, inköpslista</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>/js/underscore-1.4.3.js"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>/js/backbone-0.9.10.js"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>/js/facebook-user-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo ROOT; ?>/js/master.js"></script>
	<link type="text/css" rel="stylesheet" href="<?php echo ROOT; ?>/css/style.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/themes/smoothness/jquery-ui.css" type="text/css" media="all" />
</head>
<body>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
	console.log('fbAsyncInit');
    // init the FB JS SDK
    FB.init({
      appId      : '<?php echo FB_APP_ID; ?>', // App ID from the App Dashboard
      channelUrl : '<?php echo ROOT; ?>/channel.html', // Channel File for x-domain communication
	  status     : false, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : false  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here
	jQuery(document).trigger('FBSDKLoaded');

  };

  // Load the SDK's source Asynchronously
  // Note that the debug version is being actively developed and might 
  // contain some type checks that are overly strict. 
  // Please report such bugs using the bugs tool.
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/sv_SE/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));
</script>