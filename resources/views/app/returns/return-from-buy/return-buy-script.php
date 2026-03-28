<!-- btn for change number (package or quantity) -->
<style>
    .number-spinner {
        display: inline-flex;
        align-items: center;
        border: 1px solid var(--border);
        border-radius: 4px;
        overflow: hidden;
        width: 110px;
    }

    .number-spinner input[type="number"] {
        border: none;
        width: 60px;
        text-align: center;
        font-size: 16px;
        outline: none;
    }

    .number-spinner input[type="number"]::-webkit-inner-spin-button,
    .number-spinner input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .number-spinner button {
        background: var(--simillar);
        border: none;
        width: 25px;
        height: 30px;
        font-size: 20px;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
    }

    .number-spinner button:hover {
        background: var(--hover);
    }

    .number-spinner button:active {
        background: #ccc;
    }

    .decrement,
    .increment:hover {
        opacity: 1 !important;
    }
</style>
<script>
    function initNumberSpinners() {
        const selectors = '.package-qty, .unit-qty';

        $(selectors).each(function() {
            const $input = $(this);

            if ($input.parent().hasClass('number-spinner')) {
                return;
            }

            if ($input.is(':disabled')) {
                return;
            }

            const $wrapper = $('<div class="number-spinner"></div>');
            const $btnDec = $('<button type="button" class="decrement color opacity">−</button>');
            const $btnInc = $('<button type="button" class="increment color opacity">+</button>');

            $input.wrap($wrapper);
            $input.before($btnDec);
            $input.after($btnInc);

            $btnDec.on('click', function() {
                let val = parseFloat($input.val()) || 0;
                let min = parseFloat($input.attr('min'));
                if (!isNaN(min)) {
                    val = Math.max(min, val - 1);
                } else {
                    val = val - 1;
                }
                $input.val(val).trigger('input');
            });

            $btnInc.on('click', function() {
                let val = parseFloat($input.val()) || 0;
                let max = parseFloat($input.attr('max'));
                if (!isNaN(max)) {
                    val = Math.min(max, val + 1);
                } else {
                    val = val + 1;
                }
                $input.val(val).trigger('input');
            });
        });
    }
</script>

