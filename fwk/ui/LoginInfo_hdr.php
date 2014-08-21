<?php include __DIR__ . "/header.php"; ?>

<head>
<title>
	<?php
		if(!is_null($title)) {
			echo $title;
		} else {
			echo "Smyle Developer";
		}
	?>
</title>
</head>

<style type="text/css">

	#<?php echo $selected?> a {
		color: #FFF;
	}
</style>

<body>

	<div class="page">
		<div class="stage shadow">
			<div id="header" class="header header-bg" style="position: relative">
				<div class="logo-smyledev"></div>

				<div class="hdr-top">
					<div class="top-left sub-title">
						<?php include "title.php";?>
					</div>
					<div class="top-right">
						<?php include __DIR__ . "/HdrLoginMenu.php"; ?>
					</div>
				</div>

				<div id="menu_div" class="blog-menu">
					<ul class="hdr_menu_cz">
						<li id='home'><a href="/home">HOME</a></li>
						<!-- <li id='smylejs'><a href="/docs/smylejs">Smyle.js</a></li> -->
						<li id='webgraph'><a href="/graph">WEBGRAPH DEMO</a></li>
						<li id='downloads'><a href="/desk">WEBDESK</a></li>
						<li id='contact'><a href="/home/contact">CONTACT</a></li>
					</ul>
				</div>
			</div>

			<div class="content">

