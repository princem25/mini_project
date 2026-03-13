<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireLogin();
?>
<script>

let playerOffset = 0;
const playerLimit = 5;

function loadPlayers() {
    console.log("load players with offset", playerOffset);
    
    $.get("../../controller/player/list.php", 
    {
        limit: playerLimit,
        offset: playerOffset
    }, 
    function (response) {

        console.log(response);   

        if (response.status === "success" && response.data.length > 0) {

            let table = `
                <table border="1" cellpadding="8">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>team_name</th>
                    </tr>
            `;

            response.data.forEach(function (player) {
                table += `
                    <tr>
                        <td>${player.user_id}</td>
                        <td>${player.name}</td>
                        <td>${player.team_id}</td>
                         
                    </tr>
                `;
            });

            table += `</table><br>`;
            
            if (playerOffset > 0) {
                table += `<button id="prevPlayerBtn">Previous</button> `;
            }
            table += `<button id="nextPlayerBtn">Next</button>`;

            $("#dataplayer").html(table).show();
            $("#error").html("");

            $("#prevPlayerBtn").off("click").on("click", function() {
                playerOffset = Math.max(0, playerOffset - playerLimit);
                loadPlayers();
            });

            $("#nextPlayerBtn").off("click").on("click", function() {
                playerOffset += playerLimit;
                loadPlayers();
            });

        } else if (response.status === "success" && response.data.length === 0) {
            $("#dataplayer").append("<p>No more data available.</p>");
            $("#nextPlayerBtn").hide();
        } else {
            $("#error").html(response.message);
        }

    }, "json");
}

$("#loadplayer").click(function () {
    playerOffset = 0;
    loadPlayers();
});

</script>
