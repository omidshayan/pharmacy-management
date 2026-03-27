<?php
$title = 'مدیریت ویژه‌گی‌های محصول';
include_once('resources/views/layouts/header.php');
include_once('public/alerts/check-inputs.php');
include_once('public/alerts/toastr.php');
?>

<!-- Start content -->
<div class="content">
    <div class="content-title"> مدیریت ویژه‌گی‌های محصول
        <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
    </div>
    <?php
    $help_title = _help_title;
    $help_content = _help_desc;
    include_once('resources/views/helps/help.php');
    ?>
    <!-- start page content -->
    <div class="mini-container">
        <div class="insert">
            <form id="myForm" action="<?= url('attribute-store') ?>" method="POST">
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">نام ویژه‌گی‌ <?= _star ?> </div>
                        <input type="text" name="att_name" class="checkInput" value="<?= old('att_name') ?>" placeholder="نام ویژه‌گی را وارد نمایید" autocomplete="off" autofocus />
                    </div>
                    <?= $this->branchSelectField(); ?>
                </div>

                <div class="border w89d m-auto pt10">
                    <div class="fs14 text-right mr35">انتخاب نوع ویژه‌گی</div>
                    <div class="inputs d-flex">
                        <label class="d-flex align-center">
                            <input type="radio" class="checkbox-select mt15 checkRadioGroup" name="att_type" value="checkbox">
                            <div class="label-form mb5 fs16">حالت انتخابی</div>
                        </label>
                        <label class="d-flex align-center mr35 mm0">
                            <input type="radio" class="checkbox-select mt15 checkRadioGroup" name="att_type" value="text">
                            <div class="label-form mb5 fs16">حالت متن کوتاه</div>
                        </label>
                    </div>
                </div>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="submit" id="submit" value="ثــبــت" class="btn bold" />
            </form>
        </div>
    </div>

    <!-- show packages -->
    <div class="mini-container">
        <div class="mb10 fs14"> ویژه‌گی‌های ثبت شده</div>
        <table class="fl-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نام ویژه‌گی</th>
                    <th>حالت ویژه‌گی</th>
                    <th>ویرایش</th>
                    <th>جزئیات</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $perPage = 10;
                $data = paginate($attributes, $perPage);
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $number = ($currentPage - 1) * $perPage + 1;
                foreach ($data as $item) {
                ?>
                    <tr>
                        <td class="color-orange <?= ($item['status'] == 1) ?: 'color-red' ?>"><?= $number ?></td>
                        <td><?= $item['att_name'] ?></td>
                        <td>
                            <span class="status">
                                <?= ($item['att_type'] == 'checkbox') ? 'حالت انتخابی' : 'حالت متن کوتاه' ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= url('edit-attribute/' . $item['id']) ?>" class="color-orange">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" class="color-orange" />
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                            </a>
                        </td>
                        <td>
                            <a href="<?= url('attribute-details/' . $item['id']) ?>">
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
            <div class="table-info fs12">تعداد کل: <?= count($attributes) ?></div>
            <?php
            if (count($attributes) == null) { ?>
                <div class="center color-red fs12">
                    <i class="fa fa-comment"></i>
                    <?= _not_infos ?>
                </div>
            <?php } else {
                if (count($attributes) > 10) {
                    echo paginateView($attributes, 10);
                }
            }
            ?>
        </div>
    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<!-- checked and unchecked -->
<script>
    document.querySelectorAll('.checkRadioGroup').forEach(radio => {
        radio.addEventListener('click', function() {

            if (this.checked && this.dataset.wasChecked === 'true') {
                this.checked = false;
                this.dataset.wasChecked = 'false';
            } else {
                document.querySelectorAll(`input[name="${this.name}"]`).forEach(r => {
                    r.dataset.wasChecked = 'false';
                });
                this.dataset.wasChecked = 'true';
            }

        });
    });
</script>

<?php include_once('resources/views/layouts/footer.php') ?>