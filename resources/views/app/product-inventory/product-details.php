    <!-- start sidebar -->
    <?php
    $title = 'جزئیات دوا: ' . $product['product_name'];
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/change-status.php');
    include_once('resources/views/scripts/show-img-modal.php');
    ?>
    <!-- end sidebar -->

    <div id="alert" class="alert" style="display: none;"></div>
    <!-- loading and overlay -->
    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> جزئیات دوا: <?= $product['product_name'] ?></div>
        <br />
        <!-- start page content -->
        <div class="box-container">
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">نام دوا</div>
                    <div class="w100 m10 center"><?= $product['product_name'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">دسته بندی</div>
                    <div class="w100 m10 center"><?= $product['product_cat'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">بسته‌بندی / واحد</div>
                    <div class="w100 m10 center"><?= $product['package_type'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">تعداد داخل هر بسته / واحد</div>
                    <div class="w100 m10 center"><?= $product['package_qty'] ?> <span class="fs12 color-orange">(<?= $product['package_type'] ?>)</span></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">قیمت خرید هر بسته / واحد</div>
                    <div class="w100 m10 center"><?= number_format($this->formatScore($product['package_price_buy'])) ?> <span class="fs12 color-orange">(افغانی)</span></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">قیمت فروش هر بسته / واحد</div>
                    <div class="w100 m10 center"><?= number_format($this->formatScore($product['package_price_sell'])) ?> <span class="fs12 color-orange">(افغانی)</span></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">واحد دیگر خرید و فروش</div>
                    <div class="w100 m10 center"><?= ($product['unit_type']) ? $product['unit_type'] : '- - - -' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">قیمت خرید</div>
                    <div class="w100 m10 center">
                        <?php if (!empty($product['unit_type'])): ?>
                            <?= number_format($this->formatScore($product['unit_price_buy'])) ?>
                            <span class="fs12 color-orange">(افغانی)</span>
                        <?php else: ?>
                            - - - -
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">قمیت فروش</div>
                    <div class="w100 m10 center">
                        <?php if (!empty($product['unit_type'])): ?>
                            <?= number_format($this->formatScore($product['unit_price_sell'])) ?>
                            <span class="fs12 color-orange">(افغانی)</span>
                        <?php else: ?>
                            - - - -
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">کد دوا</div>
                    <div class="w100 m10 center"><?= ($product['product_code']) ? $product['product_code'] : '- - - -' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">تاریخ ثبت</div>
                    <div class="w100 m10 center"><?= jdate('Y/m/d', strtotime($product['created_at'])) ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">توسط</div>
                    <div class="w100 m10 center"><?= $product['who_it'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">توضیحات</div>
                    <div class="w100 m10 center"><?= ($product['description']) ? $product['description'] : '- - - -' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item flex-justify-align">
                    <div class="w100 m10 center">عکس دوا</div>
                    <div class="w100 m10 center flex-justify-align">
                        <?= $product['product_image']
                            ? '<img class="w50 cursor-p" src="' . asset('public/images/products/' . $product['product_image']) . '" alt="logo" onclick="openModal(\'' . asset('public/images/products/' . $product['product_image']) . '\')">'
                            : ' - - - - ' ?>
                    </div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">
                        <!-- HTML -->
                        <div class="w100 m10 center">
                            <td>
                                <a href="#" data-url="<?= url('change-status-expense') ?>" data-id="<?= $product['id'] ?>" class="changeStatus color btn p5 w100 m10 center">تغییر وضعیت</a>
                            </td>
                        </div>
                    </div>
                    <div class="w100 m10 center status status-column flex-justify-align" id="status"><?= ($product['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                </div>
            </div>
            <a href="<?= url('products') ?>">
                <div class="btn center p5">برگشت</div>
            </a>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>