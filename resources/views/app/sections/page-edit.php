    <!-- start sidebar -->
    <?php include_once('resources/views/layouts/header.php') ?>
    <!-- end sidebar -->

    <div class="main-content app-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 class="page-title a-mt10"> ویرایش واحد: <?= $packages['name'] ?></h1>
                </div>
                <!-- PAGE-HEADER END -->
                <!-- content start -->
                <div class="box-content-container">
                    <form method="post" action="<?= url('package/edit/store/' . $packages['id']) ?>">
                        <div class="a-input d-flex justify-content-center">
                            <div class="singel-input">
                                <label class="d-block m-1">نام واحد </label>
                                <div class="input-back">
                                    <i class="fas fa-layer-group"></i>
                                    <input type="text" class="a-input-size" name="name" value="<?= $packages['name'] ?>" placeholder="نام واحد  را وارد نمایید..." required>
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="ثبت" class="a-btn" id="submit">
                        <div id="notification" class="notification"> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content end -->


    <?php
    $message = flash('success');
    if (!empty($message)) { ?>
        <div class="alert-warning">
            <span class="close-btn">&times;</span>
            <span class="empty_comment">
                <?= $message ?>
            </span>
        </div>
    <?php  } ?>
    <?php include_once('resources/views/layouts/footer.php') ?>