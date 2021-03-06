<!DOCTYPE html>

<!--
	
	This is WaveManager Front-end.
	Its current state is UNDER DEVELOPMENT.
	Please don't use it in production.

	(c) 2019 Asterleen ~ https://asterleen.com

-->

<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="assets/style/style.css">
	<script type="text/javascript" src="/assets/js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/lib/sha256.min.js"></script>
	<script type="text/javascript" src="/assets/js/lib/ace.js"></script>
	<script type="text/javascript" src="/assets/js/lib/ace-js.js"></script>
	<script type="text/javascript" src="/assets/js/lib/ace-theme-tomorrownight.js"></script>
	<script type="text/javascript" src="/assets/js/wmclient.js"></script>
	<script type="text/javascript" src="/assets/js/wmapi.js"></script>
	<script type="text/javascript" src="/assets/js/wmgui.js"></script>
	<title>WaveManager Core</title>
</head>
<body>

	<div id="messages-wrapper">
		<div class="message error">Oh shit</div>
		<div class="message info">Not a real shish</div>
		<div class="message warn">Some shit</div>
	</div>
	
	<div id="wrapper">
	<h1>WaveManager Web Console</h1>
	<div id="subheader">Version <span id="wm-version">1.3.3.7</span></div>

	<div id="tabs">
		<div class="tab-pointer active" id="tab-instances" data-tab-name="instances">
			Instances
		</div>
		<div class="tab-pointer" id="tab-library" data-tab-name="library">
			Library
		</div>
		<div class="tab-pointer" id="tab-admin" data-tab-name="admin">
			Administration
		</div>
		<div class="tab-pointer" id="tab-log" data-tab-name="log">
			Logs
		</div>
		<div class="tab-pointer" id="tab-about" data-tab-name="about">
			About
		</div>
	</div>
	<div id="content">
		<div class="tab-content" id="tab-content-instances">
			<h2>Stations</h2>
			<div id="instances-list">
				<div style="text-align: center;">Waiting for backend...</div>
			</div>
			<div id="instances-tools" class="buttonset">
				<div class="control button" id="instances-new" title="Create a new instance">New...</div>
			</div>
		</div>

		<!-- <div class="tab-content" id="tab-content-editor">
			<div id="editor-wrap">
			<div id="editor-header-wrap">
				<div id="editor-fullscreen-toggler" class="editor-fullscreen expand" title="Toggle editor fullscreen mode"></div> 
				<h2>Configuration editor</h2>
			</div>
			<input type="text" class="control text" id="instance-name" placeholder="Name of the instance">
			<div id="instance-code"></div>

			<div id="editor-tools" class="buttonset">
				<div class="control button" id="editor-save" title="Save current instance">Save</div>
				<div class="control button" id="editor-reset" title="Clear all fields">Reset</div>
			</div>
			</div>
		</div> -->

		<div class="tab-content" id="tab-content-library">
			<h2>Station library & stats</h2>
			<div id="library-json-update">
				<h3>Import artist library from JSON</h3>
				<form id="artistsJsonForm" method="POST" action="/api/library/import" style="display:none;">
					<input type="file" id="artistsFile" name="artists" class="control">
				</form>
				<button onclick="wm_onImportFileChoose()">choose file</button>
				<button onclick="wm_onImportSend()">go</button>
				<textarea class="log" id="artistsJsonUploadResults"></textarea>
			</div>
		</div>

		<div class="tab-content" id="tab-content-admin">
			<h2>Administration</h2>
			<div>
				<h3>Set new password</h3>
				<input type="password" id="newPasswordText">
				<button onclick="wm_onSetPassword()">set</button>
			</div>
		</div>

		<div class="tab-content" id="tab-content-log">
			<h2>Instance log</h2>
			<div id="event-log"></div>		
			<div id="log-tools" class="buttonset">
				<div class="control button" id="log-clear" title="Clear the log">Clear</div>
			</div>
		</div>

		<div class="tab-content" id="tab-content-about">
			<img src="/assets/gfx/logo.png" style="margin: 0 auto; display: block; height: 64px;">
			<h2>About</h2>
			<div class="about-text" id="about-info">
				You are using <b>WaveManager</b>, a web radio station management system. It's like systemd, but for webradio.
				<br>
				WaveManager is made for developers and technicians who own multiple radio stations to manage them in a little more mobile and handy way. It allows you to start, stop and restart the radio station instances directly from the web interface.
				<br>
				WaveManager is made specially to work with Liquidsoap and Icecast.
			</div>

			<div class="about-text" id="about-legal">
				Copyright &copy; 2019 <a href="https://asterleen.com">Asterleen</a>
				<br><br>
				This software is licensed under <a href="/LICENSE">BSD</a> License.
				<br>
				Uses third-party software, see <a href="/LICENSE-3rdparty">LICENSE-3rdparty</a>.
			</div>
		</div>
	</div>

	<script type="text/javascript">
		// wmc_controlConnection = new WebSocket((wmc_settings.secure ? 'wss' : 'ws')+'://'+wmc_settings.server+':'+wmc_settings.port);
		var API_CSRF_TOKEN = '<?php echo($content['csrf_token']); ?>';

		$(document).ready(wm_init({
			secure: <?php echo ($content['backend']['secure']); ?>,
			server: '<?php echo ($content['backend']['address']); ?>',
			port: <?php echo ($content['backend']['port']); ?>,
			passphrase: '<?php echo ($content['backend']['passphrase']); ?>'
		}));
	</script>

	</div>
</body>
</html>