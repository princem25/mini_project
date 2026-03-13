    
<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireAdmin();
?>
<script>
 

    // Load teams into dropdown
    function loadTeams() {
        $.get("/mini_project/controller/team/list.php", function (response) {

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
