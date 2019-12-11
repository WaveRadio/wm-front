function wm_apiRequest(method, data, onSuccess, type, isPublic) {
	data = data || {};
	onSuccess = (typeof onSuccess === "function" ? onSuccess : null);
	type = ((type && type.toLowerCase() === "post") ? "POST" : "GET");

	data['csrf_token'] = (typeof API_CSRF_TOKEN !== "undefined") ? API_CSRF_TOKEN : undefined;

	$.ajax((isPublic ? "/public/" : "/api/") + method, {
		data: data,
		dataType: "json",
		error: function(jq, status, error) {
			showMessage("Request failed: " + status, "error");
			console.error("Request failed:", jq, status, error);
		},
		method: type,
		success: onSuccess
	});
}

function wm_getHistory(station, amount, onSuccess) {
	wm_apiRequest("history", {
		amount: amount,
		station: station
	}, onSuccess, "GET", true);
}

function wm_onSetPassword() {
	wm_apiRequest("user/setpassword", {
		password: $("#newPasswordText").val()
	}, function(status) {
		switch (status['status']) {
			case 0: 
				showMessage("Successfully changed your password");
				break;

			default:
				showMessage("Cannot change password: #" + res['status'] + " " + res['payload'], "error");
				break;
		}
	}, "post");

	return false;
}

function wm_onImportFileChoose() {
	$("#artistsFile").click();
}

function wm_onImportSend() {
	if (!$("#artistsFile").val()) {
		showMessage("No file chosen!", "warn");
		return;
	}

	showMessage("Now processing your JSON. It may take about a minute.");
	var uploadForm = document.getElementById('artistsJsonForm');

	$.ajax({
		type: "POST",
		url: "/api/library/import?csrf_token=" + API_CSRF_TOKEN,
		dataType: "json",
		data: new FormData(uploadForm),
		contentType: false,
		processData: false,
		success: function(res) {
			switch (+res['status']) {
				case 0:
					showMessage("Processing finished, see logs!");
					var textArea = $("#artistsJsonUploadResults");
					textArea.val(res['payload']);
					break;

				default:
					showMessage("Upload failed: #" + res['status'] + " " + res['payload'], "error");
					break;
			}
		},

		error: function(jq, status, error) {
			showMessage("JSON upload failed: " + status, "error");
		}
	});
}