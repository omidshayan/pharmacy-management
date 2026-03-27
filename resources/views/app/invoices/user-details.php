<!-- start sidebar -->
<?php
$title = 'جزئیات کاربر: ' . $user['user_name'];
include_once('resources/views/layouts/header.php');
include_once('resources/views/scripts/change-status.php');
?>
<!-- end sidebar -->
<div id="alert" class="alert" style="display: none;"></div>

<!-- loading and overlay -->
<div class="overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<!-- Start content -->
<div class="content">
    <div class="content-title"> جزئیات بِل شماره : <?= $sale_invoice['id'] ?></div>
    <br />
    <!-- start page content -->
    <div class="box-container">

        <div class="accordion-title color-orange">مشخصات عمومی</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نام</div>
                    <div class="info-detaile"><?= $user['user_name'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نام پدر</div>
                    <div class="info-detaile"><?= ($user['father_name'] ? $user['father_name'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">شماره</div>
                    <div class="info-detaile"><?= $user['phone'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نوع کاربر</div>
                    <div class="info-detaile">
                        <?= $user['is_customer'] || $user['is_seller']
                            ? ($user['is_customer'] ? 'مشتری' : '') .
                            ($user['is_customer'] && $user['is_seller'] ? ' / ' : '') .
                            ($user['is_seller'] ? 'فروشنده' : '')
                            : '- - - -' ?>
                    </div>
                </div>
                <!-- <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile">رمزعبور: </div>
                    <div class="info-detaile" id="passwordDisplay"><?= str_repeat('*', strlen($employee['password'])) ?></div>
                    <div class="eye-icon" onmousedown="showPassword()" onmouseup="hidePassword()">
                        <span id="eyeIcon" style="font-size: 18px;">&#128065;</span>
                    </div>
                </div> -->
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">آدرس</div>
                    <div class="info-detaile"><?= ($user['address'] ? $user['address'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">توضیحات</div>
                    <div class="info-detaile"><?= ($user['description'] ? $user['description'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">تاریخ ثبت</div>
                    <div class="info-detaile"><?= jdate('Y/m/d', strtotime($user['created_at'])) ?></div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile">عکس</div>
                    <div class="info-detaile">
                        <?= ($user['user_image'] ? '<a href="' . asset('public/images/users/' . $user['user_image']) . '" target="_blank"><img src="' . asset('public/images/users/' . $user['user_image']) . '" class="w50" alt=""></a>' : '- - - - ') ?>
                    </div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile"><a href="#" data-url="<?= url('change-status-center') ?>" data-id="<?= $user['id'] ?>" class="changeStatus color btn p5 w100 m10 center" id="submit">تغییر وضعیت</a></div>
                    <div class="info-detaile">
                        <div class="w100 m10 center status status-column" id="status"><?= ($user['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-title color-orange">اطلاعات مالی <?= ($summary['total_debt'] > 0) ? '<span class="fs11 color-red">(قرض‌دار است)</span>' : '' ?></div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مجموع حساب</div>
                    <div class="info-detaile"><?= ($summary['sale_total_amount']) ? number_format($summary['sale_total_amount']) : '0' ?> <?= _afghani ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مجموع پرداختی</div>
                    <div class="info-detaile"><?= ($summary['total_payment']) ? number_format($summary['total_payment']) : '0' ?> <?= _afghani ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مجموع تخفیف</div>
                    <div class="info-detaile"><?= ($summary['total_discount']) ? number_format($summary['total_discount']) : '0' ?> <?= _afghani ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مجموع قرضداری</div>
                    <div class="info-detaile"><?= ($summary['total_debt']) ? number_format($summary['total_debt']) : '0' ?> <?= _afghani ?></div>
                </div>
            </div>
        </div>

        <div class="accordion-title color-orange">لیست بِلهای خرید</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>شماره بِل</th>
                            <th>تاریخ</th>
                            <th>قیمت کل</th>
                            <th>تخفیف</th>
                            <th>باقیمانده</th>
                            <th>ویرایش</th>
                            <th>جزئیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $perPage = 10;
                        $data = paginate($invoices, $perPage);
                        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $number = ($currentPage - 1) * $perPage + 1;
                        foreach ($data as $item) {
                        ?>
                            <tr>
                                <td class="color-orange"><?= $number ?></td>
                                <td><?= $item['id'] ?></td>
                                <td><?= $item['sale_invoice_date'] ?></td>
                                <td><?= $item['sale_total_amount'] ?></td>
                                <td><?= $item['sale_discount'] ?></td>
                                <td><?= $item['remaining_amount'] ?></td>

                                <td>
                                    <a href="<?= url('edit-user/' . $item['id']) ?>" class="color-orange">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" class="color-orange" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= url('sale-invoice-details/' . $item['id']) ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" class="color-orange" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            $number++;
                        }
                        ?>
                    </tbody>
                    <tbody></tbody>
                </table>
                <div class="flex-justify-align mt20 paginate-section">
                    <div class="table-info fs14">تعداد کل: <?= count($invoices) ?></div>
                    <?php
                    if (count($invoices) == null) { ?>
                        <div class="center">
                            <i class="fa fa-comment"></i>
                            <?= 'اطلاعاتی ثبت نشده است' ?>
                        </div>
                    <?php } else {
                        if (count($invoices) > 10) {
                            echo paginateView($invoices, 10);
                        }
                    }
                    ?>
                </div>



            </div>
        </div>



        <a href="<?= url('users') ?>">
            <div class="btn center p5">برگشت</div>
        </a>
    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>