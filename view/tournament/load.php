<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireLogin();
?>
<script>

$("#load").click(function() {

   
    $.get("/mini_pro/controller/tournament/list.php", function (response) {

        if (response.status === "success") {

            let table = `
                <table border="1" cellpadding="8">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>verified</th>
                    </tr>
            `;

            response.data.forEach(function (tour) {
                table += `
                    <tr>
                        <td>${tour.tour_id}</td>
                        <td>${tour.tour_name}</td>
                        <td>${tour.start_date}</td>
                        <td>${tour.end_date}</td>
                        <td>${tour.type}</td>
                        <td>${tour.status}</td>
                        <td>${tour.verified==1 ? "verified" : "not verified"}</td>
                    </tr>
                `;
            });

            table += `</table>`;

            $("#data").html(table).show();
            $("#error").html("");

        } else {
            $("#error").html(response.message);
        }

    }, "json");

});

</script>