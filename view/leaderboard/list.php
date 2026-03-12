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
         $("#dataleaderboard").html("");
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

            let table = "";

            // Badges showing the type and status of the current tournament
            table += `
                <div style="margin-bottom: 15px;">
                    <span style="background-color: #4a6fa5; color: white; padding: 5px 10px; border-radius: 5px; font-size: 0.85rem; font-weight: bold; text-transform: capitalize;">
                        Type: ${response.tour_type}
                    </span>
                    <span style="background-color: ${response.tour_status === 'completed' ? '#28a745' : '#17a2b8'}; color: white; padding: 5px 10px; border-radius: 5px; font-size: 0.85rem; font-weight: bold; text-transform: capitalize; margin-left: 8px;">
                        Status: ${response.tour_status}
                    </span>
                </div>
            `;

            if (response.tour_status === "completed" && currentOffset === 0) {
                let winnerName = "";
                let subtext = "";
                let titleText = "";

                if(response.tour_type === "knockout") {
                    if(response.knockout_champion) {
                        winnerName = response.knockout_champion.team_name;
                        titleText = "🏆 Knockout Champion! 🏆";
                        subtext = "Winner of the Finals!🥇";
                    }
                } else {
                    let leagueWinner = response.data[0];
                    if(leagueWinner) {
                        winnerName = leagueWinner.team_name;
                        titleText = "🏆 League Winner! 🏆";
                        subtext = `with ${leagueWinner.total_points} total points 🎯`;
                    }
                }

                if(winnerName !== "") {
                    table += `
                        <div style="background: linear-gradient(135deg, #fceabb, #f8b500); padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; color: #333; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <h3 style="margin: 0; font-size: 1.5rem;">${titleText}</h3>
                            <p style="margin: 5px 0 0 0; font-size: 1.2rem; font-weight: bold;">${winnerName}</p>
                            <p style="margin: 5px 0 0 0; font-size: 0.9rem;">${subtext}</p>
                        </div>
                    `;
                }
            }

            table += `
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

            response.data.forEach(function(team, index) {
                let rowStyle = "";
                let namePrefix = "";
                if (response.tour_status === "completed" && currentOffset === 0 && index === 0) {
                    rowStyle = "background-color: #fffae6; font-weight: bold;";
                    namePrefix = "👑 ";
                }
                
                table += `
                    <tr style="${rowStyle}">
                        <td>${namePrefix}${team.team_name}</td>
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

            $("#dataleaderboard").html(table).show();
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
             $("#dataleaderboard").html("");
            $("#error").html(response.message);
        }

    }, "json")

    .fail(function() {
        $("#dataleaderboard").html("");
        $("#error").html("Server error while loading leaderboard");
    });
}

$("#loadleaderboard").click(function() {
    currentOffset = 0; // Start from first record (0-indexed)
    loadLeaderboard();
});

</script>   