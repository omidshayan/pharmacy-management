    <?php
    $title = 'ویرایش ویژه‌گی: ' . $item['att_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/error.php');
    ?>

    <div class="content">
        <div class="content-title">ویرایش ویژه‌گی: <?= $item['att_name'] ?></div>

        <div class="mini-container">
            <div class="insert">
                <form id="myForm" action="<?= url('edit-attribute-store/' . $item['id']) ?>" method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام ویژه‌گی‌ <?= _star ?> </div>
                            <input type="text" name="att_name" class="checkInput" value="<?= $item['att_name'] ?>" placeholder="نام ویژه‌گی را وارد نمایید" autocomplete="off" />
                        </div>
                        <?= $this->branchSelectField(); ?>
                    </div>

                    <div class="border w89d m-auto pt10">
                        <div class="fs14 text-right mr35">انتخاب نوع ویژه‌گی</div>
                        <div class="inputs d-flex">
                            <label class="d-flex align-center">
                                <input type="radio" class="checkbox-select mt15 checkRadioGroup" name="att_type" value="checkbox" <?= ($item['att_type'] == 'checkbox') ? 'checked' : '' ?>>
                                <div class="label-form mb5 fs16">حالت انتخابی</div>
                            </label>
                            <label class="d-flex align-center mr35 mm0">
                                <input type="radio" class="checkbox-select mt15 checkRadioGroup" name="att_type" value="text" <?= ($item['att_type'] == 'text') ? 'checked' : '' ?>>
                                <div class="label-form mb5 fs16">حالت متن کوتاه</div>
                            </label>
                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ویرایش" class="btn bold" />
                </form>
            </div>
            <?= $this->back_link('attributes') ?>
        </div>

    </div>

    <?php include_once('resources/views/layouts/footer.php') ?>