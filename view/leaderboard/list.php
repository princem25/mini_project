<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireLogin();
?>
<script>

let currentOffset = 0;
const limit = 5;

function loadLeaderboard() {
    var tourid = $('#tourselect').val();
    console.log("send", tourid, "offset", currentOffset);

    if(!tourid){
        $("#error").html("Please select a tournament");
        return;
    }

    $.post("/mini_pro/controller/leaderboard/list.php", 
    { 
        tourid: tourid,
        limit: limit,
        offset: currentOffset
    }, 

    function(response) {
        console.log("response:", response);

        if (response.status === "success" && response.data.length > 0) {

            let table = `
                <table border="1" cellpadding="8">
                    <tr>
                        <th>Team</th>
                        <th>Matches Played</th>
                        <th>Wins</th>
                        <th>Draws</th>
                        <th>Losses</th>
                        <th>Total Points</th>
                    </tr>
            `;

            response.data.forEach(function(team) {
                table += `
                    <tr>
                        <td>${team.team_name}</td>
                        <td>${team.matches_played}</td>
                        <td>${team.wins}</td>
                        <td>${team.draws}</td>
                        <td>${team.losses}</td>
                        <td>${team.total_points}</td>
                    </tr>
                `;
            });

            table += `</table><br>`;
            if (currentOffset > 0) {
                table += `<button id="prevBtn">Previous</button> `;
            }
            table += `<button id="nextBtn">Next</button>`;

            $("#data").html(table).show();
            $("#error").html("");

            // Re-bind buttons
            $("#prevBtn").off("click").on("click", function() {
                currentOffset = Math.max(0, currentOffset - limit);
                loadLeaderboard();
            });

            $("#nextBtn").off("click").on("click", function() {
                currentOffset += limit;
                loadLeaderboard();
            });

        } else if (response.status === "success" && response.data.length === 0) {
            $("#data").append("<p>No more data available.</p>");
            $("#nextBtn").hide();
        } else {
            $("#error").html(response.message);
        }

    }, "json")

    .fail(function() {
        $("#error").html("Server error while loading leaderboard");
    });
}

$("#load").click(function() {
    currentOffset = 0; // Start from first record (0-indexed)
    loadLeaderboard();
});

</script>   