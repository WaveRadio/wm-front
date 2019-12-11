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
	<link rel="stylesheet" type="text/css" href="/assets/style/style.css">
	<script type="text/javascript" src="/assets/js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/wmgui.js"></script>
	<script type="text/javascript" src="/assets/js/wmapi.js"></script>
	<script type="text/javascript" src="/assets/js/wmpubtools.js"></script>
	<title>WaveManager Public Tools</title>
</head>
<body>

	<div id="messages-wrapper">
		<div class="message error"></div>
		<div class="message info"></div>
		<div class="message warn"></div>
	</div>
	
	<div id="wrapper">
		<h1>WaveManager Public Tools</h1>
		<div id="subheader">For those who knows</div>

		<div id="tabs">
			<div class="tab-pointer active" id="tab-history" data-tab-name="history">
				History
			</div>
			<div class="tab-pointer" id="tab-current" data-tab-name="current">
				Current track
			</div>
		</div>
		<div id="content">
			<div class="tab-content" id="tab-content-history">
				<h2>Track history browser</h2>
				Station:
				<select id="pubtools-history-select-station" onchange="wm_onHistorySelectsChange()">
					<?php foreach($content['stations'] as $station): ?>
						<option value="<?php echo ($station['station_tag']); ?>"><?php echo ($station['station_tag']); ?></option>
					<?php endforeach; ?>
				</select>
				Amount:
				<select id="pubtools-history-select-amount" onchange="wm_onHistorySelectsChange()">
					<?php foreach($content['amounts'] as $amount): ?>
						<option value="<?php echo ($amount); ?>"><?php echo ($amount); ?></option>
					<?php endforeach; ?>
				</select>
				<div id="pubtools-history-table"></div>
			</div>
			<div class="tab-content" id="tab-content-current">
				<h2>Current track browser</h2>
				<h3>This tool is under construction yet.</h3>
			</div>

		</div>
	</div>

	<script type="text/javascript">
		wm_initTabs('history');
		wm_onHistorySelectsChange();
	</script>
</body>
</html>