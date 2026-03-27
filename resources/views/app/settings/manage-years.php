<!-- start sidebar -->
<?php
$title = 'مدیریت سال‌های تحصیلی';
include_once('resources/views/layouts/header.php');
include_once('public/alerts/error.php');
?>
<!-- end sidebar -->

<!-- Start content -->
<div class="content">
    <div class="content-title"> مدیریت سال‌های تحصیلی
    </div>

    <div class="mini-container">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>سال</th>
                    <th>وضعیت</th>
                    <th>تغییر وضعیت</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $perPage = 10;
                $data = paginate($years, $perPage);
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $number = ($currentPage - 1) * $perPage + 1;
                foreach ($data as $year) {
                ?>
                    <tr>
                        <td class="color-orange"><?= $number ?></td>
                        <td><?= $year['year'] ?></td>
                        <td>
                            <span class="status">
                                <?= ($year['status'] == 1) ? '<span class="color-green">باز است</span>' : '<span class="color-red">بسته است</span>' ?>
                            </span>
                        </td>
                        <td>
                            <form action="<?= url('change-status-years/' . $year['id']) ?>" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']?>">
                                <button type="submit" class="color bold cursor-p btn w80 p10 m10">
                                    <?= ($year['status'] == 1) ? '<span>بسته شود</span>' : '<span>باز شود</span>' ?>
                                </button>
                            </form>
                        </td>

                    </tr>
                <?php
                    $number++;
                }
                ?>
            </tbody>
        </table>
        <div class="flex-justify-align mt20 paginate-section">
            <div class="table-info fs12">تعداد کل: <?= count($years) ?></div>
            <?php
            if (count($years) == null) { ?>
                <div class="center color-red fs12">
                    <i class="fa fa-comment"></i>
                    <?= _not_infos ?>
                </div>
            <?php } else {
                if (count($years) > 10) {
                    echo paginateView($years, 10);
                }
            }
            ?>
        </div>
    </div>

</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>