var
	wmc_controlConnection = null,
	wmc_passphrase = "";

function wmc_log (mesg, level) {
	level = level || "debug";

	switch (level)
	{
		case "error":
			console.error(mesg);
			break;

		case "warn":
			console.warn(mesg);
			break;

		case "debug":
		default:
			console.debug(mesg);
			break;
	}
	
}

function wmc_init(wmc_settings) {

	wmc_passphrase = wmc_settings.passphrase;

	if (!('WebSocket' in window)) {
		wmc_error (199, "No websocket support present!");
		return;
	}

	wmc_controlConnection = new WebSocket((wmc_settings.secure ? 'wss' : 'ws')+'://'+wmc_settings.server+':'+wmc_settings.port);

	wmc_controlConnection.onmessage = function (ev) { wmc_processCommand(ev.data); };
	wmc_controlConnection.onclose = function (ev) { wmc_onClose(ev.code, (ev.reason) ? ev.reason : "Disconnected"); };
}

function wmc_onClose(code, reason) {
	switch (code)
	{
		case 1000:
		case 1001:

			break;

		default:
			wmc_error(code, reason);
	}
}

function wmc_send (command) {
	if (wmc_controlConnection)
	{
		wmc_log("WMCLIENT SEND: "+command);

		wmc_controlConnection.send(command);
		return true;
	}
		else
	{
		wmc_error(103, "WebSocket is not connected");
		return false;
	}
}

function wmc_error(code, mesg) {
	showMessage("Backend error #"+code+": "+mesg, "error");
}

function wmc_onServiceAction(action, type, tag) {
	var actionString = "";

	switch (action) {
		case "STOP":
			actionString = "stopped";
			break;

		case "START":
			actionString = "started";
			break;

		default:
			wmc_log("Bad action: " + action);
			return;
	}

	showMessage("Instance of " +type.toLowerCase() + " '" + tag + "' has been " + actionString);
}

function wmc_processCommand(commandLine) {
	wmc_log("WMCLIENT RECV: "+commandLine);
	
	var commands = commandLine.split (' '),
		fulltext = commandLine.substr(commandLine.indexOf('#')+1),
		mainCmd = commands[0];

	switch (mainCmd)
	{

		case "INIT": 
			wmc_log("Authentication token is " + commands[1]);
			wmc_send("AUTH " + sha256(sha256(wmc_passphrase) + commands[1]));
			break;

		case 'AUTH' :
			switch (commands[1])
			{
				case "OK" :
					wmc_log("Successfully registered on the control server");
					wm_clearInstanceList();
					wmc_send("SERVICE LIST");
					break;

				default :
					wmc_log("Unknown authentication data");
					break;
			}
			break;

		case "SERVICE": 
			switch (commands[1]) {
				case "INSTANCE":
					wm_addInstance({
						type: commands[2],
						tag: commands[3],
						state: commands[4]
					});
					break;

				default:
					wmc_onServiceAction(commands[2], commands[1], commands[3]);
			}
			break;

		case "ERROR": 
			switch (+commands[1]) {
				case 101:
					showMessage("Could not authenticate", "error");
					wmc_log("Control server registration error: "+fulltext, "error");
					break;
			}
			break;
	}
}





function wmc_instanceAction(action, type, tag) {

	action = action.toUpperCase();

	switch (action) {
		case "RESTART":
		case "STOP":
		case "START":
			break;

		default:
			wmc_log("Bad action: " + action);
			return;
	}

	type = type.toUpperCase();

	switch (type) {
		case "ICECAST":
		case "LIQUIDSOAP":
			break;

		default:
			wmc_log("Bad instance type: " + type);
			return;
	}

	wmc_send("SERVICE " + type + " " + action + " " + tag);
}