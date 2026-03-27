<?php
$title = 'کاردکس کالا: ' . $product['product_name'];
include_once('resources/views/layouts/header.php');
include_once('public/alerts/error.php');
?>

<!-- Start content -->
<div class="content">
    <div class="content-title"> کاردکس کالا: <?= $product['product_name'] ?>
        <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
    </div>
    <?php
    $help_title = _help_title;
    $help_content = _help_desc;
    include_once('resources/views/helps/help.php');
    ?>

    <!-- show expenses -->
    <div class="content-container mt20 mb20">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>شماره بِل</th>
                    <th>نوع</th>
                    <th>تاریخ</th>
                    <th>محل</th>
                    <th>تعداد</th>
                    <th>مانده</th>
                    <th>فی (واحد)</th>
                    <th>مبلغ کل</th>
                    <th>توسط</th>
                    <th>بِل</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $perPage = 20;
                $data = paginate($cardexData, $perPage);

                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $number = ($currentPage - 1) * $perPage + 1;

                foreach ($data as $item) {
                ?>
                    <tr>
                        <td class="color-orange"><?= $number ?></td>
                        <td><?= $item['invoice_number'] ?></td>
                        <td>
                            <span class="<?= $item['color'] ?>">
                                <?= $item['type_label'] ?>
                            </span>
                        </td>
                        <td><?= jdate('Y/m/d' . strtotime($item['date'])) ?></td>
                        <td><?= $item['warehouse'] ?></td>

                        <td class="<?= $item['color'] ?> font-weight-bold fs16" dir="ltr">
                            <?= ($item['color'] == 'text-danger' ? '-' : '+') ?> <?= $this->twoFormatNumber($item['qty']) ?>
                        </td>

                        <td class="font-weight-bold fs16"><?= $this->twoFormatNumber($item['balance']) ?></td>
                        <td class="font-weight-bold fs16"><?= $this->twoFormatNumber($item['unit_price']) ?></td>
                        <td class="font-weight-bold fs16"><?= $this->twoFormatNumber($item['total_price']) ?></td>

                        <td><?= $item['who'] ?></td>

                        <td>
                            <a href="<?= url('invoice-details/' . $item['idd']) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    fill="currentColor"
                                    class="bi bi-eye"
                                    viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"
                                        class="color-orange" />
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
            <div class="table-info fs14">تعداد کل: <?= count($cardexData) ?></div>
            <?php
            if (count($cardexData) == null) { ?>
                <div class="center color-red fs12">
                    <i class="fa fa-comment"></i>
                    <?= _not_infos ?>
                </div>
            <?php } else {
                if (count($cardexData) > 20) {
                    echo paginateView($cardexData, 20);
                }
            }
            ?>
        </div>
    </div>
    <?= $this->back_link('inventories') ?>
    <!-- end page content -->
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>