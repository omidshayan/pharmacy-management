    <!-- start sidebar -->
    <?php
    $title = 'ثبت کارمند';
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php'); ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title">ثبت کارمند جدید</div>

        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form action="<?= url('employee-store') ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام و تخلص <?= _star ?> </div>
                            <input type="text" class="checkInput" name="employee_name" placeholder="نام و تخلص را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">نام پدر</div>
                            <input type="text" name="father_name" placeholder="نام پدر را وارد نمایید" maxlength="40"/>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره <?= _star ?> </div>
                            <input type="number" class="checkInput" name="phone" placeholder="شماره را وارد نمایید" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">رمزعبور <?= _star ?></div>
                            <input type="password" class="checkInput" name="password" value="" placeholder="رمزعبور را وارد نمایید" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">مقدار معاش <?= _star ?> </div>
                            <input type="number" class="checkInput" name="salary_price" value="" placeholder="مقدار معاش را وارد نمایید" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14" for="name">وظیفه</div>
                            <select name="position" id="mySelect" class="checkSelect">
                                <option selected disabled>انتخاب وظیفه</option>
                                <?php
                                foreach ($positions as $position) { ?>
                                    <option value="<?= $position['name'] ?>"><?= $position['name'] ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <?= $this->branchSelectField(); ?>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">آدرس</div>
                            <textarea name="address" placeholder="آدرس را وارد نمایید"></textarea>
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"></textarea>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب عکس</div>
                            <input type="file" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                    <div id="imagePreview">
                        <img src="" class="img" alt="">
                    </div>

                    <!-- <div class="accordion-title color-orange">تعیین سطح دسترسی</div>
                    <div class="accordion-content">
                        <div class="child-accordioin">
                            <div class="one">
                                <div class="accordion-select permision">
                                    <?php foreach ($sections as $section) : ?>
                                        <?php $mainId = 'accordion-main-' . $section['id']; ?>
                                        <div class="accordion-item-select">
                                            <input type="checkbox" name="section_name[]" value="<?= $section['en_name'] ?>" id="<?= $mainId ?>" class="main-checkbox-select">
                                            <label for="accordion-button-<?= $mainId ?>" class="accordion-button-select bb"><?= $section['name'] ?></label>
                                            <div class="accordion-content-select">
                                                <?php foreach ($subSections as $subSection) : ?>
                                                    <?php if ($section['id'] == $subSection['section_id']) : ?>
                                                        <?php $subId = 'accordion-sub-' . $subSection['id']; ?>
                                                        <div class="hover">
                                                            <label class="checkbox-container">
                                                                <input type="checkbox" name="section_name[]" class="inner-checkbox-select" value="<?= $subSection['en_name'] ?>" data-parent="<?= $mainId ?>">
                                                                <span class="checkmark"></span> <?= $subSection["name"] ?>
                                                            </label>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>