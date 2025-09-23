<?php if (isset($_SESSION['sweet_alert'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['sweet_alert']['icon'] ?>',
            title: '<?= $_SESSION['sweet_alert']['title'] ?>',
            text: '<?= $_SESSION['sweet_alert']['text'] ?>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    </script>
<?php unset($_SESSION['sweet_alert']);
endif; ?>