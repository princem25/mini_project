    
<script>
 

    // Load teams into dropdown
    function loadTeams() {
        $.get("/mini_pro/controller/teamcontroller/team_data.php", function (response) {

            if (response.status === "success") {

                let options = '<option value="">-- Select Team --</option>';

                response.data.forEach(function(team) {
                    options += `<option value="${team.team_id}">
                                    ${team.team_name}
                                </option>`;
                });

                $("#teamSelect").html(options);
            }

        }, "json");
    }

    
    loadTeams();

 
</script>