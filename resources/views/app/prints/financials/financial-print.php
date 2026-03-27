    <?php
    $title = 'پرینت بِل';

    // factor titles
    $types = [
        5 => 'رســـیـد دریـــافــت پــــول',
        6 => 'رســـیـد پــــرداخـــت پــــول',
    ];
    $factorType = $types[$transaction['type']] ?? 'fffff';

    include_once('resources/views/app/prints/financials/header-print-deposit.php');

    ?>

    <table class="table-print w100 color-print center mt5">
        <thead>

        </thead>
        <tbody>
        <tbody>
            <tr class="fs15">
                <td class="bold">مبلغ
                </td>
                <td class="bold">
                    <span><?= $this->formatNumber($transaction['amount']) ?> <?= _afghani ?> <?= $transaction['type'] == 5 ? 'دریافت شد' : 'پرداخت شد' ?></span>
                </td>
            </tr>
            <tr class="fs15">
                <td class="bold">بابت</td>
                <td class="bold"><?= $transaction['description'] ?></td>
            </tr>
        </tbody>
        </tbody>
    </table>

    <!-- seller details -->
    <?php
    if ($transaction['user_name']) { ?>
        <table class="table-print w100 color-print center mt5">
            <thead>
                <tr>
                    <th colspan="4">جزئیات مالی شما</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                <tr class="fs15">
                    <td class="bold">مانده از حساب قبلی:
                        <span><?= $this->formatNumberFcator($oldBalance) . _afghani  ?></span>
                    </td>
                    <td class="bold"><?= $transaction['type'] == 5 ? 'مبلغ دریافت شده' : 'مبلغ پرداخت شده' ?>: <span><?= $this->formatNumberFcator($transaction['amount']) ?> <?= _afghani ?></span></td>
                    <td class="bold">مانده حساب نهایی</td>
                    <td class="bold"><?= $this->formatNumberFcator($newBalance) ?? $this->formatNumberFcator(0) ?> <?= _afghani ?></td>
                </tr>
            </tbody>
            </tbody>
        </table>
    <?php }
    ?>

    <!-- footer -->
    <div class="d-flex justify-between">
        <div class="fs12 center mt5 color-print"> آدرس: <?= $factor_infos['address'] ?></div>
        <div class="fs12 center mt5 color-print"><?= $factor_infos['website'] ?> - <?= $factor_infos['email'] ?></div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(() => {
                printReceipt();
            }, 200);
        };
    </script>

    </div>

    <?php include_once('resources/views/layouts/footer.php') ?>