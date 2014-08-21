
<?php
	if(isset($GLOBALS["_user"])) {
		$u = $GLOBALS["_user"];
?>

	<span id="hi_name" class="hi-name"><a id="profile" href="/account">Hi, <?php echo $u->lastname; ?></a></span>
	<span class="separator">|</span>
	<span><a href="/home/logout">Sign Out</a></span>

<?php } else { ?>

	<span id="login"><a href="/home/login">Sign In</a></span>
	<span class="separator">|</span>
	<span id="register"><a href="/home/newuser">New User?</a></span>

<?php } ?>

