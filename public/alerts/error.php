<?php
// error
$message = flash('error');
if (!empty($message)) {
    $errorData = json_encode($message); ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: <?= $errorData ?>,
        });
    </script>
<?php  }

// success
$message = flash('success');
if (!empty($message)) {
    $errorData = json_encode($message); ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'موفق',
            text: <?= $errorData ?>,
        });
    </script>
<?php  } ?>

<script>
    function confirmAction(message, url) {
        Swal.fire({
            title: 'تأیید عملیات',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'بله، ادامه بده',
            cancelButtonText: 'نه لغو کن',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>