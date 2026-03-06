<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireAdmin();
?>
<script>

$("#loadplayer").click(function () {

console.log("btn clicked");
    
    $.get("../../controller/player/list.php", function (response) {

  
console.log(response);   

        if (response.status === "success") {

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

            table += `</table>`;

            $("#dataplayer").html(table).show();
            $("#error").html("");

        } else {
            $("#error").html(response.message);
        }

    }, "json");

});

</script>