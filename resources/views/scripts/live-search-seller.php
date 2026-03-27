<script>
    $(document).ready(function() {
        let currentIndex = -1;
        let sellersData = [];

        $('#search_seller').on('keydown', function(e) {
            if (e.key === "ArrowDown" || e.key === "ArrowUp" || e.key === "Enter") {
                e.preventDefault();
                navigateOptions(e);
            }
        });

        $('#search_seller').on('keyup', function(e) {
            let query = $(this).val().trim();

            if (e.key !== "ArrowDown" && e.key !== "ArrowUp" && e.key !== "Enter") {
                if (query.length > 0) {
                    $.ajax({
                        url: '<?= url('search-seller') ?>',
                        method: 'POST',
                        data: {
                            customer_name: query,
                            csrf_token: $('input[name="csrf_token"]').val()
                        },
                        dataType: 'json',
                        success: function(response) {
                            let output = '';
                            sellersData = [];
                            if (response.status === 'success' && response.sellers.length > 0) {
                                response.sellers.forEach(function(seller) {
                                    sellersData.push(seller);
                                    output += '<li class="resSel search-item color" role="option" data-id="' + seller.id + '">' + seller.user_name + '</li>';
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
                    $('#search_seller').val(sellerName);
                    $('#seller_id').val(sellerId).trigger('change');
                    $('#backResponseSeller').hide();
                }
            }
        }

        $(document).on('click', '#backResponseSeller li', function() {
            const sellerName = $(this).text();
            const sellerId = $(this).data('id');
            $('#search_seller').val(sellerName);
            $('#seller_id').val(sellerId).trigger('change');
            $('#backResponseSeller').hide();
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#search_seller, #backResponseSeller').length) {
                $('#backResponseSeller').hide();
            }
        });

        $('#search_seller').on('focus', function() {
            if ($(this).val().length > 0) {
                $('#backResponseSeller').show();
            }
        });
    });
</script>