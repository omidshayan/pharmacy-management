    <!-- print page -->
    <iframe id="print_frame" name="print_frame" style="display:none;"></iframe>
    <!-- check for print -->
    <?php
    if (isset($_SESSION['flash_id'])) {
        $id = $_SESSION['flash_id'];
        unset($_SESSION['flash_id']);
        $url = url('invoice-print/' . $id);
    ?>
        <script>
            var printFrame = document.getElementById('print_frame');
            printFrame.src = '<?php echo $url; ?>';
            printFrame.onload = function() {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            };
        </script>
    <?php
    }
    ?>