<?php
 // no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	JHtml::_('bootstrap.framework');
	JHtml::_('bootstrap.loadCss', true, $this->direction);

  $app    = JFactory::getApplication();
  $menu   = $app->getMenu();
  $active = $menu->getActive();
  $class  = $active->alias;
?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>

	<jdoc:include type="head" />

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/system/css/system.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/system/css/general.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/css/default.css" type="text/css" />

	<?php
		$css = $this->baseurl . '/templates/' . $this->template . '/css/' . $class . '.css';
		// check for stylesheet of active page
		if(file_exists($_SERVER['DOCUMENT_ROOT'] . $css)) {
			echo '<link rel="stylesheet" href="' . $css . '" type="text/css" />';
		}
	?>

	<script src="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/js/nav.js"></script>
</head>

<body class="main">
	<div id="pg-bkgnd"></div>

	<div id="banner">
		<div class="company">
			<a class="company-logo" href="<?php echo $this->baseurl ;?>">
				<img src="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/images/logo.png" alt="Village Dance Theatre">
			</a>
			<div class="company-info">
				<span class="company-name">Village Dance Theatre, LLC</span>
				<span>5907 W. Hwy 22</span>
				<span>Oldham County</span>
				<span class="location">Crestwood, KY 40014</span>
				<span class="phone">(502) 241-2113</span>
			</div>
		</div>

		<div class="menu-icon">
			<span class="m1"></span>
			<span class="m2"></span>
			<span class="m3"></span>
			<span class="m4"></span>
		</div>
		<jdoc:include type="modules" name="menu" title="Main Menu" style="html5" />

	</div>

	<div id="wrapper">

		<div id="sidebar">
	   <jdoc:include type="modules" name="menu" title="Main Menu" style="html5" />
	   <jdoc:include type="modules" name="sidebar" title="Find Us on Facebook" style="html5" />
		</div>

		<div id="mainContent" class="<?php echo $class ;?>">
			<jdoc:include type="component" style="html5" />
		</div>

	</div>

	<div id="footer">
		&copy; <?php echo date('Y'); ?> Village Dance Theatre, LLC
	</div>

</body>
</html>
