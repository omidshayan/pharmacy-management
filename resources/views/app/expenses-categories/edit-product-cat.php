    <?php
    $title = 'ویرایش دسته: '  . $cat['cat_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/error.php');
    ?>

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> ویرایش دسته: <?= $cat['cat_name'] ?></div>

        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form action="<?= url('edit-expense-cat-store/' . $cat['id']) ?>" method="post">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14"><?= _name ?> <?= _star ?> </div>
                            <input type="text" name="cat_name" class="checkInput" value="<?= $cat['cat_name'] ?>" placeholder="نام دسته را وارد نمایید" />
                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ویرایش" class="btn bold" />
                </form>
            </div>
            <a href="<?= url('expenses_categories') ?>" class="color text-underline d-flex justify-center fs14">نمایش دسته بندی‌ها</a>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>