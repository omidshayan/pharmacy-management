<!-- start sidebar -->
<?php
$title = 'ویرایش دسته: ' . $cat['product_cat_name'];
include_once('resources/views/layouts/header.php');
include_once('public/alerts/check-inputs.php');
?>
<!-- end sidebar -->

<!-- Start content -->
<div class="content">
    <div class="content-title"> ویرایش دسته <?= $cat['product_cat_name'] ?>
        <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
    </div>
    <?php
    $help_title = _help_title;
    $help_content = _help_desc;
    include_once('resources/views/helps/help.php');
    ?>

    <!-- start page content -->
    <div class="box-container">
        <div class="insert">
            <form id="myForm" action="<?=url('edit-product-cat-store/' . $cat['id'])?>" method="POST">
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14"><?= _name ?> <?= _star ?> </div>
                        <input type="text" name="product_cat_name" class="checkInput" value="<?= $cat['product_cat_name'] ?>" placeholder="نام را وارد نمایید" autocomplete="off" />
                    </div>
                    <?= $this->branchSelectField(); ?>
                </div>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="submit" id="submit" value="ویرایش" class="btn bold" />
            </form>
        </div>
        <div class="mt15"><?= $this->back_link('product-categories') ?></div>
    </div>
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>