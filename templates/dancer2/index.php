<?php
 // no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	JHtml::_('bootstrap.framework');
	JHtml::_('bootstrap.loadCss', true, $this->direction);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<jdoc:include type="head" />
 <link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/system/css/system.css" type="text/css" />
 <link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/system/css/general.css" type="text/css" />
 <link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/css/default.css" type="text/css" />

</head>

<body class="main">

	<div id="banner">
		 <jdoc:include type="modules" name="banner" style="xhtml" />
	</div>

	<div id="wrapper">

		<div id="sidebar1">
			<div id="logo">
				<a href="<?php echo $this->baseurl ;?>"><img src="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/images/logo.png" alt="Village Dance Theatre" width="158" height="80" /></a>
				<p>Village Dance Theatre, LLC<br />
					5907 W. Hwy 22<br />
					Oldham County<br />
					Crestwood, KY 40014<br />
					(502) 241-2113<br />
				</p>
			</div>
			<div id="navigation">
			   <jdoc:include type="modules" name="sidebar" style="xhtml" />
			</div>
		</div>

		<div id="mainContent">
			<jdoc:include type="component" />
		</div>

	</div>

	<div id="footer">
		&copy; <?php echo date('Y'); ?> Village Dance Theatre, LLC
	</div>

</body>
</html>
