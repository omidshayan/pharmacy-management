<!-- start sidebar -->
<?php
$title = 'نمایش برگشت‌ها';
include_once('resources/views/layouts/header.php');
include_once('public/alerts/check-inputs.php');
include_once('public/alerts/error.php');
?>
<!-- end sidebar -->

<!-- Start content -->
<div class="content">
    <div class="content-title"> نمایش برگشت‌ها
        <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
    </div>
    <?php
    $help_title = _help_title;
    $help_content = _help_desc;
    include_once('resources/views/helps/help.php');
    $current_path = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    ?>

    <div class="search-database center m-auto w350 mb10">
        <select id="routeSelect" class="w100 p5 fs15 input mb5">
            <option disabled <?= in_array($current_path, ['returns', 'returns-sales', 'returns-buy']) ? '' : 'selected' ?>>فیلتر اطلاعات</option>
            <option value="returns-sales" <?= $current_path === 'returns-sales' ? 'selected' : '' ?>>برگشت‌ از فروش</option>
            <option value="returns-buy" <?= $current_path === 'returns-buy' ? 'selected' : '' ?>>برگشت از خرید</option>
            <option value="returns" <?= $current_path === 'returns' ? 'selected' : '' ?>>نمایش همه</option>
        </select>
    </div>
    <!-- js for filter -->
    <script>
        document.getElementById('routeSelect').addEventListener('change', function() {
            const route = this.value;
            if (route) {
                const baseUrl = "<?= CURRENT_DOMAIN ?>";
                window.location.href = baseUrl + '/' + route;
            }
        });
    </script>
    <!-- end filter internship

    <!-- show expenses -->
    <div class="content-container mt20">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>شماره بِل</th>
                    <th>نوع</th>
                    <th>مشتری</th>
                    <th>تاریخ</th>
                    <th>ویرایش</th>
                    <th>جزئیات</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $perPage = 10;
                $data = paginate($returns, $perPage);
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $number = ($currentPage - 1) * $perPage + 1;
                foreach ($data as $item) {
                    $type = $item['invoice_type'];
                    if ($type == 3) {
                        $details_link = 'return-sale-invoice-details';
                        $edit_link = 'return-sale-invoice-details';
                    } else {
                        $edit_link = 'return-buy-invoice-details';
                        $details_link = 'return-buy-invoice-details';
                    }
                ?>
                    <tr>
                        <td class="color-orange"><?= $number ?></td>
                        <td><?= $item['invoice_number'] ?></td>
                        <td>
                            <?= $item['invoice_type'] == 3 ? 'برگشت از فروش' : ($item['invoice_type'] == 4 ? 'برگشت از خرید' : '-') ?>
                        </td>
                        <td>
                            <?= !empty($item['seller_name']) ? ('<a href="#" class="color text-underline">' . $item['seller_name'] . ' </a>') : 'عمومی' ?>
                        </td>

                        <td>
                            <span class="status">
                                <?= jdate('Y/m/d', $item['date']) ?> <span class="color-orange fs11"> (<?= $this->calculateDaysText($item['date']) ?>) </span> </span>
                        </td>
                        <td>
                            <a href="<?= url($edit_link . '/' . $item['id']) ?>" class="color-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" class="color-orange" />
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                            </a>
                        </td>
                        <td>
                            <a href="<?= url($details_link . '/' . $item['id']) ?>">
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
            <div class="table-info fs14">تعداد کل: <?= count($returns) ?></div>
            <?php
            if (count($returns) == null) { ?>
                <div class="center color-red fs12">
                    <i class="fa fa-comment"></i>
                    <?= _not_infos ?>
                </div>
            <?php } else {
                if (count($returns) > 10) {
                    echo paginateView($returns, 10);
                }
            }
            ?>
        </div>
    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>