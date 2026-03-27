    <!-- check user -->
    <?php
    if ($invoice_infos['user_id'] != 1) {
        $balanceStatusText = $currentBalanceValue > 0 ? ' مجموع طلب از شرکت' : ($currentBalanceValue < 0 ? ' مجموع قرضداری به شرکت' : '');
        if ($invoice_infos['user_name']) { ?>
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
                            <span>
                                <?= $oldBalance == 0
                                    ? $this->formatSimpleNumber(0)
                                    : $this->formatSimpleNumber($oldBalance) . ' ' . _afghani ?>
                            </span>
                        </td>
                        <td class="bold">مانده از این بِل: <span><?= $this->formatNumber($remaining_amount) ?> <?= _afghani ?></span></td>
                        <td class="bold"><?= $balanceStatusText ?></td>
                        <td class="bold">
                            <?= $this->formatSimpleNumber(abs($currentBalanceValue)) ?? $this->formatNumber(0) ?>
                            <?= _afghani ?>
                        </td>
                    </tr>
                </tbody>
                </tbody>
            </table>
    <?php }
    }
    ?>

    <div class="d-flex justify-between">
        <div class="fs12 center mt5 color-print"> آدرس: <?= $factor_infos['address'] ?></div>
        <div class="fs12 center mt5 color-print"><?= $factor_infos['website'] ?> - <?= $factor_infos['email'] ?></div>
    </div>


    <?php
    if (isset($transactin)) { ?>
        <!-- emza -->
        <div class="emza d-flex mt40 bold">
            <div>امضای فروشنده</div>
            <div>امضای خریدار</div>
            <div>امضای تحویل گیرنده</div>
        </div>
    <?php }
    ?>

    </div>
    <!-- end content -->

    <?php
    $timestamp = $invoice_infos['date'];
    $discount = ($invoice_infos['discount']) ? '-' . $this->formatPrice($invoice_infos['discount']) : '';
    $date = date('Ymd', $timestamp);
    $fa =  $invoice_infos['id'] . '-' . $date . $discount;
    ?>

    <!-- barcode -->
    <!-- <script src="<?= asset('public/assets/js/qrcode.js') ?>"></script>
    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= $fa ?>",
            width: 60,
            height: 60
        });
    </script> -->