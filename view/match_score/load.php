<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireAdmin();
?>
<script>

$("#matchscore").click(function () {

console.log("btn clicked");
    
    $.get("../../controller/score/list.php", function (response) {

  
console.log(response);   

        if (response.status === "success") {

            let table = `
                <table border="1" cellpadding="8">
                    <tr>
                        <th>ID</th>
                        <th>match_id</th>
                        <th>team1_score</th>
                        <th>team2_score</th>
                        <th>winner_id</th>
                    </tr>
            `;

            response.data.forEach(function (match) {
                table += `
                    <tr>
                        <td>${match.score_id}</td>
                        <td>${match.match_id}</td>
                        <td>${match.team1_score}</td>
                        <td>${match.team2_score}</td>
                        <td>${match.winner_team_id == null ? "draw" : match.winner_team_id}</td>
                     
                         
                    </tr>
                `;
            });

            table += `</table>`;

            $("#datamatchscore").html(table).show();
            $("#error").html("");

        } else {
            $("#error").html(response.message);
        }

    }, "json");

});

</script>