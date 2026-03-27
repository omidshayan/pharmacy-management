    <!-- start sidebar -->
    <?php
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/error.php');
    ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title">مدیریت صفحات و بخش ها
            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
        </div>
        <?php
        $help_title = 'راهنمای بخش ثبت صفحه جدید';
        $help_content = 'این قسمت مربوط به برنامه نویس است و شما نباید به این قسمت دسترسی داشته باشید، اگر به هر دلیلی وارد این قسمت شدید لطفا تغییراتی ایجاد نکنید و با برنامه نویس تماس بگرید، متشکرم';
        include_once('resources/views/helps/help.php');
        ?>
        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form id="myForm" action="section/store" method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14"><?= _name ?> <?= _star ?> </div>
                            <input type="text" name="name" class="checkInput" placeholder="نام را وارد نمایید" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14" for="name">انتخاب صفحه</div>
                            <select name="section_id">
                                <option selected disabled>آیا زیر شاخه یک صفحه است؟</option>
                                <?php foreach ($sections as $section) { ?>
                                    <option value="<?= $section['id'] ?>"><?= $section['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام انگلیسی <?= _star ?> </div>
                            <input type="text" name="en_name" class="checkInput" placeholder="نام انگلیسی را وارد نمایید" />
                        </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ثــبــت" class="btn bold" />
                </form>
            </div>
        </div>

        <!-- show packages -->
        <div class="box-container">
            <div class="mb10 fs14">پکیج های ثبت شده</div>
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام بخش</th>
                        <th>نام انگلیسی</th>
                        <th>دسته</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $number = 1;
                    foreach ($sections as $section) { ?>
                        <tr>
                            <td class="color-orange"><?= $number ?></td>
                            <td><?= $section['name'] ?></td>
                            <td><?= $section['en_name'] ?></td>
                            <td><?= isset($section['section_id']) ? 'زیر دسته' : 'دسته اصلی' ?></td>

                        </tr>
                    <?php $number++;
                    }
                    ?>
                </tbody>
                <tbody></tbody>
            </table>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>

    <?php include_once('resources/views/layouts/footer.php') ?>