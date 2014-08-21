
<?php  include __DIR__ . "/header.php";?>

<head>
<title>Smyle Web: ERROR</title>
</head>

<!-- css-group-start -->
<link rel="stylesheet" type="text/css" href="/ui/smyle/src/common/css/error.css"/>
<link rel="stylesheet" type="text/css" href="/ui/smyle/src/common/css/page.css"/>
<link rel="stylesheet" type="text/css" href="/ui/smyle/src/common/css/common.css"/>
<link rel="stylesheet" type="text/css" href="/ui/smyle/src/common/css/stage.css"/>
<!-- css-group-end -->


<script type="text/JavaScript">
	$2("page", ["sw-common"], function(S) {});
</script>

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

			</div>

			<div class="content">

				<div class="error-content _content silver-grad">

					<div class="error">

						<div class="_title">ERROR</div>

						<?php

							if(isset($GLOBALS["msg"])) {
								echo "<br><div class='title-1'>" . $GLOBALS['msg'] . "</div>";
							}

							if(isset($GLOBALS["exp"])) {
								echo "<p>" . $GLOBALS['exp']->getMessage() . "</p>";
							}
						?>
					</div>

				</div>

			</div>

			<?php include __DIR__ . "/footer.php"?>

		</div>
	</div>
</body>
</html>

