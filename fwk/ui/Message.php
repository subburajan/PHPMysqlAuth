
<?php include __DIR__ . "/header.php"; ?>

<head>
<title>Smyle Web</title>
</head>

<!-- css-group-start -->
<link rel="stylesheet" type="text/css" href="/ui/smyle/src/common/css/message.css"/>
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
			
				<div class="message-content _content silver-grad">

					<div class="message">
												
						<?php 

							if(isset($GLOBALS["msg"])) {
								echo "<p>" . $GLOBALS['msg'] . "</p>";						
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

