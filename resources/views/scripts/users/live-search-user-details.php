<script>
    $(document).ready(function() {
        let currentIndex = -1;

        $('#search_item').on('keydown', function(e) {
            if (e.key === "ArrowDown" || e.key === "ArrowUp" || e.key === "Enter") {
                e.preventDefault();
                navigateOptions(e);
            }
        });

        $('#search_item').on('keyup', function(e) {
            let query = $(this).val().trim();
            if (e.key !== "ArrowDown" && e.key !== "ArrowUp" && e.key !== "Enter") {
                if (query.length > 0) {
                    $.ajax({
                        url: "<?= url('search-user-details') ?>",
                        method: 'POST',
                        data: {
                            customer_name: query
                        },
                        dataType: 'json',
                        success: function(response) {
                            let output = '';
                            if (response.status === 'success' && response.items.length > 0) {
                                response.items.forEach(function(item) {
                                    output += '<li class="resSel search-item color" role="option" data-id="' + item.id + '">' + item.user_name + ' - ' + item.phone + '</li>';
                                });
                            } else {
                                output = '<li class="resSel search-item color" role="option">چیزی یافت نشد</li>';
                            }
                            $('#backResponseSeller').removeClass('d-none').html(output).show();
                            currentIndex = -1;
                        },
                        error: function(xhr) {
                            console.log("AJAX Error:", xhr.responseText);
                            $('#backResponseSeller').html('<li class="resSel search-item color" role="option">خطایی رخ داد، لطفا دوباره امتحان کنید</li>').show();
                        }
                    });
                } else {
                    $('#backResponseSeller').addClass('d-none').hide();
                }
            }
        });

        function navigateOptions(e) {
            const items = $('#backResponseSeller li');
            if (e.key === "ArrowDown") {
                if (currentIndex < items.length - 1) {
                    currentIndex++;
                    items.removeClass('selected');
                    items.eq(currentIndex).addClass('selected');
                }
            } else if (e.key === "ArrowUp") {
                if (currentIndex > 0) {
                    currentIndex--;
                    items.removeClass('selected');
                    items.eq(currentIndex).addClass('selected');
                }
            } else if (e.key === "Enter") {
                if (currentIndex > -1) {
                    const selectedItem = items.eq(currentIndex);
                    const sellerName = selectedItem.text();
                    const sellerId = selectedItem.data('id');
                    if (sellerId) {
                        $('#search_item').val(sellerName);
                        $('#backResponseSeller').html('').hide();
                        window.location.href = "<?= url('user-details') ?>/" + sellerId;
                    }
                }
            }
        }

        $(document).on('click', '#backResponseSeller li', function() {
            const sellerName = $(this).text();
            const sellerId = $(this).data('id');
            if (sellerId) {
                $('#search_item').val(sellerName);
                $('#backResponseSeller').html('').hide();
                window.location.href = "<?= url('user-details') ?>/" + sellerId;
            }
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#search_item, #backResponseSeller').length) {
                $('#backResponseSeller').hide();
            }
        });

        $('#search_item').on('focus', function() {
            if ($(this).val().length > 0) {
                $('#backResponseSeller').show();
            }
        });
    });
</script>