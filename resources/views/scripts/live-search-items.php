<script>
    $(document).ready(function() {
        $('.search-database-s').each(function() {
            const $wrapper = $(this);
            const ajaxUrl = $wrapper.data('url');
            const inputId = $wrapper.data('input-id');
            const resultId = $wrapper.data('result-id');
            const fieldName = $wrapper.data('field-name');
            const targetId = $wrapper.data('target-id');
            let currentIndex = -1;
            let itemsData = [];
            const $input = $('#' + inputId);
            const $result = $('#' + resultId);

            function showLoading() {
                $result.removeClass('d-none').html('<li class="search-item color loading fs14">در حال جستجو...</li>').show();
            }

            function navigateOptions(e) {
                const items = $result.find('li').not('.no-select');
                if (e.key === "ArrowDown") {
                    if (currentIndex < items.length - 1) currentIndex++;
                } else if (e.key === "ArrowUp") {
                    if (currentIndex > 0) currentIndex--;
                } else if (e.key === "Enter") {
                    if (currentIndex > -1) {
                        const selectedItem = items.eq(currentIndex);
                        const value = selectedItem.text();
                        const id = selectedItem.data('id');
                        $input.val(value);
                        if (targetId) $('#' + targetId).val(id).trigger('change');
                        $result.hide();
                    }
                }
                items.removeClass('selected');
                if (currentIndex >= 0) items.eq(currentIndex).addClass('selected');
            }

            $input.on('keydown', function(e) {
                if (["ArrowDown", "ArrowUp", "Enter"].includes(e.key)) {
                    e.preventDefault();
                    navigateOptions(e);
                }
            });

            $input.on('keyup', function(e) {
                let query = $(this).val().trim();
                if (["ArrowDown", "ArrowUp", "Enter"].includes(e.key)) return;

                if (query.length > 0) {
                    showLoading();
                    $.ajax({
                        url: ajaxUrl,
                        method: 'POST',
                        data: {
                            [fieldName]: query
                        },
                        dataType: 'json',
                        success: function(response) {
                            let output = '';
                            itemsData = [];
                            if (response.status === 'success' && response.items.length > 0) {
                                response.items.forEach(item => {
                                    itemsData.push(item);
                                    let phoneText = item.phone ? ` - ${item.phone}` : '';
                                    output += `<li class="resSel search-item color" role="option" data-id="${item.id}">${item.user_name}${phoneText}</li>`;
                                });
                            } else {
                                output = '<li class="resSel search-item color no-select" role="option">چیزی یافت نشد</li>';
                            }
                            $result.html(output).show();
                            currentIndex = -1;
                        },
                        error: function(xhr) {
                            console.log("AJAX Error:", xhr.responseText);
                            $result.html('<li class="resSel search-item color no-select" role="option">خطایی رخ داد، لطفا دوباره امتحان کنید</li>').show();
                        }
                    });
                } else {
                    $result.addClass('d-none').hide();
                }
            });

            $result.on('click', 'li', function() {
                if ($(this).hasClass('no-select')) return;
                const value = $(this).text();
                const id = $(this).data('id');
                $input.val(value);
                if (targetId) $('#' + targetId).val(id).trigger('change');
                $result.hide();
            });

            $(document).on('click', function(event) {
                if (!$(event.target).closest($input).length && !$(event.target).closest($result).length) {
                    $result.hide();
                }
            });

            $input.on('focus', function() {
                if ($(this).val().length > 0) $result.show();
            });
        });
    });
</script>