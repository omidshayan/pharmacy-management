<?php
$title = 'نمایش موجودی';
include_once('resources/views/layouts/header.php');
include_once('public/alerts/error.php');
?>

<!-- Start content -->
<div class="content">
    <div class="content-title"> نمایش موجودی
        <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
    </div>
    <?php
    $help_title = _help_title;
    $help_content = _help_desc;
    include_once('resources/views/helps/help.php');
    ?>

    <!-- show expenses -->
    <div class="content-container mt20">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نام محصول</th>
                    <th>موجودی کل</th>
                    <?php
                    if ($hasWarehouse) {
                        foreach ($warehouses as $warehouse) { ?>
                            <th><?= $warehouse['warehouse_name'] ?></th>
                    <?php }
                    }
                    ?>
                    <th>کاردکس</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $perPage = 20;
                $data = paginate($inventories, $perPage);

                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $number = ($currentPage - 1) * $perPage + 1;

                foreach ($data as $product) {
                ?>
                    <tr>
                        <td class="color-orange"><?= $number ?></td>
                        <td>
                            <?= $product['product_name'] ?>
                            <?php if (!empty($product['package_type'])): ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $this->formatNumber($product['total_quantity']) ?></td>
                        <?php if ($hasWarehouse): ?>
                            <?php foreach ($warehouses as $warehouse):
                                $wid = $warehouse['id'];
                                $qty = $product['warehouses'][$wid] ?? 0;
                            ?>
                                <td><?= $this->formatNumber($qty) ?></td>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <td>
                            <a href="<?= url('cardex-product/' . $product['id']) ?>">
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
            <div class="table-info fs14">تعداد کل: <?= count($inventories) ?></div>
            <?php
            if (count($inventories) == null) { ?>
                <div class="center color-red fs12">
                    <i class="fa fa-comment"></i>
                    <?= _not_infos ?>
                </div>
            <?php } else {
                if (count($inventories) > 20) {
                    echo paginateView($inventories, 20);
                }
            }
            ?>
        </div>
    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>