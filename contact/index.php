<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="all" />
		<meta name="generator" content="RapidWeaver" />
		<link rel="icon" href="http://osteele.com/favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="http://osteele.com/favicon.ico" type="image/x-icon" />
		
		<title>Contact</title>
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/styles.css"  />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/ie6.css"  /><![endif]-->
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/colors-theme-default.css"  />
		<script type="text/javascript" src="/rw_common/themes/elements/javascript.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/element/space1.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/backgrounds/coloronly.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/slogan/hidden.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/title/boxon.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/font/12.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/text/normal.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/archives/archivesoff.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/sidebar/off.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/font/11s.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/links/underlined.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/menu/mixed.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/rw_common/themes/elements/css/font/sanserif.css" />
				
		
		
		
		

		<script type="text/javascript" charset="utf-8">
			var blankImg = "/rw_common/themes/elements/blank.gif";
		</script>	

		<style type="text/css">

 			img, div, input { behavior: url("/rw_common/themes/elements/iepngfix.htc") }

		</style>


	</head>

<body>
	
<!-- This site was created using the RapidWeaver theme, Elements, developed by elixir graphics. -->
<!-- For more information on RapidWeaver themes, icon design and graphics, visit www.elixirgraphics.com -->

<div id="container">
	<div id="header">
		<div id="slogan"><h2>Software Development Leader</h2></div>
		<div id="logospot">
			
		</div>
		<div id="sitetitle">
			<div id="titlebackleft"></div><div id="titlebackmiddle"><a href="http://osteele.com/" title="Oliver Steele">Oliver Steele</a></div><div id="titlebackright"></div>
		</div>
	</div>
	<div id="main">
		<div id="menusystem">
			<div id="navcontainer">
				<ul><li><a href="../" rel="self">Home</a></li><li><a href="../about/" rel="self">About</a></li><li><a href="../projects/overview.html" rel="self">Projects</a></li><li><a href="/projects/" rel="self">Portfolio</a></li><li><a href="../writing/" rel="self">Writing</a></li><li><a href="./" rel="self" id="current">Contact</a></li></ul>
			</div>
			<div id="archiveTop"></div>
			<div id="archives">
				<div id="archivesContent">
					<div id="leftsidebar">
						<h3></h3>
						
					</div>
					
				</div>
			</div>
			<div id="archiveBottom"></div>
		</div>
		<div id="sidebarContainer">
			<div id="sidebarTop"></div>
			<div id="sidebarContent">
				<div id="sidebarIndent">
					<h3></h3>
					
				</div>
			</div>
			<div id="sidebarBottom"></div>
		</div>
		<div id="content">
			<?php
if(!array_key_exists('formMessage', $_SESSION))
$_SESSION['formMessage'] = "";
if(!array_key_exists('form_element0', $_SESSION))
$_SESSION['form_element0'] = "";
if(!array_key_exists('form_element1', $_SESSION))
$_SESSION['form_element1'] = "";
if(!array_key_exists('form_element2', $_SESSION))
$_SESSION['form_element2'] = "";
if(!array_key_exists('form_element3', $_SESSION))
$_SESSION['form_element3'] = "";
?><div class="message-text">
<?php
if (!$_SESSION['formMessage']) { 
echo 'Fill in the form below to send me an email.';
} else {
 echo $_SESSION['formMessage'];
 }
 ?>
</div><br />


<form action="./files/mailer.php" method="post" enctype="multipart/form-data">
<label>Your Name:</label> *<br /><input class="form-input-field" type="text" value="<?php echo $_SESSION['form_element0']; ?>" name="form_element0" size="40"/><br /><br /><label>Your Email:</label> *<br /><input class="form-input-field" type="text" value="<?php echo $_SESSION['form_element1']; ?>" name="form_element1" size="40"/><br /><br /><label>Subject:</label> *<br /><input class="form-input-field" type="text" value="<?php echo $_SESSION['form_element2']; ?>" name="form_element2" size="40"/><br /><br /><label>Message:</label> *<br /><textarea class="form-input-field" name="form_element3" rows="8" cols="38">
<?php echo $_SESSION['form_element3']; ?>
</textarea><br /><br /><input class="form-input-button" type="reset" name="resetButton" value="Reset" />
<input class="form-input-button" type="submit" name="submitButton" value="Submit" />

</form>

<?php session_destroy(); ?>

			<div class="clearer"></div>
		</div>
	</div>
	<div id="contentBottom"></div>

</div>
<div id="breadcrumb"></div>
<div id="footer">&copy; 2005-2009 Oliver Steele <a href="#" id="rw_email_contact">Contact Me</a><script type="text/javascript">var _rwObsfuscatedHref0 = "mai";var _rwObsfuscatedHref1 = "lto";var _rwObsfuscatedHref2 = ":st";var _rwObsfuscatedHref3 = "eel";var _rwObsfuscatedHref4 = "e@o";var _rwObsfuscatedHref5 = "ste";var _rwObsfuscatedHref6 = "ele";var _rwObsfuscatedHref7 = ".co";var _rwObsfuscatedHref8 = "m";var _rwObsfuscatedHref = _rwObsfuscatedHref0+_rwObsfuscatedHref1+_rwObsfuscatedHref2+_rwObsfuscatedHref3+_rwObsfuscatedHref4+_rwObsfuscatedHref5+_rwObsfuscatedHref6+_rwObsfuscatedHref7+_rwObsfuscatedHref8; document.getElementById('rw_email_contact').href = _rwObsfuscatedHref;</script></div>

<!-- Start Google Analytics -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-202010-1");
pageTracker._trackPageview();
} catch(err) {}</script><!-- End Google Analytics --></body>