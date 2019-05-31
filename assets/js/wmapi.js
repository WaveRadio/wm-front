function wm_apiRequest(method, data, onSuccess, type) {
	data = data || {};
	onSuccess = (typeof onSuccess === "function" ? onSuccess : null);
	type = (type.toLowerCase() === "post" ? "POST" : "GET");

	data['csrf_token'] = API_CSRF_TOKEN;
 
	$.ajax("/api/" + method, {
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

function wm_onSetPassword() {
	wm_apiRequest("user/setpassword", {
		password: $("#newPasswordText").val()
	}, function(status) {
		console.log(status);
	}, "post");

	return false;
}