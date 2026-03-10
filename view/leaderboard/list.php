<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireLogin();
?>
<script>

$("#load").click(function() {

    var tourid = $('#tourselect').val();
       console.log("send", tourid);

    if(!tourid){
        $("#error").html("Please select a tournament");
        return;
    }

 

    $.post("/mini_pro/controller/leaderboard/list.php", 
    { tourid: tourid }, 

    function(response) {

        console.log("response:", response);

        if (response.status === "success") {

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

            table += `</table>`;

            $("#data").html(table).show();
            $("#error").html("");

        } else {

            $("#error").html(response.message);

        }

    }, "json")

    .fail(function() {
        $("#error").html("Server error while loading leaderboard");
    });

});

</script>