<!-- format price number -->
<script>
    function formatPrice(num) {
        let number = typeof num === 'string' ? parseFloat(num) : num;

        if (Number.isInteger(number)) {
            return number.toLocaleString('en-US');
        } else {
            return number.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }

    // inputs
    function formatPriceForInput(num) {
        if (typeof num === 'string') {
            num = parseFloat(num.replace(/,/g, ''));
        }
        if (isNaN(num)) return '';

        return num.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }
</script>

<!-- store product -->
<script>
    $(document).ready(function() {
        const $input = $('#search_input');
        const resultContainerSelector = $input.data('result-container');

        window.itemsData = window.itemsData || [];

        function showLoadingOverlay() {
            var overlay = document.getElementById('loadingOverlay');
            if (overlay) overlay.style.display = 'flex';
        }

        function hideLoadingOverlay() {
            var overlay = document.getElementById('loadingOverlay');
            if (overlay) overlay.style.display = 'none';
        }

        $(document).on('click', `${resultContainerSelector} li`, function() {
            const index = $(this).index();
            const item = window.itemsData[index];
            if (!item) {
                console.warn('آیتم انتخاب شده پیدا نشد.');
                return;
            }

            showLoadingOverlay();

            $.ajax({
                url: '<?= url("return-buy-store") ?>',
                method: 'POST',
                data: {
                    product_id: item.id,
                    product_name: item.product_name,
                    csrf_token: $('input[name="csrf_token"]').val(),
                    main_p_id: $('#selected_item_product_id').val(),
                    unit_type: $('#selected_item_unit_type').val(),
                    package_price_sell: $('#selected_item_package_price_sell').val(),
                    package_price_buy: $('#selected_item_package_price_buy').val(),
                    unit_price_sell: $('#selected_item_unit_price_sell').val(),
                    unit_price_buy: $('#selected_item_unit_price_buy').val(),
                    quantity_in_pack: $('#selected_item_quantity_in_pack').val(),
                },
                dataType: 'json',
                success: function(response) {

                    hideLoadingOverlay();

                    if (response.success) {
                        $('#alert')
                            .removeClass('error')
                            .addClass('success')
                            .text(response.message || 'دوا با موفقیت به سبد خرید اضافه شد.')
                            .fadeIn()
                            .delay(300)
                            .fadeOut();

                        if (response.id.invoice_id) {
                            $('#invoice_id').val(response.id.invoice_id);
                        }

                        let items = response.id.items || [];
                        renderInvoiceItems(items, '#cart-items-tbody');


                    } else {
                        $('#alert')
                            .removeClass('success')
                            .addClass('error')
                            .text(response.message || 'خطایی رخ داد.')
                            .fadeIn()
                            .delay(5000)
                            .fadeOut();
                    }

                    $input.val('');
                    $(resultContainerSelector).hide();
                    $input.focus();
                },
                error: function(xhr) {
                    hideLoadingOverlay();

                    $('#alert')
                        .removeClass('success')
                        .addClass('error')
                        .text('خطا در ارسال دوا به سرور. لطفاً دوباره تلاش کنید.')
                        .fadeIn()
                        .delay(5000)
                        .fadeOut();
                }
            });
        });
    });
</script>

<!-- make table -->
<script>
    $(document).ready(function() {
        loadInvoiceItems();
        initNumberSpinners();
    });

    function loadInvoiceItems() {
        $.get('<?= url('get-return-buy-invoice-items-ajax') ?>', function(response) {

            if (response.success) {
                if (response.id.invoice_id) {
                    $('#invoice_id').val(response.id.invoice_id);
                }

                renderInvoiceItems(response.id.items, '#cart-items-tbody');
            }

        }, 'json');
    }

    function renderInvoiceItems(items, tbodySelector) {

        const $tbody = $(tbodySelector);
        $tbody.empty();

        if (!items || items.length === 0) {
            $tbody.append('<tr><td colspan="10" class="text-center fs12 color-red">لیست بِل خالی است</td></tr>');
            toggleSubmitButton([]);
            calculateGrandTotal();
            return;
        }

        items.forEach((item, index) => {

            const quantityInPack = parseFloat(item.quantity_in_pack) || 1;
            const hasUnit = quantityInPack > 1;

            let packageQty = parseFloat(item.package_qty) || 0;
            let unitQty = parseFloat(item.unit_qty) || 0;

            if (packageQty === 0 && unitQty === 0) {

                if (hasUnit) {
                    unitQty = 1;
                    packageQty = 0;
                } else {
                    packageQty = 1;
                    unitQty = 0;
                }
            }

            const totalQuantity = (packageQty * quantityInPack) + unitQty;

            const unitPriceSell = parseFloat(item.unit_price_sell) || 0;

            const totalPrice = parseFloat(item.item_total_price) || (totalQuantity * unitPriceSell);


            const packagePriceBuy = parseFloat(item.package_price_buy);
            const packagePriceSell = parseFloat(item.package_price_sell);
            const unitPriceBuy = parseFloat(item.unit_price_buy);

            const unitQtyHtml = hasUnit ?
                `<input type="number"
                    class="unit-qty w70 transparent border-none color bold center"
                    data-id="${item.id}"
                    value="${unitQty}"
                    min="0">
                    ` :
                `
                        <span class="color-gray bold">- - -</span>
                    `;

            const row = `
                <tr data-pack-size="${quantityInPack}">
                    <td>${index + 1}</td>
                    <td>${item.product_name}</td>
                    
                    <td>
                        <input type="number"
                            class="package-qty w70 transparent border-none color bold center"
                            data-id="${item.id}"
                            value="${packageQty}"
                            min="0">
                    </td>

                    <td>
                        ${unitQtyHtml}
                    </td>

                    <td class="total-quantity">${totalQuantity}</td>

                    <td>
                        <input type="text"
                            class="package-price-input w70 transparent border-none color bold center"
                            data-id="${item.id}"
                            value="${formatPriceForInput(packagePriceSell)}"
                            step="0.01"
                            min="0">
                    </td>

                    <td>
                        <input type="text"
                            class="unit-price-input w70 transparent border-none color bold center"
                            data-id="${item.id}"
                            placeholder="${(hasUnit ? '' : '- - -')}"
                            ${(hasUnit ? '' : 'disabled')}
                            value="${formatPriceForInput(hasUnit ? unitPriceSell : '')}"
                            step="0.01"
                            min="0">
                    </td>

                    <td class="row-total bold">${formatPrice(totalPrice)}</td>

                    <td class="product-info-icon cursor-p"
                        data-product-id="${item.id}"
                        data-product-name="${item.product_name}"
                        data-unit-price-buy="${unitPriceBuy}"
                        data-unit-price-sell="${unitPriceSell}"
                        data-package-price-buy="${packagePriceBuy}"
                        data-package-price-sell="${packagePriceSell}"
                        data-total-stock="${item.total_stock}"
                        data-quantity-in-pack="${quantityInPack}"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" class="color-orange" />
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a.5.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                    </td>

                    <td class="delete-product-cart transparent cursor-p" data-id="${item.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512">
                        <path fill="#ff0000" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320z"/>
                        </svg>
                    </td>
                </tr>
            `;

            $tbody.append(row);
            toggleSubmitButton(items);
            calculateGrandTotal();
            initNumberSpinners();
        });
    }
</script>

<!-- change valies -->
<script>
    let ajaxTimeout;

    function getRawNumber(val) {
        if (val === null || val === undefined) return 0;
        if (typeof val === 'string') val = val.replace(/,/g, '').trim();
        let num = parseFloat(val);
        return isNaN(num) ? 0 : num;
    }

    function formatPrice(num) {
        return num.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }

    function validatePackageAndUnitQty($input, packageQty, unitQty) {
        if (packageQty === 0 && unitQty === 0) {
            alert('تعداد بسته و تعداد عدد هر دو نمی‌توانند همزمان صفر باشند.');
            let prevVal = $input.data('prev');
            if (prevVal === undefined || prevVal === null) prevVal = 1;
            $input.val(prevVal);
            return false;
        }
        return true;
    }

    $(document).on('focus', '.package-qty, .unit-qty', function() {
        $(this).data('prev', $(this).val());
    });

    // اضافه کردن کلاس‌های جدید قیمت به مانیتورینگ input
    // مانیتورینگ تغییرات قیمت و تعداد
    $(document).on('input', '.package-qty, .unit-qty, .unit-price-input, .package-price-input', function() {

        const $row = $(this).closest('tr');

        // تغییر مهم: استفاده از attr به جای data برای خواندن مقادیر داینامیک
        const itemId = $(this).attr('data-id');
        const quantityInPack = parseFloat($row.attr('data-pack-size')) || 1;

        const packageQty = getRawNumber($row.find('.package-qty').val());
        const unitQty = getRawNumber($row.find('.unit-qty').val());

        const currentPackagePrice = getRawNumber($row.find('.package-price-input').val());
        const currentUnitPrice = getRawNumber($row.find('.unit-price-input').val());

        const hasUnit = quantityInPack > 1;

        // اعتبار سنجی تعداد
        if (!validatePackageAndUnitQty($(this), packageQty, unitQty)) return;

        // محاسبه قیمت کل سطر
        let totalPrice;
        if (hasUnit) {
            totalPrice = (packageQty * currentPackagePrice) + (unitQty * currentUnitPrice);
        } else {
            totalPrice = packageQty * currentPackagePrice;
        }

        const totalQuantity = (packageQty * quantityInPack) + unitQty;

        // نمایش در جدول
        $row.find('.total-quantity').text(totalQuantity);
        $row.find('.row-total').text(formatPrice(totalPrice));

        // آپدیت دیتابیس
        clearTimeout(ajaxTimeout);
        ajaxTimeout = setTimeout(() => {
            // چک کردن وجود itemId برای جلوگیری از خطای URL
            if (!itemId) return;

            $.ajax({
                url: '<?= url("update-sale-invoice-item") ?>/' + itemId,
                method: 'POST',
                data: {
                    package_qty: packageQty,
                    unit_qty: unitQty,
                    package_price_sell: currentPackagePrice,
                    unit_price_sell: hasUnit ? currentUnitPrice : 0
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.success) alert('خطا در ویرایش!');
                },
                error: function() {
                    console.log('خطای ارتباط با سرور در هنگام آپدیت قیمت/تعداد');
                }
            });
        }, 400);

        // فراخوانی تابع مجموع کل فاکتور
        calculateGrandTotal();
    });

    // فرمت کردن اعداد هنگام خروج از اینپوت برای هر دو فیلد قیمت
    $(document).on('blur', '.unit-price-input, .package-price-input', function() {
        let raw = getRawNumber($(this).val());
        $(this).val(formatPrice(raw));
    });
