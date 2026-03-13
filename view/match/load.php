<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireAdmin();
?>
<script>

let matchOffset = 0;
const matchLimit = 5;

function loadMatches() {
    console.log("load matches with offset", matchOffset);
    let searchTerm = $("#searchMatch").val() || "";
    
    $.get("../../controller/match/list.php", 
    {
        limit: matchLimit,
        offset: matchOffset,
        search: searchTerm
    }, 
    function (response) {

        console.log(response);   

        if (response.status === "success" && response.data.length > 0) {

            let table = `
                <table border="1" cellpadding="8">
                    <tr>
                        <th>ID</th>
                        <th>tour_id</th>
                        <th>team1_id</th>
                        <th>team2_id</th>
                        <th>date</th>
                        <th>status</th>
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
                        <td>${match.status}</td>
                         
                    </tr>
                `;
            });

            table += `</table><br>`;
            
            if (matchOffset > 0) {
                table += `<button id="prevMatchBtn">Previous</button> `;
            }
            table += `<button id="nextMatchBtn">Next</button>`;

            $("#datamatch").html(table).show();
            $("#error").html("");

            $("#prevMatchBtn").off("click").on("click", function() {
                matchOffset = Math.max(0, matchOffset - matchLimit);
                loadMatches();
            });

            $("#nextMatchBtn").off("click").on("click", function() {
                matchOffset += matchLimit;
                loadMatches();
            });

        } else if (response.status === "success" && response.data.length === 0) {
            $("#datamatch").append("<p>No more data available.</p>");
            $("#nextMatchBtn").hide();
        } else {
            $("#error").html(response.message);
        }

    }, "json");
}

$("#loadmatches").before('<input type="text" id="searchMatch" placeholder="Search by Status or Date..." style="margin-right:10px;"><button id="btnSearchMatch" style="margin-right:10px;">Search</button>');

$("#loadmatches").click(function () {
    matchOffset = 0;
    $("#searchMatch").val("");
    loadMatches();
});

$("#btnSearchMatch").click(function() {
    matchOffset = 0;
    loadMatches();
});

</script>
