 
    <script>
            $("#load").click(function() {

        $.get("/mini_pro/controller/tourcontroller/tourdata_control.php", function(response) {

            if (response.status === "success") {

                let table = `
                    <table border="1" cellpadding="8">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                `;

                response.data.forEach(function(tour) {
                    table += `
                        <tr>
                            <td>${tour.tour_id}</td>
                            <td>${tour.tour_name}</td>
                            <td>${tour.start_date}</td>
                            <td>${tour.end_date}</td>
                            <td>${tour.type}</td>
                            <td>${tour.status}</td>
                        </tr>
                    `;
                });

                table += `</table>`;

                $("#data").html(table);

            } else {
                $("#error").html(response.message);
            }

        }, "json");
    });

 

    </script>
 