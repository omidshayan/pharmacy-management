<script>
    $(document).ready(function() {
        let currentIndex = -1;
        let studentsData = [];
        let productTotalNumber = 1;

        function formatNumber(value) {
            return parseFloat(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",").replace(/\.0$/, '');
        }

        function calculateTotalPrice() {
            let total = 0;

            function getInputValue(selector) {
                let value = $(selector).val();
                return value ? parseFloat(value.replace(/,/g, '')) || 0 : 0;
            }

            let cartonQty = getInputValue('input[name="package_qty"]');
            let cartonPrice = getInputValue('input[name="package_price_sell"]');
            let unitQty = getInputValue('input[name="unit_qty"]');
            let unitPrice = getInputValue('input[name="unit_price_sell"]');
            let discount = getInputValue('input[name="sale_discount"]');

            let cartonTotal = cartonQty * cartonPrice;
            let unitTotal = unitQty * unitPrice;
            total = cartonTotal + unitTotal - discount;

            $('.all_price').val(formatNumber(total));
        }

        function calculateTotalQuantity() {
            let cartonQty = parseFloat($('input[name="package_qty"]').val().replace(/,/g, '')) || 0;
            let unitQty = parseFloat($('input[name="unit_qty"]').val().replace(/,/g, '')) || 0;

            if (typeof productTotalNumber === 'undefined' || isNaN(productTotalNumber)) {
                productTotalNumber = 1;
            }

            let totalQuantity = (cartonQty * productTotalNumber) + unitQty;

            $('.quantity').text(`تعداد عددی: ${totalQuantity}`);
            $('input[name="quantity"]').val(formatNumber(totalQuantity));
        }

        $('input[name="package_qty"], input[name="unit_qty"]').on('input', function() {
            calculateTotalQuantity();
        });

        $('input[name="package_qty"], input[name="package_price_sell"], input[name="unit_qty"], input[name="unit_price_sell"], input[name="discount"]').on('input', function() {
            calculateTotalPrice();
        });

        $('#product_name').on('keydown', function(e) {
            if (e.key === "ArrowDown" || e.key === "ArrowUp" || e.key === "Enter") {
                e.preventDefault();
                navigateOptions(e);
            }
        });

        $('#product_name').on('keyup', function(e) {
            if ($(this).val().length > 0) {
                $('.search-content').addClass('min-hie');
            } else {
                $('.search-content').removeClass('min-hie');
            }
            let query = $(this).val();
            if (e.key !== "ArrowDown" && e.key !== "ArrowUp" && e.key !== "Enter") {
                if (query.length > 0) {
                    $.ajax({
                        url: '<?= url('search-product-sale') ?>',
                        method: 'POST',
                        data: {
                            customer_name: query,
                            csrf_token: $('input[name="csrf_token"]').val()
                        },
                        dataType: 'json',
                        success: function(response) {
                            let output = '';
                            studentsData = [];
                            if (response.status === 'success' && response.products.length > 0) {
                                response.products.forEach(function(product) {
                                    studentsData.push(product);
                                    output += '<li class="res search-item" role="option" data-id="' + product.id + '">' + product.product_name + '</li>';
                                });
                            } else {
                                output = '<li class="res search-item color" role="option">چیزی یافت نشد</li>';
                            }
                            $('#backResponse').html(output).show();
                            currentIndex = -1;
                        },
                        error: function(xhr) {
                            $('#backResponse').html('<li class="res search-item color" role="option">خطایی رخ داد، لطفا دوباره امتحان کنید</li>').show();
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    $('#backResponse').hide();
                }
            }
        });

        $(document).on('click', '#backResponse li', function() {
            const productName = $(this).text();
            const productId = $(this).data('id');
            $('#product_name').val(productName);
            $('#product_id').val(productId);
            $('#backResponse').hide();
            fetchProductInfo(productId);
        });

        function navigateOptions(e) {
            const items = $('#backResponse li');
            const container = $('#backResponse');

            if (e.key === "ArrowDown" && currentIndex < items.length - 1) {
                currentIndex++;
            } else if (e.key === "ArrowUp" && currentIndex > 0) {
                currentIndex--;
            } else if (e.key === "Enter" && currentIndex > -1) {
                const selectedItem = items.eq(currentIndex);
                const productName = selectedItem.text();
                const productId = selectedItem.data('id');

                $('#product_name').val(productName);
                $('#product_id').val(productId);
                $('#backResponse').hide();
                fetchProductInfo(productId);
            }

            items.removeClass('selected');
            if (currentIndex > -1) {
                items.eq(currentIndex).addClass('selected');
                container.scrollTop(items.eq(currentIndex).position().top + container.scrollTop() - container.height() / 2);
            }
        }

        function fetchProductInfo(productId) {
            $.ajax({
                url: '<?= url('get-product-infos-sale') ?>',
                method: 'POST',
                data: {
                    id: productId,
                    csrf_token: $('input[name="csrf_token"]').val()
                },
                beforeSend: function() {
                    showLoadingOverlay();
                },
                success: function(response) {
                    if (response.status === 'success' && response.product) {
                        $('.my-form').show();
                        let product = response.product;
                        var product_name = product.product_name;
                        productTotalNumber = product.quantity_in_pack || 1;
                        console.log(product);
                        calculateTotalQuantity();
                        $('input[name="quantity_in_pack"]').val(productTotalNumber).parent().show();
                        $('input[name="product_id"]').val(product.product_id).parent().show();
                        if (product.package_type && product.package_type.trim() !== "") {

                            var quantityInPack = product.quantity_in_pack;
                            var currentInventory = product.quantity;
                            var pack = Math.floor(currentInventory / quantityInPack);
                            var current_unit = currentInventory % quantityInPack;

                            console.log("تعداد بسته‌ها:", pack);
                            console.log("تعداد واحدهای باقی‌مانده:", current_unit);

                            $('.producInfos').show();

                            $('.pro_type').text(product.package_type);
                            $('.pro_quantity').text(quantityInPack);
                            $('.uni_type').text(product.unit_type);
                            $('.current_inventory').text(currentInventory);
                            $('.current_inventory_pack').text(pack);
                            $('.current_unit').text(current_unit);

                            $('input[name="package_price_buy"]').val(formatNumber(product.package_price_buy)).parent().show();
                            $('input[name="package_price_sell"]').val(formatNumber(product.package_price_sell)).parent().show();
                            $('input[name="package_qty"]').val("").closest('.one').show();
                        } else {
                            $('input[name="package_price_buy"]').parent().hide();
                            $('input[name="package_price_sell"]').parent().hide();
                            // $('input[name="package_price_buy"]').val(formatNumber(product.package_price_buy)).parent().hide();
                            // $('input[name="package_price_sell"]').val(formatNumber(product.package_price_sell)).parent().hide();
                            $('input[name="package_qty"]').val("").closest('.one').hide();
                        }

                        if (product.unit_type && product.unit_type.trim() !== "") {
                            $('input[name="unit_price_buy"]').val(formatNumber(product.unit_price_buy)).parent().show();
                            $('input[name="unit_price_sell"]').val(formatNumber(product.unit_price_sell)).parent().show();
                            $('input[name="unit_qty"]').val("").closest('.one').show();
                        } else {
                            $('input[name="unit_price_buy"]').parent().hide();
                            $('input[name="unit_price_sell"]').parent().hide();
                            // $('input[name="unit_price_buy"]').val(formatNumber(product.unit_price_buy)).parent().hide();
                            // $('input[name="unit_price_sell"]').val(formatNumber(product.unit_price_sell)).parent().hide();
                            $('input[name="unit_qty"]').val("").closest('.one').hide();
                        }
                    } else {
                        alert('خطا در دریافت اطلاعات دوا!');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                },
                complete: function() {
                    hideLoadingOverlay();
                }
            });
        }

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#product_name, #backResponse').length) {
                $('#backResponse').hide();
            }
        });

        $('#product_name').on('focus', function() {
            if ($(this).val().length > 0) {
                $('#backResponse').show();
            }
        });

    });

    function showLoadingOverlay() {
        $('#loadingOverlay').show();
    }

    function hideLoadingOverlay() {
        $('#loadingOverlay').hide();
    }
</script>