<script>
$(document).ready(function() {

    // حالت‌ها و فلگ‌ها
    let currentIndex = -1;
    let itemsData = [];
    let quantityInPack = 1;
    let hasUnit = false;
    let isSyncingBuy = false;
    let isSyncingSell = false;

    const $productName = $('#item_name');
    const $backResponse = $('#backResponse');

    const searchUrl = $productName.data('search-url');
    const itemInfoUrl = $productName.data('item-info-url');

    // === helpers ===
    function parseNumber(value) {
        if (value === null || value === undefined) return 0;
        const s = String(value).replace(/,/g, '').trim();
        if (s === '') return 0;
        const n = parseFloat(s);
        return isNaN(n) ? 0 : n;
    }

    function formatNumber(value) {
        if (value === null || value === undefined || value === '') return '';
        let num = typeof value === 'number' ? value : parseFloat(String(value).replace(/,/g, ''));
        if (!isFinite(num)) num = 0;
        const isInt = Math.abs(num - Math.round(num)) < 1e-9;
        const str = isInt ? String(Math.round(num)) : String(Number(num.toFixed(2)));
        return str.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // امن خواندن مقدار input (اگر input وجود نداشت باز هم 0 برمی‌گرداند)
    function safeVal(selector) {
        const $el = $(selector);
        if (!$el.length) return '';
        return $el.val();
    }

    // === محاسبه توتال و تعداد ===
    function calculateTotals() {
        // همیشه از parseNumber استفاده کن تا NaN رخ نده
        const cartonQty = parseNumber(safeVal('input[name="package_qty"]'));
        const cartonPrice = parseNumber(safeVal('input[name="package_price_buy"]'));
        const unitQty = hasUnit ? parseNumber(safeVal('input[name="unit_qty"]')) : 0;
        const unitPrice = hasUnit ? parseNumber(safeVal('input[name="unit_price_buy"]')) : 0;
        const discount = parseNumber(safeVal('input[name="discount"]'));

        let total = (cartonQty * cartonPrice) + (unitQty * unitPrice) - discount;
        if (!isFinite(total)) total = 0;
        $('.all_price').val(formatNumber(total));

        let totalQty = (cartonQty * (quantityInPack || 1)) + unitQty;
        // totalQty عدد خام است (برای نمایش بدون فرمت کاما)
        $('.quantity').text(`تعداد کل: ${totalQty}`);
        $('input[name="quantity"]').val(formatNumber(totalQty));
    }

    // === سینک قیمت خرید (package <-> unit) ===
    function syncBuyPrices(changedField) {
        // اگر واحد وجود ندارد، مقدار unit را پاک کن و محاسبه کن
        if (!hasUnit) {
            $('input[name="unit_price_buy"]').val('');
            calculateTotals();
            return;
        }
        if (isSyncingBuy) return;
        isSyncingBuy = true;

        const pkg = parseNumber(safeVal('input[name="package_price_buy"]'));
        const unit = parseNumber(safeVal('input[name="unit_price_buy"]'));

        if (changedField === 'package') {
            const newUnit = (quantityInPack && quantityInPack > 0) ? (pkg / quantityInPack) : 0;
            $('input[name="unit_price_buy"]').val(formatNumber(Number(newUnit.toFixed(2))));
        } else if (changedField === 'unit') {
            const newPkg = unit * quantityInPack;
            $('input[name="package_price_buy"]').val(formatNumber(Number(newPkg.toFixed(2))));
        } else {
            const newUnit = (quantityInPack && quantityInPack > 0) ? (pkg / quantityInPack) : 0;
            $('input[name="unit_price_buy"]').val(formatNumber(Number(newUnit.toFixed(2))));
        }

        isSyncingBuy = false;
        calculateTotals();
    }

    // === سینک قیمت فروش (package <-> unit) ===
    function syncSellPrices(changedField) {
        if (!hasUnit) {
            $('input[name="unit_price_sell"]').val('');
            return;
        }
        if (isSyncingSell) return;
        isSyncingSell = true;

        const pkg = parseNumber(safeVal('input[name="package_price_sell"]'));
        const unit = parseNumber(safeVal('input[name="unit_price_sell"]'));

        if (changedField === 'package') {
            const newUnit = (quantityInPack && quantityInPack > 0) ? (pkg / quantityInPack) : 0;
            $('input[name="unit_price_sell"]').val(formatNumber(Number(newUnit.toFixed(2))));
        } else if (changedField === 'unit') {
            const newPkg = unit * quantityInPack;
            $('input[name="package_price_sell"]').val(formatNumber(Number(newPkg.toFixed(2))));
        } else {
            const newUnit = (quantityInPack && quantityInPack > 0) ? (pkg / quantityInPack) : 0;
            $('input[name="unit_price_sell"]').val(formatNumber(Number(newUnit.toFixed(2))));
        }

        isSyncingSell = false;
        // توجه: توتال بر اساس قیمت خرید محاسبه می‌شود (منطق قبلی)
        calculateTotals();
    }

    // === Event listeners (ایمن) ===

    // تعداد‌ها و تخفیف — از delegated یا ثابت استفاده می‌کنیم
    $(document).on('input', 'input[name="package_qty"], input[name="unit_qty"], input[name="discount"]', function() {
        // اگر unit_qty وجود نداره parseNumber مقدار 0 برمی‌گرداند
        calculateTotals();
    });

    // قیمت خرید بسته
    $(document).on('input', 'input[name="package_price_buy"]', function() {
        syncBuyPrices('package');
    });

    // قیمت خرید واحد (اگر وجود داشته باشد کاربر می‌تواند تغییر دهد)
    $(document).on('input', 'input[name="unit_price_buy"]', function() {
        syncBuyPrices('unit');
    });

    // قیمت فروش بسته
    $(document).on('input', 'input[name="package_price_sell"]', function() {
        syncSellPrices('package');
    });

    // قیمت فروش واحد
    $(document).on('input', 'input[name="unit_price_sell"]', function() {
        syncSellPrices('unit');
    });

    // === سرچ محصول (همان منطق قبلی) ===
    $productName.on('keyup', function(e) {
        const query = $(this).val();

        $('.search-content').toggleClass('min-hie', query.length > 0);

        if (["ArrowDown", "ArrowUp", "Enter"].includes(e.key)) return;
        if (!query.length) return $backResponse.hide();

        $backResponse.html('<li class="search-item d-flex align-items-center justify-content-center"><span class="spinner-border me-2"></span></li>').show();

        $.post(searchUrl, {
            customer_name: query,
            csrf_token: $('input[name="csrf_token"]').val()
        }, function(response) {
            itemsData = [];
            let output = '';
            if (response.status === 'success' && response.products.length) {
                response.products.forEach(p => {
                    itemsData.push(p);
                    output += `<li class="res search-item" data-id="${p.id}">${p.product_name}</li>`;
                });
            } else {
                output = '<li class="res search-item color">چیزی یافت نشد</li>';
            }
            $backResponse.html(output);
            currentIndex = -1;
        }, 'json').fail(() => {
            $backResponse.html('<li class="res search-item color">نمی توان به اطلاعات دسترسی داشت!</li>');
        });
    });

    // حرکت با کیبورد در لیست
    $productName.on('keydown', function(e) {
        const items = $backResponse.find('li');
        if (e.key === "ArrowDown") currentIndex = Math.min(currentIndex + 1, items.length - 1);
        if (e.key === "ArrowUp") currentIndex = Math.max(currentIndex - 1, 0);
        if (e.key === "Enter" && currentIndex > -1) {
            const item = items.eq(currentIndex);
            if (!item.hasClass('color')) {
                selectProduct(item.data('id'), item.text());
            }
        }
        items.removeClass('selected');
        if (currentIndex > -1) {
            const item = items.eq(currentIndex);
            item.addClass('selected');
            $backResponse.scrollTop(item.position().top + $backResponse.scrollTop() - $backResponse.height() / 2);
        }
        if (["ArrowDown", "ArrowUp", "Enter"].includes(e.key)) e.preventDefault();
    });

    // کلیک روی آیتم
    $(document).on('click', '#backResponse li', function() {
        if ($(this).hasClass('color')) return;
        selectProduct($(this).data('id'), $(this).text());
    });

    // خارج شدن از باکس سرچ
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#item_name,#backResponse').length) $backResponse.hide();
    });

    $productName.on('focus', function() {
        if ($(this).val().length) $backResponse.show();
    });

    // === دریافت اطلاعات محصول و مقداردهی فرم ===
    function selectProduct(id, name) {
        $('#item_id').val(id);
        $productName.val(name);
        $backResponse.hide();

        showLoadingOverlay();

        $.post(itemInfoUrl, { id: id }, function(res) {
            if (res.status === 'success' && res.product) {
                const p = res.product;
                const i = res.inventory || { quantity: 0 };

                quantityInPack = p.quantity_in_pack || 1;
                hasUnit = !!(p.unit_type && String(p.unit_type).trim().length);

                // quantity_in_pack field
                const $qip = $('input[name="quantity_in_pack"]');
                if ($qip.length) {
                    $qip.val(quantityInPack).parent().show();
                }

                $('.my-form').show();

                $('div.product-name').show().find('span').text(p.product_name);
                $('div.now-inventory').show();

                // مقداردهی قیمت‌های بسته (خرید/فروش)
                $('input[name="package_price_buy"]').val(formatNumber(p.package_price_buy));
                $('input[name="package_price_sell"]').val(formatNumber(p.package_price_sell));

                // محاسبه قیمت واحد از بسته (همان منطق قبلی)
                const unitPriceBuy = (quantityInPack && quantityInPack > 0) ? (p.package_price_buy / quantityInPack) : 0;
                const unitPriceSell = (quantityInPack && quantityInPack > 0) ? (p.package_price_sell / quantityInPack) : 0;

                // نمایش یا پنهان‌سازی و فعال/غیرفعال‌سازی فیلدهای واحد
                const $unitQty = $('input[name="unit_qty"]');
                const $unitPriceBuy = $('input[name="unit_price_buy"]');
                const $unitPriceSell = $('input[name="unit_price_sell"]');

                if (hasUnit) {
                    $unitQty.closest('.one').show();
                    $unitPriceBuy.closest('.one').show();
                    $unitPriceSell.closest('.one').show();

                    $unitQty.val('').prop('disabled', false);
                    $unitPriceBuy.val(formatNumber(Number(unitPriceBuy.toFixed(2)))).prop('disabled', false);
                    $unitPriceSell.val(formatNumber(Number(unitPriceSell.toFixed(2)))).prop('disabled', false);
                } else {
                    // محصول بدون واحد: فیلدها را پنهان و خالی کن و disabled باشن
                    $unitQty.val('').closest('.one').hide();
                    $unitPriceBuy.val('').closest('.one').hide();
                    $unitPriceSell.val('').closest('.one').hide();

                    $unitQty.prop('disabled', true);
                    $unitPriceBuy.prop('disabled', true);
                    $unitPriceSell.prop('disabled', true);
                }

                $('.packageType').text(p.package_type || '');
                $('.unitType').text(p.unit_type || '');
                $('.qip').text(p.quantity_in_pack || '');

                $('.quan').text(i.quantity || 0);

                // محاسبه اولیه
                calculateTotals();
            } else {
                alert('خطا در دریافت اطلاعات محصول!');
            }
        }, 'json').fail(function() {
            console.log('error fetching product info');
        }).always(hideLoadingOverlay);
    }

    // loading overlay helpers
    function showLoadingOverlay() { $('#loadingOverlay').show(); }
    function hideLoadingOverlay() { $('#loadingOverlay').hide(); }

    // === initial sync: اگر قبلاً مقادیری بود آنها را سینک کن ===
    (function initialSync() {
        // اگر ورودی‌ها در صفحه هستند مقادیر اولیه را سینک کن
        const pkgBuyVal = safeVal('input[name="package_price_buy"]');
        const pkgSellVal = safeVal('input[name="package_price_sell"]');

        // فرض کن hasUnit ممکن است از قبل درست تنظیم نشده باشد؛
        // پس اگر unit_price موجوده، فرض کن hasUnit=true برای یک سینک اولیه
        if (safeVal('input[name="unit_price_buy"]')) hasUnit = true;
        if (pkgBuyVal) syncBuyPrices('package');
        if (pkgSellVal) syncSellPrices('package');

        calculateTotals();
    })();

});
</script>
