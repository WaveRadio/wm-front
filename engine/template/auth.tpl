<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<link rel="stylesheet" type="text/css" href="/assets/style/fonts.css">
	<link rel="stylesheet" type="text/css" href="/assets/style/auth.css">
	<title><?php echo($content['title']); ?></title>
</head>
<body>
	<div id="auth-form">
		<img src="/assets/gfx/logo.png">
		<form name="auth" action="/auth/go" method="post">
			<input type="text" name="username" placeholder="<?php echo($content['text_username']); ?>">
			<input type="password" name="password" placeholder="<?php echo($content['text_password']); ?>">
			<input type="submit" value="<?php echo($content['text_submit']); ?>">
		</form>
	</div>
</body>
</html>