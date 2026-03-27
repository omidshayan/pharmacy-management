    <!-- start sidebar -->
    <?php
    $title = 'جزئیات محصول: ' . $product['product_name'];
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
        <div class="content-title fs18"> جزئیات محصول: <?= $product['product_name'] ?></div>
        <div class="content-title fs18"> موجودی فعلی: <?= ($product['quantity'] != null) ? $product['quantity'] : 0 ?> عدد</div>

        <div class="box-container">
            <!-- general detailse -->
            <div class="accordion-title color-orange">جزئیات کلی محصول</div>
            <div class="accordion-content">
                <div class="child-accordioin">

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">نام محصول</div>
                        <div class="info-detaile"><?= $product['product_name'] ?></div>
                    </div>
                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">شعبه</div>
                        <div class="info-detaile"><?= $branch['branch_name'] ?></div>
                    </div>
                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">دسته بندی</div>
                        <div class="info-detaile"><?= $product['product_cat'] ?></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">بسته بندی / واحد</div>
                        <div class="info-detaile"><?= $product['package_type'] ?></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">تعداد داخل هر بسته / واحد</div>
                        <div class="info-detaile"><?= $this->twoFormatNumber($product['quantity_in_pack']) ?> <span class="fs12 color-orange">(<?= $product['package_type'] ?>)</span></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">قیمت خرید هر بسته / واحد</div>
                        <div class="info-detaile"><?= $this->twoFormatNumber($product['package_price_buy']) ?> <span class="fs12 color-orange">(افغانی)</span></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">قیمت فروش هر بسته / واحد</div>
                        <div class="info-detaile"><?= $this->twoFormatNumber($product['package_price_sell']) ?> <span class="fs12 color-orange">(افغانی)</span></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">واحد دیگر خرید و فروش</div>
                        <div class="info-detaile"><?= ($product['unit_type']) ? $this->twoFormatNumber($product['unit_type']) : '- - - -' ?></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">قیمت خرید</div>
                        <div class="info-detaile"><?= $this->twoFormatNumber($unitPrices['buy']) ?></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">قیمت فروش</div>
                        <div class="info-detaile"><?= $this->formatNumber($unitPrices['sell']) ?></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">کد محصول</div>
                        <div class="info-detaile"><?= ($product['product_code']) ? $product['product_code'] : '- - - -' ?></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">تاریخ ثبت</div>
                        <div class="info-detaile"><?= jdate('Y/m/d', strtotime($product['created_at'])) ?></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">ثبت شده توسط</div>
                        <div class="info-detaile"><?= $product['who_it'] ?></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">توضیحات</div>
                        <div class="info-detaile"><?= ($product['description']) ? $product['description'] : '- - - -' ?></div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p align-center">
                        <div class="title-detaile">عکس محصول</div>
                        <div class="info-detaile d-flex align-center">
                            <?= $product['product_image']
                                ? '<img class="w50 cursor-p" src="' . asset('public/images/products/' . $product['product_image']) . '" alt="logo" onclick="openModal(\'' . asset('public/images/products/' . $product['product_image']) . '\')">'
                                : ' - - - - ' ?>
                        </div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">
                            <div class="w100 m10 center">
                                <td>
                                    <a href="#" data-url="<?= url('change-status-product') ?>" data-id="<?= $product['id'] ?>" class="changeStatus color btn p5 w100 m10 center">تغییر وضعیت</a>
                                </td>
                            </div>
                        </div>
                        <div class="info-detaile">
                            <div class="w100 m10 center status status-column flex-justify-align" id="status"><?= ($product['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- buy detailse -->
            <div class="accordion-title color-orange">جزئیات فروش محصول</div>
            <div class="accordion-content">
                <div class="child-accordioin">
                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">جزئیات فروش</div>
                        <div class="info-detaile"><?= ($product['product_code']) ? $product['product_code'] : '- - - -' ?></div>
                    </div>
                </div>
            </div>

            <a href="<?= url('products') ?>">
                <div class="btn center p5">برگشت</div>
            </a>

        </div>
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>