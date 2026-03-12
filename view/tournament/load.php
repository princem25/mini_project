<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireLogin();
?>
<script>

let tourOffset = 0;
const tourLimit = 5;

function loadTournaments() {
    console.log("load tournaments with offset", tourOffset);
   
    $.get("/mini_pro/controller/tournament/list.php", 
    {
        limit: tourLimit,
        offset: tourOffset
    }, 
    function (response) {

        if (response.status === "success" && response.data.length > 0) {

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

            table += `</table><br>`;
            
            if (tourOffset > 0) {
                table += `<button id="prevTourBtn">Previous</button> `;
            }
            table += `<button id="nextTourBtn">Next</button>`;

            $("#data").html(table).show();
            $("#error").html("");

            $("#prevTourBtn").off("click").on("click", function() {
                tourOffset = Math.max(0, tourOffset - tourLimit);
                loadTournaments();
            });

            $("#nextTourBtn").off("click").on("click", function() {
                tourOffset += tourLimit;
                loadTournaments();
            });

        } else if (response.status === "success" && response.data.length === 0) {
            $("#data").append("<p>No more data available.</p>");
            $("#nextTourBtn").hide();
        } else {
            $("#error").html(response.message);
        }

    }, "json");
}

$("#load").click(function() {
    tourOffset = 0;
    loadTournaments();
});

</script>