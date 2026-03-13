
            <script>
                // Load teams into dropdown
                function loadTours() {

                    $.get("/mini_project/controller/tournament/listverified.php", function(response) {

                        if (response.status === "success") {

                            let options = '<option value="">-- Select Tour --</option>';

                            response.data.forEach(function(tour) {
                                options += `<option value="${tour.tour_id}">
                                        ${tour.tour_name}
                                    </option>`;
                            });

                            $("#tourselect").html(options);
                        }

                    }, "json");
                }


                loadTours();
            </script>
