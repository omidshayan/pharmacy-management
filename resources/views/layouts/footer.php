<script src="<?= asset('public/assets/js/script.js') ?>"></script>
<script src="<?= asset('lib/datePicker/datePicker.min.js') ?>"></script>

<!-- <script src="<?= asset('lib/timePicker/persian-datepicker.min.js') ?>"></script>
<script src="<?= asset('lib/timePicker/persian-date.min.js') ?>"></script> -->

<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F7') {
            e.preventDefault();
            window.location.href = '<?= url('add-sale') ?>';
        }
        if (e.key === 'F6') {
            e.preventDefault();
            window.location.href = '<?= url('add-product-inventory') ?>';
        }
        if (e.key === 'F2') {
            e.preventDefault();
            window.location.href = '<?= url('deposit-money') ?>';
        }
    });
</script>
</body>

</html>