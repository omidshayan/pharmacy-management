<script>
    $(document).ready(function() {
        let currentIndex = -1;
        let $currentInput = null;
        let $resultBox = null;

        function renderItems(template, item) {
            const fields = template.split('-').map(f => f.trim());
            const values = fields.map(f => item[f]?.trim()).filter(Boolean);
            return values.join(' - ');
        }

        $(document).on('keydown', '.live-search-input', function(e) {
            if (["ArrowDown", "ArrowUp", "Enter"].includes(e.key)) {
                e.preventDefault();
                navigateOptions(e);
            }
        });

        $(document).on('keyup', '.live-search-input', function(e) {
            if (["ArrowDown", "ArrowUp", "Enter"].includes(e.key)) return;

            $currentInput = $(this);
            $resultBox = $currentInput.siblings('.live-search-result');
            const query = $currentInput.val().trim();
            const searchUrl = $currentInput.data('search-url');
            const template = $currentInput.data('template');
            const idKey = $currentInput.data('id-key');
            const editUrlBase = $currentInput.data('edit-url');

            if (query.length > 0) {
                $resultBox
                    .removeClass('d-none')
                    .html(`
                    <li class="search-item color d-flex align-items-center justify-content-center" role="option">
                        <span class="spinner-border me-2"></span>
                    </li>
                `)
                    .show();

                $.ajax({
                    url: searchUrl,
                    method: 'POST',
                    data: {
                        customer_name: query
                    },
                    dataType: 'json',
                    success: function(response) {
                        let output = '';

                        if (response.status === 'success' && response.items.length > 0) {
                            response.items.forEach(item => {
                                const display = renderItems(template, item);
                                const itemId = item[idKey];
                                const editButton = editUrlBase ?
                                    `<a href="${editUrlBase + itemId}" class="btn-sm btn-warning edit-btn fs11 center">ویرایش</a>` :
                                    '';

                                output += `
                                    <li class="resSel search-item color d-flex justify-content-between align-items-center"
                                        role="option" data-id="${itemId}">
                                        <span class="search-name">${display}</span>
                                        ${editButton}
                                    </li>`;
                            });
                        } else {
                            output = '<li class="resSel search-item color" role="option">چیزی یافت نشد</li>';
                        }

                        $resultBox.html(output).show();
                        currentIndex = -1;
                    },
                    error: function() {
                        $resultBox.html('<li class="resSel search-item color" role="option">خطا در ارتباط</li>').show();
                    }
                });
            } else {
                $resultBox.addClass('d-none').hide();
            }
        });

        function navigateOptions(e) {
            if (!$currentInput) {
                $currentInput = $(e.target);
                $resultBox = $currentInput.siblings('.live-search-result');
            }

            const items = $resultBox.find('li');
            if (!items.length) return;

            if (e.key === "ArrowDown" && currentIndex < items.length - 1) currentIndex++;
            else if (e.key === "ArrowUp" && currentIndex > 0) currentIndex--;
            else if (e.key === "Enter" && currentIndex > -1) {
                selectItem(items.eq(currentIndex));
                return;
            }

            items.removeClass('selected');
            const $selectedItem = items.eq(currentIndex);
            $selectedItem.addClass('selected');
            $selectedItem[0]?.scrollIntoView({
                behavior: "smooth",
                block: "nearest"
            });
        }

        function selectItem($item) {
            const sellerName = $item.find('.search-name').text();
            const sellerId = $item.data('id');
            const redirectBase = $currentInput.data('redirect-url');
            if (sellerId) {
                $currentInput.val(sellerName);
                $resultBox.html('').hide();
                window.location.href = redirectBase + sellerId;
            }
        }

        $(document).on('click', '.live-search-result li', function(event) {
            if ($(event.target).hasClass('edit-btn')) {
                event.stopPropagation();
                return;
            }
            selectItem($(this));
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.live-search-input, .live-search-result').length) {
                $('.live-search-result').hide();
            }
        });

        $(document).on('focus', '.live-search-input', function() {
            const input = $(this);
            const resultBox = input.siblings('.live-search-result');
            if (input.val().length > 0) resultBox.show();
        });
    });
</script>