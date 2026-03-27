    <!-- start sidebar -->
    <?php
    $title = 'جزئیات دسته بندی: ' . $product_cat['product_cat_name'];
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/change-status.php');
    ?>
    <!-- end sidebar -->

    <div id="alert" class="alert" style="display: none;"><?=_error_programmer?></div>
    <!-- loading and overlay -->
    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> جزئیات دسته بندی: <?= $product_cat['product_cat_name'] ?></div>
        <br />
        <!-- start page content -->
        <div class="box-container">
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">نام</div>
                    <div class="w100 m10 center"><?= $product_cat['product_cat_name'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">ساخته شده توسط</div>
                    <div class="w100 m10 center"><?= $product_cat['who_it'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">تاریخ ساخت</div>
                    <div class="w100 m10 center"><?= jdate('Y/m/d', strtotime($product_cat['created_at'])) ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">
                        <!-- HTML -->
                        <div class="w100 m10 center">
                            <td>
                                <a href="#" data-url="<?= url('change-status-product-cat') ?>" data-id="<?= $product_cat['id'] ?>" class="changeStatus color btn p5 w100 m10 center">تغییر وضعیت</a>
                            </td>
                        </div>
                    </div>
                    <div class="w100 m10 center status status-column" id="status"><?= ($product_cat['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                </div>
            </div>
            <a href="<?= url('product_categories') ?>">
                <div class="btn center p5">برگشت</div>
            </a>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>