    <?php
    $title = 'ویرایش مصرفی: ' . $expense['title_expenses'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/error.php'); ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> ویرایش مصرفی: <?=$expense['title_expenses']?>
        </div>
        <br />
        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form action="<?= url('edit-expense-store/' . $expense['id']) ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">عنوان مصرفی <?= _star ?> </div>
                            <input type="text" class="checkInput" name="title_expenses" value="<?=$expense['title_expenses']?>" placeholder="نام و تخلص را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب دسته بندی</div>
                        <select name="category">
                            <option selected disabled>لطفا دسته بندی را انتخاب نمایید</option>
                            <?php
                            foreach ($expenses_categories as $expenses_category) {
                                $selected = ($expenses_category['cat_name'] == $expense['category']) ? 'selected' : '';?>
                                <option value="<?= $expenses_category['cat_name'] ?>" <?= $selected ?>><?= $expenses_category['cat_name'] ?></option>
                            <?php }
                            ?>
                        </select>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">مبلغ هزینه</div>
                            <input type="number" name="price" value="<?=$expense['price']?>" placeholder="مبلغ را وارد نمایید" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">پرداختی</div>
                            <input type="number" name="payment_expense" value="<?=$expense['payment_expense']?>" placeholder="مبلغ پرداختی را وارد نمایید" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب کارمند</div>
                        <select name="by_whom">
                            <option selected disabled>لطفا کارمند را انتخاب نمایید</option>
                            <?php
                            foreach ($by_whom_employees as $by_whom_employee) {
                                $selected = ($by_whom_employee['employee_name'] == $expense['by_whom']) ? 'selected' : '';
                            ?>
                                <option value="<?= $by_whom_employee['employee_name'] ?>" <?= $selected ?>><?= $by_whom_employee['employee_name'] ?></option>
                            <?php }
                            ?>
                        </select>

                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"><?=$expense['description']?></textarea>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب عکس</div>
                            <input type="file" id="image" name="image_expense" accept="image/*">
                        </div>
                    </div>
                    <div id="imagePreview">
                        <img src="" class="img" alt="">
                    </div>
                    <?php if (!empty($expense['image_expense'])): ?>
                        <div class="center mr0">
                            <img src="<?= asset('../application/public/images/expenses_images/' . $expense['image_expense']) ?>" class="img" alt="afghan faizi">
                        </div>
                        <div class="fs11 center mr0 mb20">تصویر فعلی</div>
                    <?php endif; ?>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
            <a href="<?= url('expenses') ?>" class="color text-underline d-flex justify-center fs14">برگشت</a>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>