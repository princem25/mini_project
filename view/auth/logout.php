<?php
require_once __DIR__ . '/../../config/auth_check.php';
requireLogin();
?>

<!-- Logout button fragment - included via require_once -->
<div class="page-actions">
    <button id="logout">Logout</button>
</div>

<script>
    $(document).ready(function() {

        $("#logout").click(function() {
            $.get("/mini_pro/controller/auth/logout.php", function(resp) {
                console.log(resp);

                if (resp.status === "ok") {
                    window.location.href = "/mini_pro/view/auth/login.php";
                } else {
                    $("#error").html(resp.message);
                }

            }, "json");
        });

    });


</script>