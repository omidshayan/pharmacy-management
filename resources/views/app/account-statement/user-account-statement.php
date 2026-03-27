    <!-- start sidebar -->
    <?php
    $title = 'صورتحساب: ' . $user['user_name'];
    include_once('resources/views/layouts/header.php');
    ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title fs18">صورتحساب: <?= $user['user_name'] ?></div>

        <!-- short details -->
        <div class="content-container mt20">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>مجموع عملیات</th>
                        <th>مجموع رسید شده</th>
                        <th>مجموع پرداخت شده</th>
                        <th>بالانس</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $this->formatNumber(count($user_statement)) ?></td>
                        <td><?= $this->formatNumber($current_balance_row['total_in'] ?? 0) ?></td>
                        <td><?= $this->formatNumber($current_balance_row['total_out'] ?? 0) ?></td>
                        <td><?= $this->formatNumber($current_balance_row['balance'] ?? 0) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- start page content -->
        <div class="content-container mt20">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>شماره سند / بِل</th>
                        <th>تاریخ</th>
                        <th>نوع تراکنش</th>
                        <th>مبلغ کل</th>
                        <th>پرداخت</th>
                        <th>تخفیف</th>
                        <th>باقیمانده سند</th>
                        <th>مانده حساب</th>
                        <th>جزئیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $transactionTypes = [
                        1 => 'بِل فروش',
                        2 => 'بِل خرید',
                        3 => 'برگشت از فروش',
                        4 => 'برگشت از خرید',
                        5 => 'دریافت نقد از مشتری',
                        6 => 'پرداخت نقد به مشتری',
                    ];

                    $perPage = 30;
                    $data = paginate($user_statement, $perPage);
                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $number = ($currentPage - 1) * $perPage + 1;

                    foreach ($data as $item) {
                        $total = (float)$item['total_amount'];
                        $paid = (float)$item['paid_amount'];
                        $discount = (float)$item['discount'];
                        $type = (int)$item['transaction_type'];

                        $docRemainder = 0;
                        if (in_array($type, [1, 2, 3, 4])) {
                            $docRemainder = $total - $discount - $paid;
                        } else {
                            $docRemainder = 0;
                        }

                        $bal = (float)$item['running_balance'];
                        $balClass = $bal > 0 ? 'text-success' : ($bal < 0 ? 'text-danger' : '');
                        $balStatus = $bal > 0 ? '<span class="fs12 color-orange"> (طلبکار) </span> ' : ($bal < 0 ? '<span class="fs12 color-orange"> (قرضدار) </span>' : ' (تسویه)');
                    ?>
                        <tr>
                            <td class="color-orange"><?= $this->twoFormatNumber($number) ?></td>
                            <td><?= $item['ref_id'] ?: '---' ?></td>
                            <td><?= jdate('Y/m/d', $item['transaction_date']) ?></td>
                            <td>
                                <span class="badge <?= $item['source'] == 'cash' ? 'badge-info' : '' ?>">
                                    <?= $transactionTypes[$type] ?? 'نامشخص' ?>
                                </span>
                            </td>

                            <td><?= $total > 0 ?  $this->twoFormatNumber($total) : '---' ?></td>

                            <td class="text-success"><?= $paid > 0 ?  $this->twoFormatNumber($paid) : '0' ?></td>

                            <td><?= $discount > 0 ?  $this->twoFormatNumber($discount) : '0' ?></td>

                            <td class="text-danger">
                                <?= ($docRemainder != 0) ?  $this->twoFormatNumber(abs($docRemainder)) : '0' ?>
                            </td>

                            <td class="font-weight-bold <?= $balClass ?>" dir="ltr">
                                <?= $balStatus . $this->twoFormatNumber(abs($bal)) ?>
                            </td>

                            <td>
                                <a href="<?= url('get-invoice/' . $item['id']) ?>">
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
            </table>
            <div class="flex-justify-align mt20 paginate-section">
                <div class="table-info fs14">تعداد کل: <?= $this->formatNumber(count($user_statement)) ?></div>
                <?php
                if (count($user_statement) == null) { ?>
                    <div class="center color-red fs12">
                        <i class="fa fa-comment"></i>
                        <?= _not_infos ?>
                    </div>
                <?php } else {
                    if (count($user_statement) > 30) {
                        echo paginateView($user_statement, 30);
                    }
                }
                ?>
            </div>
        </div>
        <!-- end page content -->
        <div class="mt15"><?= $this->back_link('account-statement') ?></div>
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>