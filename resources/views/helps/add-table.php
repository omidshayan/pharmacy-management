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


    /* switch */
    .date-switch {
        display: flex;
        background: var(--simillar);
        border: 1px solid #cccccc1a;
        border-radius: 20px;
        padding: 2px;
        cursor: pointer;
        width: 120px;
        position: relative;
        margin-bottom: 10px;
    }

    .date-switch span {
        flex: 1;
        text-align: center;
        font-size: 15px;
        z-index: 2;
        padding: 4px 0;
        transition: color 0.3s;
    }

    .date-switch .active-bg {
        position: absolute;
        width: 50%;
        height: 85%;
        background: var(--simillar);
        border: 1px solid var(--bg);
        border-radius: 18px;
        top: 50%;
        transform: translateY(-50%);
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        z-index: 1;
    }

    .switch-shamsi .active-bg {
        left: 2px;
    }

    .switch-miladi .active-bg {
        left: 58px;
    }

    .hidden-date {
        display: none !important;
    }
</style>
<!-- spiner for change number -->
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

<script>
    const expirationDate = <?= (int)$expire_date['expiration_date'] ?>;
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
                url: '<?= url("product-inventory-store") ?>',
                method: 'POST',
                data: {
                    product_id: item.id,
                    product_name: item.product_name,
                    csrf_token: $('input[name="csrf_token"]').val(),
                    unit_type: $('#selected_item_unit_type').val(),
                    package_price_buy: $('#selected_item_package_price_buy').val(),
                    package_price_sell: $('#selected_item_package_price_sell').val(),
                    unit_price_buy: $('#selected_item_unit_price_buy').val(),
                    unit_price_sell: $('#selected_item_unit_price_sell').val(),
                    quantity_in_pack: $('#selected_item_quantity_in_pack').val(),
                },
                dataType: 'json',
                success: function(response) {
                    hideLoadingOverlay();

                    if (response.success) {
                        $('#alert')
                            .removeClass('error')
                            .addClass('success')
                            .text(response.message || 'محصول با موفقیت به سبد خرید اضافه شد.')
                            .fadeIn()
                            .delay(300)
                            .fadeOut();


                        if (response.id.invoice_id) {
                            $('#invoice_id').val(response.id.invoice_id);
                        }

                        currentWarehouses = response.id.warehouses ?? [];
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
                        .text('خطا در ارسال محصول به سرور. لطفاً دوباره تلاش کنید.')
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
    let currentWarehouses = [];

    $(document).ready(function() {
        loadInvoiceItems();
        initNumberSpinners();

        $(document).on('click', '.product-info-icon', function() {
            const $this = $(this);
            const itemId = $this.data('product-id');
            const item = currentItems.find(i => i.id == itemId);

            const product = {
                name: $this.data('product-name'),
                packageQty: $this.data('package-qty'),
                unitQty: $this.data('unit-qty'),
                packagePriceBuy: $this.data('package-price-buy'),
                packagePriceSell: $this.data('package-price-sell'),
                quantityInPack: $this.data('quantity-in-pack'),
                unitPriceBuy: $this.data('unit-price-buy'),
                unitPriceSell: $this.data('unit-price-sell'),
                productType: $this.data('product-type'),
                unitType: $this.data('unit-type')
            };

            let warehousesOptions = '';
            if (currentWarehouses.length > 0) {
                currentWarehouses.forEach(w => {
                    warehousesOptions += `<option value="${w.id}" ${item && item.warehouse_id == w.id ? 'selected' : ''}>${w.warehouse_name}</option>`;
                });
            }

            let warehouseHtml = `
                    <div class="inputs">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب محل قرارگیری این محصول</div>
                            <select name="warehouse_id">
                                <option selected disabled>پیشفرض (داخل فروشگاه)</option>
                                ${warehousesOptions}
                            </select>
                        </div>
                    </div>
            `;

            const unitBuyHtml = (product.unitType) ?
                `
                    <div class="one">
                        <span class="fs14">قیمت خرید عدد</span>
                        <input type="text" id="unitPriceBuy" value="${product.unitPriceBuy ?? 0}">
                    </div>
                ` : '';

            const unitSellHtml = (product.unitType) ?
                `
                    <div class="one">
                        <span class="fs14">قیمت فروش عدد</span>
                        <input type="text" id="unitPriceSell" value="${product.unitPriceSell ?? 0}">
                    </div>
                ` : '';

            let expireHtml = '';
            if (typeof expirationDate !== 'undefined' && expirationDate === 1) {
                expireHtml = `
                <div class="inputs">
                    <div class="label-form mb5 fs14">
                        تاریخ بِل (انقضا)
                        <div class="date-switch switch-shamsi" id="toggleDatePicker">
                            <div class="active-bg"></div>
                            <span class="sh-txt">میلادی</span>
                            <span class="mi-txt">شمسی</span>
                        </div>
                    </div>
                    <div class="one" id="shamsi-container">
                        <input type="text" id="expiration_date" data-jdp class="form-control date-view checkInput" autocomplete="off" placeholder="تاریخ شمسی (کلیک کنید)">
                    </div>
                    <div class="one hidden-date" id="miladi-container">
                        <input type="date" id="expiration_date_miladi" class="form-control" style="direction: ltr;">
                    </div>
                </div>`;
            }

            $('#productInfosModalTitle').text(product.name);
            let productInfoHtml = `<span>دسته بندی: ${product.productType}</span>`;

            if (product.unitType && product.unitType.trim() !== '') {
                productInfoHtml += ` - <span>تعداد در بسته: ${product.quantityInPack}</span> - <span>واحد شمارش: ${product.unitType}</span>`;
            }

            $('#productInfosModalContent').html(`
                ${productInfoHtml}
                <div class="insert">
                    <input type="hidden" id="modal_item_id" value="${itemId}">
                    <input type="hidden" id="productUnitType" value="${product.unitType}">
                    <div class="inputs d-flex">
                        <div class="one">
                            <span class="fs14">قیمت خرید بسته</span>
                            <input type="text" id="packagePriceBuy" value="${product.packagePriceBuy}">
                        </div>
                        ${unitBuyHtml}
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <span class="fs14">قیمت فروش بسته</span>
                            <input type="text" id="packagePriceSell" value="${product.packagePriceSell}">
                        </div>
                        ${unitSellHtml}
                    </div>
                    ${warehouseHtml}
                    <div class="inputs d-flex">
                        <div class="one">
                            <textarea class="h-area" placeholder="آدرس محل قرار گیری محصول...">${item?.location || ''}</textarea>
                        </div>
                    </div>
                    ${expireHtml} 
                </div>
            `);

            const modal = $('#productInfosModal');
            const box = modal.find('.product-infos-modal-box');
            box.removeClass('shake');
            modal.removeClass('hidden').addClass('show');
        });

        // سوئیچ تاریخ
        $(document).on('click', '#toggleDatePicker', function() {
            const $this = $(this);
            $this.toggleClass('switch-shamsi switch-miladi');
            $('#shamsi-container, #miladi-container').toggleClass('hidden-date');
        });

        $(document).on('click', '#productInfosModal', function(e) {
            if ($(e.target).closest('.product-infos-modal-box').length === 0) {
                const modal = $(this);
                if (!modal.hasClass('show')) return;
                const box = modal.find('.product-infos-modal-box');
                box.removeClass('shake');
                void box[0].offsetWidth;
                box.addClass('shake');
            }
        });

        $(document).on('click', '.product-infos-modal-close', closeProductInfosModal);
        $(document).on('click', '#productInfosModalCancelBtn', closeProductInfosModal);

        function closeProductInfosModal() {
            const modal = $('#productInfosModal');
            modal.removeClass('show');
            setTimeout(() => modal.addClass('hidden'), 300);
        }

        $(document).on('click', '#productInfosModalOkBtn', function() {
            // انتخاب مقدار تاریخ بر اساس سوئیچ فعال
            let selectedDate = $('#expiration_date').val();
            if ($('#toggleDatePicker').hasClass('switch-miladi')) {
                selectedDate = $('#expiration_date_miladi').val();
            }

            const data = {
                item_id: $('#modal_item_id').val(),
                warehouse_id: $('#productInfosModal select[name="warehouse_id"]').val(),
                note: $('#productInfosModal textarea').val(),
                package_price_buy: parseFloat($('#packagePriceBuy').val()) || 0,
                package_price_sell: parseFloat($('#packagePriceSell').val()) || 0,
                unit_price_buy: parseFloat($('#unitPriceBuy').val()) || null,
                unit_price_sell: parseFloat($('#unitPriceSell').val()) || null,
                unit_type: $('#productUnitType').val() || null,
                csrf_token: $('input[name="csrf_token"]').val(),
                expiration_date: selectedDate
            };

            InvoiceItemService.updateWarehouse(data, function(response) {
                if (response.success) {
                    loadInvoiceItems();
                    closeProductInfosModal();
                } else {
                    alert('خطا در بروزرسانی انبار!');
                }
            });
        });
    });

    let currentItems = [];
    let invoiceItemsState = {};

    function loadInvoiceItems() {
        $.get('<?= url('get-invoice-items-ajax') ?>', function(response) {
            if (response.success) {
                if (response.id.invoice_id) {
                    $('#invoice_id').val(response.id.invoice_id);
                }
                currentWarehouses = response.id.warehouses ?? [];
                currentItems = response.id.items ?? [];
                renderInvoiceItems(currentItems, '#cart-items-tbody');
            }
        }, 'json');
    }

    function renderInvoiceItems(items, tbodySelector) {
        const $tbody = $(tbodySelector);
        $tbody.empty();
        invoiceItemsState = {};
        if (!items || items.length === 0) {
            $tbody.append('<tr><td colspan="9" class="text-center fs12 color-red">لیست بِل خالی است</td></tr>');
            toggleSubmitButton([]);
            calculateGrandTotal();
            return;
        }
        items.forEach((item, index) => {
            const quantityInPack = parseFloat(item.quantity_in_pack) || 1;
            const hasUnit = quantityInPack > 1;
            let packageQty = parseFloat(item.package_qty) || 0;
            let unitQty = parseFloat(item.unit_qty) || 0;
            if (!hasUnit) {
                packageQty = packageQty || 1;
                unitQty = 0;
            } else {
                if (packageQty === 0 && unitQty === 0) {
                    unitQty = 1;
                }
            }
            const totalQuantity = (packageQty * quantityInPack) + unitQty;
            const packagePriceBuy = parseFloat(item.package_price_buy) || 0;
            const packagePriceSell = parseFloat(item.package_price_sell) || 0;
            const unitPriceBuy = item.unit_price_buy && !isNaN(item.unit_price_buy) ? parseFloat(item.unit_price_buy) : null;
            const unitPriceSell = item.unit_price_sell && !isNaN(item.unit_price_sell) ? parseFloat(item.unit_price_sell) : null;
            invoiceItemsState[item.id] = {
                package_price_buy: packagePriceBuy,
                unit_price_buy: unitPriceBuy,
                quantity_in_pack: quantityInPack
            };
            const totalPrice = (packageQty * packagePriceBuy) + (unitQty * (unitPriceBuy ?? (packagePriceBuy / Math.max(quantityInPack, 1))));
            const unitQtyHtml = hasUnit ? `<input type="number" class="unit-qty w70 transparent border-none color bold center" data-id="${item.id}" value="${unitQty}" min="0">` : `<span class="color-gray bold">- - -</span>`;
            const row = `
            <tr data-pack-size="${quantityInPack}">
                <td>${index + 1}</td>
                <td>${item.product_name}</td>
                <td><input type="number" class="package-qty w70 transparent border-none color bold center" data-id="${item.id}" value="${packageQty}" min="0"></td>
                <td>${unitQtyHtml}</td>
                <td class="total-quantity">${totalQuantity}</td>
                <td class="row-total bold" data-value="${totalPrice}">${formatPrice(totalPrice)}</td>
                <td class="product-info-icon cursor-p" data-product-id="${item.id}" data-product-name="${item.product_name}" data-unit-type="${item.unit_type}" data-product-type="${item.package_type}" data-package-qty="${packageQty}" data-unit-qty="${unitQty}" data-package-price-buy="${packagePriceBuy}" data-package-price-sell="${packagePriceSell}" data-unit-price-buy="${unitPriceBuy}" data-unit-price-sell="${unitPriceSell}" data-quantity-in-pack="${quantityInPack}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" class="color-orange" /><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" /></svg>
                </td>
                <td class="delete-product-cart transparent cursor-p" data-id="${item.id}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512"><path fill="#ff0000" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z" /></svg>
                </td>
            </tr>`;
            $tbody.append(row);
        });
        toggleSubmitButton(items);
        calculateGrandTotal();
        initNumberSpinners();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        $('#cart-items-tbody tr').each(function() {
            const rowTotal = parseFloat($(this).find('.row-total').attr('data-value')) || 0;
            grandTotal += rowTotal;
        });
        $('#grand-total').text(grandTotal === 0 ? '0' : formatPrice(grandTotal));
        $('#total-price').val(grandTotal);
    }
</script>

<!-- change values -->
<script>
    let ajaxTimeout;

    function getRawNumber(val) {
        if (typeof val === 'string') {
            val = val.replace(/,/g, '');
        }
        return parseFloat(val) || 0;
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
            if (prevVal === undefined || prevVal === null) {
                prevVal = 1;
            }
            $input.val(prevVal);
            return false;
        }
        return true;
    }

    $(document).on('focus', '.package-qty, .unit-qty', function() {
        $(this).data('prev', $(this).val());
    });

    $(document).on('input', '.package-qty, .unit-qty', function() {

        const $row = $(this).closest('tr');
        const itemId = $(this).data('id');

        const packageQty = getRawNumber($row.find('.package-qty').val());
        const unitQty = getRawNumber($row.find('.unit-qty').val());
        const quantityInPack = parseFloat($row.data('pack-size')) || 1;

        if (!validatePackageAndUnitQty($(this), packageQty, unitQty)) {
            return;
        }

        const state = invoiceItemsState[itemId];

        const packagePriceBuy = state.package_price_buy || 0;
        const unitPriceBuy = state.unit_price_buy ?? null;



        const totalQuantity = (packageQty * quantityInPack) + unitQty;

        const totalPrice =
            (packageQty * packagePriceBuy) +
            (unitQty * (unitPriceBuy ?? (packagePriceBuy / Math.max(quantityInPack, 1))));

        $row.find('.total-quantity').text(totalQuantity);

        $row.find('.row-total')
            .text(formatPrice(totalPrice))
            .attr('data-value', totalPrice);

        clearTimeout(ajaxTimeout);
        ajaxTimeout = setTimeout(() => {
            $.ajax({
                url: '<?= url("update-invoice-item") ?>/' + itemId,
                method: 'POST',
                data: {
                    package_qty: packageQty,
                    unit_qty: unitQty,
                    package_price_buy: packagePriceBuy
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.success) {
                        alert('خطا در ویرایش!');
                    }
                },
                error: function() {
                    alert('خطای ارتباط با سرور!');
                }
            });
        }, 400);

        calculateGrandTotal();
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

<!-- update prices, warehouse and location -->
<script>
    const InvoiceItemService = {
        updateWarehouse: function(data, callback = null) {
            const itemId = data.item_id;
            $.ajax({
                url: '<?= url("item-update-warehouse") ?>/' + itemId,
                method: 'POST',
                data: data,
                dataType: 'json',

                success: function(response) {
                    if (response.success) {
                        $('#alert')
                            .removeClass('error')
                            .addClass('success')
                            .text(response.message || 'محصول با موفقیت به سبد خرید اضافه شد.')
                            .fadeIn()
                            .delay(300)
                            .fadeOut();

                        if (typeof callback === 'function') {
                            callback(response);
                        }
                    } else {
                        alert(response.message || 'خطا در بروزرسانی');
                    }
                },

                error: function() {
                    alert('خطا در ارتباط با سرور');
                }
            });
        }
    };
</script>