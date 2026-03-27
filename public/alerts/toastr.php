<link rel="stylesheet" href="<?= asset('public/assets/style/toastr.min.css') ?>" />
<script src="<?= asset('public/assets/js/toastr.js') ?>"></script>

<?php
$message = flash('error');
if (!empty($message)) {
    $errorData = json_encode($message); ?>
    <script>
        toastr.error(<?= $errorData ?>, '', {
            closeButton: true,
            progressBar: true,
            timeOut: 5000, 
            extendedTimeOut: 1000,
            positionClass: "toast-bottom-right"
        });
    </script>
<?php }

$message = flash('success');
if (!empty($message)) {
    $successData = json_encode($message); ?>
    <script>
        toastr.success(<?= $successData ?>, '', {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 1000,
            positionClass: "toast-bottom-right"
        });
    </script>
<?php }

$message = flash('warning');
if (!empty($message)) {
    $warningData = json_encode($message); ?>
    <script>
        toastr.warning(<?= $warningData ?>, '', {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 1000,
            positionClass: "toast-bottom-right"
        });
    </script>
<?php }

$message = flash('info');
if (!empty($message)) {
    $infoData = json_encode($message); ?>
    <script>
        toastr.info(<?= $infoData ?>, '', {
            closeButton: true,
            progressBar: true,
            timeOut: 5000,
            extendedTimeOut: 1000,
            positionClass: "toast-bottom-right"
        });
    </script>
<?php }
?>
