<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireLogin();
?>
<script>

let teamOffset = 0;
const teamLimit = 5;

function loadTeams() {
    console.log("load teams with offset", teamOffset);
    
    $.get("../../controller/team/list.php", 
    {
        limit: teamLimit,
        offset: teamOffset
    }, 
    function (response) {

        console.log("resp send");

        if (response.status === "success" && response.data.length > 0) {

            let table = `
                <table border="1" cellpadding="8">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Tour_id</th>
                        <th>verified</th>
                    </tr>
            `;

            response.data.forEach(function (team) {
                table += `
                    <tr>
                        <td>${team.team_id}</td>
                        <td>${team.team_name}</td>
                        <td>${team.tour_id}</td>
                        <td>${team.verified==1 ? "verified" : "not verified"}</td>
                         
                    </tr>
                `;
            });

            table += `</table><br>`;
            
            if (teamOffset > 0) {
                table += `<button id="prevTeamBtn">Previous</button> `;
            }
            table += `<button id="nextTeamBtn">Next</button>`;

            $("#datateam").html(table).show();
            $("#error").html("");

            $("#prevTeamBtn").off("click").on("click", function() {
                teamOffset = Math.max(0, teamOffset - teamLimit);
                loadTeams();
            });

            $("#nextTeamBtn").off("click").on("click", function() {
                teamOffset += teamLimit;
                loadTeams();
            });

        } else if (response.status === "success" && response.data.length === 0) {
            $("#datateam").append("<p>No more data available.</p>");
            $("#nextTeamBtn").hide();
        } else {
            $("#error").html(response.message);
        }

    }, "json");
}

$("#loadteams").click(function () {
    teamOffset = 0;
    loadTeams();
});

</script>