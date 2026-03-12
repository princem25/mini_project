<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireAdmin();
?>
<script>

let matchScoreOffset = 0;
const matchScoreLimit = 5;

function loadMatchScores() {
    console.log("load match scores with offset", matchScoreOffset);
    
    $.get("../../controller/score/list.php", 
    {
        limit: matchScoreLimit,
        offset: matchScoreOffset
    }, 
    function (response) {

        console.log(response);   

        if (response.status === "success" && response.data.length > 0) {

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

            table += `</table><br>`;
            
            if (matchScoreOffset > 0) {
                table += `<button id="prevMatchScoreBtn">Previous</button> `;
            }
            table += `<button id="nextMatchScoreBtn">Next</button>`;

            $("#datamatchscore").html(table).show();
            $("#error").html("");

            $("#prevMatchScoreBtn").off("click").on("click", function() {
                matchScoreOffset = Math.max(0, matchScoreOffset - matchScoreLimit);
                loadMatchScores();
            });

            $("#nextMatchScoreBtn").off("click").on("click", function() {
                matchScoreOffset += matchScoreLimit;
                loadMatchScores();
            });

        } else if (response.status === "success" && response.data.length === 0) {
            $("#datamatchscore").append("<p>No more data available.</p>");
            $("#nextMatchScoreBtn").hide();
        } else {
            $("#error").html(response.message);
        }

    }, "json");
}

$("#matchscore").click(function () {
    matchScoreOffset = 0;
    loadMatchScores();
});

</script>