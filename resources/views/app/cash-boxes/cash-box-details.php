    <?php
    $title = 'جزئیات صندوق: ' . $item['name'];
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/change-status.php');
    ?>

    <div id="alert" class="alert" style="display: none;"><?= _error_programmer ?></div>
    <!-- loading and overlay -->
    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="content">
        <div class="content-title"> جزئیات صندوق: <?= $item['name'] ?></div>
        <?php
        $typeLabels = [
            'cash'        => 'صندوق داخلی',
            'cash_center' => 'صندوق مرکزی',
            'exchange'    => 'صرافی',
            'bank'        => 'بانک',
        ];

        $currencyLabels = [
            'af'      => 'افغانی',
            'dollar'  => 'دالر',
            'toman'   => 'تومان',
            'euro'    => 'یورو',
            'dirham'  => 'درهم',
            'kaldar'  => 'کالدار',
        ];

        $allowNegativeLabels = [
            1 => 'بله، مجاز است',
            2 => 'خیر، مجاز نیست',
        ];
        ?>

        <div class="box-container">
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">نام صندوق</div>
                    <div class="w100 m10 center"><?= htmlspecialchars($item['name']) ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">نوع صندوق</div>
                    <div class="w100 m10 center"><?= $typeLabels[$item['type']] ?? 'نامشخص' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">نوع پول</div>
                    <div class="w100 m10 center"><?= $currencyLabels[$item['currency']] ?? 'نامشخص' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">اجازه منفی شدن</div>
                    <div class="w100 m10 center"><?= $allowNegativeLabels[$item['allow_negative']] ?? 'نامشخص' ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">موجودی اولیه</div>
                    <div class="w100 m10 center"><?= $this->formatNumber($item['opening_balance']) ?? 0 ?></div>
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
                    <div class="w100 m10 center">ساخته شده توسط</div>
                    <div class="w100 m10 center"><?= htmlspecialchars($item['who_it']) ?></div>
                </div>
            </div>
            <div class="details">
                <div class="detail-item d-flex">
                    <div class="w100 m10 center">
                        <div class="w100 m10 center">
                            <td>
                                <a href="#" data-url="<?= url('change-status-cash-box') ?>" data-id="<?= $item['id'] ?>" class="changeStatus color btn p5 w100 m10 center">تغییر وضعیت</a>
                            </td>
                        </div>
                    </div>
                    <div class="w100 m10 center status status-column" id="status"><?= ($item['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                </div>
            </div>
            <a href="<?= url('cash-boxes') ?>">
                <div class="btn center p5">برگشت</div>
            </a>
        </div>
    </div>

    <?php include_once('resources/views/layouts/footer.php') ?>