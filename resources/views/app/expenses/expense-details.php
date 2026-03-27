    <!-- start sidebar -->
    <?php
    $title = 'جزئیات مصرفی: '. $expense['title_expenses'];
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
        <div class="content-title"> جزئیات مصرفی: <?= $expense['title_expenses'] ?></div>
        <br />
        <!-- start page content -->
        <div class="box-container">
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">عنوان مصرفی</div>
                    <div class="w100 m10 center"><?= $expense['title_expenses'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">دسته بندی</div>
                    <div class="w100 m10 center"><?= $expense['category'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">هزینه مصرفی</div>
                    <div class="w100 m10 center"><?= ($expense['price'] == 0) ? 0 : number_format($expense['price']) . ' <span class="fs11 color-orange"> (افغانی)</span>' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">پرداختی</div>
                    <div class="w100 m10 center"><?= ($expense['payment_expense'] == 0) ? 0 : number_format($expense['payment_expense']) . ' <span class="fs11 color-orange"> (افغانی)</span>' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">باقیمانده</div>
                    <div class="w100 m10 center"><?= ($expense['remainder_expense'] == 0) ? 0 : number_format($expense['remainder_expense']) . ' <span class="fs11 color-orange"> (افغانی)</span>' ?></div>
                </div>
            </div>
             <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">تاریخ ثبت</div>
                    <div class="w100 m10 center"><?= jdate('Y/m/d', strtotime($expense['created_at'])) ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">توسط</div>
                    <div class="w100 m10 center"><?= $expense['who_it'] ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">توضیحات</div>
                    <div class="w100 m10 center"><?= ($expense['description']) ? $expense['description'] : '- - - -' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item flex-justify-align">
                    <div class="w100 m10 center">عکس / بِل</div>
                    <div class="w100 m10 center flex-justify-align">
                        <?= $expense['image_expense']
                            ? '<img class="w50 cursor-p" src="' . asset('public/images/expenses/' . $expense['image_expense']) . '" alt="logo" onclick="openModal(\'' . asset('public/images/expenses/' . $expense['image_expense']) . '\')">'
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
                                <a href="#" data-url="<?= url('change-status-expense') ?>" data-id="<?= $expense['id'] ?>" class="changeStatus color btn p5 w100 m10 center">تغییر وضعیت</a>
                            </td>
                        </div>
                    </div>
                    <div class="w100 m10 center status status-column flex-justify-align" id="status"><?= ($expense['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                </div>
            </div>
            <a href="<?= url('expenses') ?>">
                <div class="btn center p5">برگشت</div>
            </a>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>