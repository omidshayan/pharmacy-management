    <?php
    $title = 'ویرایش واحد: ' . $item['product_unit'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/error.php');
    ?>

    <!-- Start content -->
    <div class="content">
        <div class="content-title">ویرایش واحد <?= $item['product_unit'] ?></div>

        <div class="box-container">
            <div class="insert">
                <form id="myForm" action="<?=url('edit-product-unit-store/' . $item['id'])?>" method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14"><?= _name ?> <?= _star ?> </div>
                            <input type="text" name="product_unit" class="checkInput" value="<?= $item['product_unit'] ?>" placeholder="واحد شمارش را وارد نمایید" autocomplete="off" />
                        </div>
                        <?= $this->branchSelectField(); ?>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ویرایش" class="btn bold" />
                </form>
            </div>
            <?= $this->back_link('products-units') ?>
        </div>

    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>