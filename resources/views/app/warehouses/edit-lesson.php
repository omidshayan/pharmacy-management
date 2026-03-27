    <!-- start sidebar -->
    <?php include_once('resources/views/layouts/header.php');
    include_once('public/alerts/error.php');
    include_once('resources/views/scripts/file-form-handler-update.php');
    ?>
    <!-- end sidebar -->
    <div id="alert" class="alert" style="display: none;"><?= _error_programmer ?></div>
    <!-- loading and overlay -->
    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Start content -->
    <div class="content">
        <div class="content-title">ویرایش درس <?= $lesson['lesson_name'] ?></div>
        <br />
        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form id="submitform" data-url="<?= url('edit-store-lesson/' . $lesson['id']) ?>" data-method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14"><?= _name ?> <?= _star ?> </div>
                            <input type="text" name="lesson_name" class="checkInput" value="<?= $lesson['lesson_name'] ?>" placeholder="نام دیپارتمنت را وارد نمایید" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14" for="name">انتخاب پکیج</div>
                            <select name="package_id" id="mySelect">
                                <option disabled>پکیج را انتخاب کنید</option>
                                <?php foreach ($packages as $package) { ?>
                                    <option value="<?= $package['id'] ?>" <?= ($package['id'] == $lesson['package_id']) ? 'selected' : '' ?>>
                                        <?= $package['package_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ویرایش" class="btn bold" />
                </form>
            </div>
            <a href="<?= url('lessons') ?>" class="color text-underline d-flex justify-center fs14">نمایش دروس</a>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>