</script>

<!-- format numbers -->
<script>
    function getRawNumber(val) {

        if (val === null || val === undefined) return 0;

        if (typeof val === 'string') {
            val = val.replace(/,/g, '').trim();
        }

        let num = parseFloat(val);

        return isNaN(num) ? 0 : num;
    }
</script>

<!-- delete item from list -->
<script>
    $(document).on('click', '.delete-product-cart', function() {

        const itemId = $(this).data('id');

        if (!confirm('آیا برای حذف مطمئن هستید')) {
            return;
        }

        $.ajax({
            url: '<?= url("delete-product-cart") ?>/' + itemId,
            dataType: 'json',
            success: function(response) {

                if (response.success) {

                    loadInvoiceItems();

                }
            },
            error: function() {
                console.log('خطا در حذف آیتم');
            }
        });

    });
</script>

<!-- total -->
<script>
    function calculateGrandTotal() {

        let grandTotal = 0;

        $('#cart-items-tbody tr').each(function() {

            const rowTotalText = $(this).find('.row-total').text();
            const rowTotal = getRawNumber(rowTotalText);

            grandTotal += rowTotal;
        });

        // اگر جمع صفر بود، مقدار صفر نشون بده
        if (grandTotal === 0) {
            $('#grand-total').text('0');
        } else {
            $('#grand-total').text(formatPrice(grandTotal));
        }

        $('#total-price').val(grandTotal);
    }
