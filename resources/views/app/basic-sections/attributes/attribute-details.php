    <!-- start sidebar -->
    <?php
    $title = 'نام ویژه‌گی: ' . $item['att_name'];
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/change-status.php');
    ?>
    <!-- end sidebar -->

    <div id="alert" class="alert" style="display: none;"><?= _error_programmer ?></div>
    <!-- loading and overlay -->
    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> نام ویژه‌گی: <?= $item['att_name'] ?></div>
        <br />
        <!-- start page content -->
        <div class="box-container">
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">نام</div>
                    <div class="w100 m10 center"><?= $item['att_name'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">حالت ویژه‌گی</div>
                    <div class="w100 m10 center"> <?= ($item['att_type'] == 'checkbox') ? 'حالت انتخابی' : 'حالت متن کوتاه' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">ساخته شده توسط</div>
                    <div class="w100 m10 center"><?= $item['who_it'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">تاریخ ساخت</div>
                    <div class="w100 m10 center"><?= jdate('Y/m/d', strtotime($item['created_at'])) ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">
                        <!-- HTML -->
                        <div class="w100 m10 center">
                            <td>
                                <a href="#" data-url="<?= url('change-status-attribute') ?>" data-id="<?= $item['id'] ?>" class="changeStatus color btn p5 w100 m10 center">تغییر وضعیت</a>
                            </td>
                        </div>
                    </div>
                    <div class="w100 m10 center status status-column" id="status"><?= ($item['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                </div>
            </div>
            <a href="<?= url('attributes') ?>">
                <div class="btn center p5">برگشت</div>
            </a>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>