<script>
    $(document).ready(function() {
        $('.changeStatus').click(function(event) {
            showLoadingOverlay()
            event.preventDefault();
            var id = $(this).data('id');
            var url = $(this).data('url') + '/' + id;
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(response) {
                    var statusColumn = $('#status');
                    hideLoadingOverlay()
                    if (response.id == 1) {
                        statusColumn.html('<span class="color-green">فعال</span>');
                        statusColumn.removeClass('color-red').addClass('color-green');
                        $('#alert').removeClass('error').addClass('success').text(response.message).fadeIn().delay(3000).fadeOut();
                    } else {
                        statusColumn.html('<span class="color-red">غیرفعال</span>');
                        statusColumn.removeClass('color-green').addClass('color-red');
                        $('#alert').removeClass('error').addClass('success').text(response.message).fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function(xhr, status, error) {
                    $('#alert').removeClass('success').addClass('error').text(errorMessage).fadeIn().delay(3000).fadeOut();
                }
            });
        });

        function showLoadingOverlay() {
            var overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'flex';
            }
        }

        function hideLoadingOverlay() {
            var overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }
    });
</script>