</script>

<!-- show btn or section -->
<script>
    function toggleSubmitButton(items) {
        if (items && items.length > 0) {
            $('.show-hide').removeClass('d-none');
            $('.input-disable').prop('disabled', false);
        } else {
            $('.show-hide').addClass('d-none');
            $('.input-disable').prop('disabled', true);
        }
    }
</script>

<!-- quantity check btn input desable -->
<script>
    function validatePackageAndUnitQty($input, packageQty, unitQty) {
        if (packageQty === 0 && unitQty === 0) {
            alert('تعداد بسته و تعداد عدد هر دو نمی‌توانند همزمان صفر باشند.');

            let prevVal = $input.data('prev');
            if (prevVal === undefined || prevVal === null) {
                prevVal = 1;
            }

            $input.val(prevVal);

            return false;
        }

        return true;
    }
</script>

<!-- modal for infos  -->
<script>
    $(document).on('click', '.product-info-icon', function() {

        const productName = $(this).data('product-name');
        const quantityInPack = showValue($(this).data('quantity-in-pack'));
        const unitPriceBuy = showValue($(this).data('unit-price-buy'));
        const unitPriceSell = showValue($(this).data('unit-price-sell'));
        const packagePriceBuy = showValue($(this).data('package-price-buy'));
        const packagePriceSell = showValue($(this).data('package-price-sell'));
        const totalStock = showValue($(this).data('total-stock'));

        $('#productInfosModalTitle').text(productName);

        const html = `
        <hr class="hr mb10">
        <div class="fs13">
            <div>موجودی فعلی: <b>${totalStock}</b></div>
            <div>تعداد در هر بسته: <b>${quantityInPack}</b></div>
            <div>قیمت خرید بسته: <b>${packagePriceBuy}</b></div>
            <div>قیمت فروش بسته: <b>${packagePriceSell}</b></div>
            <div>قیمت خرید واحد: <b>${unitPriceBuy}</b></div>
            <div>قیمت فروش واحد: <b>${unitPriceSell}</b></div>
        </div>
    `;

        $('#productInfosModalContent').html(html);

        $('#productInfosModal')
            .removeClass('hidden')
            .addClass('show');
    });

    $('#productInfosModalCancelBtn').on('click', function() {
        $('#productInfosModal')
            .removeClass('show')
            .addClass('hidden');
    });

    $('#productInfosModal').on('click', function(e) {
        if (e.target === this) {
            $(this).removeClass('show').addClass('hidden');
        }
    });
</script>

<!-- for check number -->
<script>
    function showValue(val) {
        const num = Number(val);

        if (!val || isNaN(num) || num === 0) {
            return '- - -';
        }
        return val;
    }
</script>