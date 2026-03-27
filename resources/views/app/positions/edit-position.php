    <!-- start sidebar -->
    <?php
    $title = 'ویرایش وظیفه: ' . $position['name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/toastr.php');

    ?>

    <!-- Start content -->
    <div class="content">
        <div class="content-title">ویرایش وظیفه <?= $position['name'] ?></div>
        <br />
        <div class="box-container">
            <div class="insert">
                <form id="myForm" action="<?= url('edit-position-store/' . $position['id']) ?>" method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14"><?= _name ?> <?= _star ?> </div>
                            <input type="text" name="name" class="checkInput" value="<?= $position['name'] ?>" placeholder="نام وظیفه را وارد نمایید" autocomplete="off" />
                        </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ویرایش" class="btn bold" />
                </form>
            </div>
            <?= $this->back_link('positions') ?>
        </div>
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>