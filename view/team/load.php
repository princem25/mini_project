<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireAdmin();
?>
<script>

$("#loadteams").click(function () {

console.log("btn clicked");
    
    $.get("../../controller/team/list.php", function (response) {

  
console.log("resp send");

        if (response.status === "success") {

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

            table += `</table>`;

            $("#datateam").html(table).show();
            $("#error").html("");

        } else {
            $("#error").html(response.message);
        }

    }, "json");

});

</script>