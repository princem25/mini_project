<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireAdmin();
?>
<script>

$("#loadmatches").click(function () {

console.log("btn clicked");
    
    $.get("../../controller/match/list.php", function (response) {

  
console.log(response);   

        if (response.status === "success") {

            let table = `
                <table border="1" cellpadding="8">
                    <tr>
                        <th>ID</th>
                        <th>tour_id</th>
                        <th>team1_id</th>
                        <th>team2_id</th>
                        <th>date</th>
                    </tr>
            `;

            response.data.forEach(function (match) {
                table += `
                    <tr>
                        <td>${match.match_id}</td>
                        <td>${match.tour_id}</td>
                        <td>${match.team1_id}</td>
                        <td>${match.team2_id}</td>
                        <td>${match.time}</td>
                         
                    </tr>
                `;
            });

            table += `</table>`;

            $("#datamatch").html(table).show();
            $("#error").html("");

        } else {
            $("#error").html(response.message);
        }

    }, "json");

});

</script>