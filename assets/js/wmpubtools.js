function timestampToDatetime(timestamp) {
    var stamp = new Date(+timestamp * 1000),
        time = ((stamp.getHours() < 10) ? '0' : '') + stamp.getHours() + ':' +
        ((stamp.getMinutes() < 10) ? '0' : '') + stamp.getMinutes() + ':' +
        ((stamp.getSeconds() < 10) ? '0' : '') + stamp.getSeconds(),

        date = ((stamp.getDate() < 10) ? '0' : '') + stamp.getDate() + '.' +
        ((stamp.getMonth() + 1 < 10) ? '0' : '') + (stamp.getMonth() + 1) + '.' +
        stamp.getFullYear();

    return {
        time: time,
        date: date
    };

}

function wm_onHistorySelectsChange() {
    wm_getHistory(
        $('#pubtools-history-select-station').val(),
        $('#pubtools-history-select-amount').val(),
        function(data) {
            var table =
                "<table>" +
                    "<tr>" +
                        "<th>Start time</th>" +
                        "<th>Title</th>" +
                    "</tr>";

            if (+data.status === 0) {
                data.payload.forEach(function(track) {
                    var parsedTimestamp = timestampToDatetime(track.start_time);

                    table +=
                        "<tr>" +
                            "<td title='" + parsedTimestamp.date + "' class='pubtools-history-table-time'>" + parsedTimestamp.time + "</td>" +
                            "<td>" + track.artist + "&nbsp;&ndash;&nbsp;" + track.track_title + "</td>" +
                        "</tr>";
                });

                table += "</table>";

                $('#pubtools-history-table').html(table);
            }
        }
    );
}