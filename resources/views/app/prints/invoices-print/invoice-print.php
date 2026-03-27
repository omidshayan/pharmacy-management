    <?php
    $title = 'پرینت بِل';

    // factor titles
    $types = [
        1 => 'بـــــل فــــــــروش',
        2 => 'بـــــل خـــــریــــد',
        3 => 'بـــــل بــــرگـــشت از فــــروش',
        4 => 'بـــــل بــــرگـــشت از خـــــریــــد',
    ];
    $factorType = $types[$invoice_infos['invoice_type']] ?? '';

    include_once('resources/views/app/prints/layouts/header-print.php');

    $remaining_amount = $invoice_infos['total_amount'] - ($invoice_infos['paid_amount'] + $invoice_infos['discount']);

    // if ($debtor) {
    //     $finalBalance = $debtor['debtor'] + $remaining_amount;
    // }
    ?>

    <!-- products details items  -->
    <table class="table-print w100 color-print center mt15">
        <thead>
            <tr class="fs14">
                <th class="w20 fs11">شماره</th>
                <th class="w300">نام کالا</th>
                <th>تعداد بسته</th>
                <th>تعداد جز</th>
                <th>تعداد کل</th>
                <th>قیمت واحد</th>
                <!-- <th class="fs11">تخفیف</th> -->
                <th>قیمت کل</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $total_price = 0;
            if (!empty($invoice_items) && is_array($invoice_items)) {
                $number = 1;
                foreach ($invoice_items as $item) {
                    $total_price += $item['item_total_price'];
                    $unitPrices = $this->calculateUnitPrices($item);

                    // check type for price
                    $unitPrice = '';
                    if ($invoice_infos['invoice_type'] == 1) {
                        $unitPrice = $unitPrices['sell'];
                    } else {
                        $unitPrice = $unitPrices['buy'];
                    }

            ?>
                    <tr>
                        <td><?= $this->to_farsi_number($number) ?></td>
                        <td class="w300"><?= $this->to_farsi_number($item['product_name']) ?></td>

                        <td class="w80">
                            <?= $this->formatNumber($item['package_qty']) ?>
                            <?php if ((float)$item['package_qty'] > 0): ?>
                                <span class="fs12"><?= $item['package_type'] ?></span>
                            <?php endif; ?>
                        </td>

                        <td class="w80"><?= $this->formatNumber($item['unit_qty']) ?? $this->formatNumber(0) ?> <span class="fs12"><?= $item['unit_type'] ?></span></td>

                        <td class="w80">
                            <?= $this->formatNumber($item['quantity']) ?>
                            <span class="fs12">
                                <?= trim($item['unit_type'] ?? '') !== ''
                                    ? $item['unit_type']
                                    : ($item['package_type'] ?? '') ?>
                            </span>
                        </td>

                        <td class="w80"><?= $this->formatNumber(number_format($unitPrice)) ?></td>
                        <td class="w80"><?= $this->to_farsi_number(number_format($item['item_total_price'])) ?></td>
                    </tr>
            <?php
                    $number++;
                }
            }
            ?>

            <?php if (!empty(trim($invoice_infos['description']))) : ?>
                <tr class="fs15">
                    <td colspan="8" class="text-right bold w300">توضیحات: <?= htmlspecialchars($invoice_infos['description']) ?> </td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
    <!-- end product details itmes -->

    <!-- amount details -->
    <table class="table-print w100 color-print center mt5">
        <thead>
            <tr>
                <th colspan="4">مبلغ کل</th>
            </tr>
        </thead>
        <tbody>

            <tr class="fs15">
                <?php if ($invoice_infos['total_amount'] > 0) : ?>
                    <td colspan="1" class="text-right bold w300"><?= $this->number_to_dari_words($invoice_infos['total_amount']) ?> <span class="fs11">افغانی</span></td>
                    <td colspan="2" class="w200 bold"><?= $this->to_farsi_number(number_format($invoice_infos['total_amount'])) ?> افغانی</td>
                <?php endif; ?>
            </tr>
            <tr class="fs15">
                <td class="bold text-right">پرداختی: <?= ($invoice_infos['paid_amount']) ? $this->to_farsi_number(number_format($invoice_infos['paid_amount'])) . _afghani : $this->to_farsi_number(0) ?></td>
                <td class="bold text-right">تخفیف: <?= ($invoice_infos['discount']) ? $this->to_farsi_number(number_format($invoice_infos['discount'])) . _afghani : $this->to_farsi_number(0) ?></td>
                <td class="bold">باقیمانده: <?= ($remaining_amount) ? $this->to_farsi_number(number_format($remaining_amount)) . _afghani : $this->to_farsi_number(0) ?></td>
            </tr>
        </tbody>
    </table>

    <!-- seller account and footer -->
    <?php include_once('resources/views/app/prints/layouts/footer.php'); ?>