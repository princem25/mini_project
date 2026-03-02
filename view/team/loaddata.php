<script>

$("#loadteams").click(function () {

 

   
    $.get("../../controller/teamcontroller/team_data.php", function (response) {

  
console.log("resp send");

        if (response.status === "success") {

            let table = `
                <table border="1" cellpadding="8">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
            `;

            response.data.forEach(function (team) {
                table += `
                    <tr>
                        <td>${team.team_id}</td>
                        <td>${team.team_name}</td>
                         
                    </tr>
                `;
            });

            table += `</table>`;

            $("#datateam").html(table).show();

        } else {
            $("#error").html(response.message);
        }

    }, "json");

});

</script>