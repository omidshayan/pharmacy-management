<script>
    $(document).ready(function() {
        const $input = $('#search_input');
        const ajaxUrl = $input.data('ajax-url');
        const resultContainerSelector = $input.data('result-container');
        const itemName = $input.data('item-name');
        const displayFieldsStr = $input.data('display-fields') || '';
        const displayFields = displayFieldsStr.split(',').map(f => f.trim()).filter(f => f.length > 0);
        const hiddenFieldsStr = $input.data('hidden-fields') || '';
        const hiddenFields = hiddenFieldsStr.split(',').map(f => f.trim()).filter(f => f.length > 0);

        let currentIndex = -1;
        window.itemsData = [];

        function setSelectedFields(item) {
            $(`#selected_${itemName}_id`).val(item.id || '').trigger('change');
            hiddenFields.forEach(field => {
                $(`#selected_${itemName}_${field}`).val(item[field] || '').trigger('change');
            });
        }

        function navigateOptions(e) {
            const items = $(`${resultContainerSelector} li`);
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
                    selectedItem.trigger('click');
                    $(resultContainerSelector).hide();
                }
            }
        }

        $input.on('keydown', function(e) {
            if (e.key === "ArrowDown" || e.key === "ArrowUp" || e.key === "Enter") {
                e.preventDefault();
                navigateOptions(e);
            }
        });

        $input.on('keyup', function(e) {
            let query = $(this).val().trim();

            if (e.key !== "ArrowDown" && e.key !== "ArrowUp" && e.key !== "Enter") {
                if (query.length > 0) {
                    $.ajax({
                        url: ajaxUrl,
                        method: 'POST',
                        data: {
                            customer_name: query,
                            csrf_token: $('input[name="csrf_token"]').val()
                        },
                        dataType: 'json',
                        success: function(response) {
                            let output = '';
                            window.itemsData = [];
                            if (response.status === 'success' && response.products.length > 0) {
                                response.products.forEach(function(item) {
                                    window.itemsData.push(item);

                                    let displayText = displayFields.map(field => item[field] || '').join(' | ');

                                    output += `<li class="search-item" role="option" data-id="${item.id}">${displayText}</li>`;
                                });
                            } else {
                                output = `<li class="search-item">چیزی یافت نشد</li>`;
                            }
                            $(resultContainerSelector).removeClass('d-none').html(output).show();
                            currentIndex = -1;
                        },
                        error: function(xhr) {
                            $(resultContainerSelector).html('<li class="search-item">خطایی رخ داد، لطفا دوباره امتحان کنید</li>').show();
                        }
                    });
                } else {
                    $(resultContainerSelector).addClass('d-none').hide();
                }
            }
        });

        $(document).on('click', `${resultContainerSelector} li`, function() {
            const index = $(this).index();
            const item = window.itemsData[index];
            if (!item) return;

            $input.val($(this).text());
            setSelectedFields(item);
            $(resultContainerSelector).hide();
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest($input).length && !$(event.target).closest(resultContainerSelector).length) {
                $(resultContainerSelector).hide();
            }
        });

        $input.on('focus', function() {
            if ($(this).val().length > 0) {
                $(resultContainerSelector).show();
            }
        });
    });
</script>