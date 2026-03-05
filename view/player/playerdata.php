    <script>

$("#loadplayer").click(function () {

console.log("btn clicked");
    
    $.get("../../controller/playercontroller/player_fresh.php", function (response) {

  
console.log("resp send");   

        if (response.status === "success") {

            let table = `
                <table border="1" cellpadding="8">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Team_id</th>
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

        } else {
            $("#error").html(response.message);
        }

    }, "json");

});

</script>