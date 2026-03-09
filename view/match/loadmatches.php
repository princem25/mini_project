 <script>
 

    // Load matchs into dropdown
    function loadmatches() {
        $.get("/mini_pro/controller/match/list.php", function (response) {

            if (response.status === "success") {

                let options = '<option value="">-- Select Match --</option>';

                response.data.forEach(function(match) {
                    options += `<option value="${match.match_id}">
                                    ${match.match_id}
                                </option>`;
                });

                $(".matchselect").html(options);
                 
            }

        }, "json");
    }

    
    loadmatches();

 
</script>