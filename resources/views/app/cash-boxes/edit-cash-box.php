<!-- start sidebar -->
<?php
$title = 'ویرایش صندوق: ' . $item['name'];
include_once('resources/views/layouts/header.php');
include_once('public/alerts/check-inputs.php');
include_once('public/alerts/toastr.php');
?>
<!-- end sidebar -->

<!-- Start content -->
<div class="content">
    <div class="content-title"> ویرایش صندوق: <?= $item['name'] ?>
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
            <form id="myForm" action="<?= url('edit-cash-box-store/' . $item['id']) ?>" method="POST">
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14"><?= _name ?> <?= _star ?> </div>
                        <input type="text" name="name" class="checkInput" value="<?= $item['name'] ?>" placeholder="نام صندوق را وارد نمایید" autocomplete="off" />
                    </div>
                    <div class="one">
                        <div class="label-form mb5 fs14">نوع صندوق <?= _star ?></div>
                        <?php
                        $types = [
                            'cash'        => 'صندوق داخلی',
                            'cash_center' => 'صندوق مرکزی',
                            'exchange'    => 'صرافی',
                            'bank'        => 'بانک',
                        ];
                        ?>

                        <select name="type" class="checkSelect">
                            <option value="" disabled>نوع صندوق را انتخاب نمایید</option>

                            <?php foreach ($types as $value => $label): ?>
                                <option value="<?= $value ?>" <?= ($item['type'] ?? '') === $value ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">نوع پول <?= _star ?></div>
                        <?php
                        $currencies = [
                            'af'      => 'افغانی',
                            'dollar'  => 'دالر',
                            'toman'   => 'تومان',
                            'euro'    => 'یورو',
                            'dirham'  => 'درهم',
                            'kaldar'  => 'کالدار',
                        ];
                        ?>
                        <select name="currency" class="checkSelect">
                            <option value="" disabled>نوع پول را انتخاب نمایید</option>
                            <?php foreach ($currencies as $value => $label): ?>
                                <option value="<?= $value ?>" <?= ($item['currency'] ?? '') === $value ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="one">
                        <div class="label-form mb5 fs14">اجازه موجودی منفی شود؟</div>
                        <select name="allow_negative">
                            <option value="1" <?= ($item['allow_negative'] ?? '') == 1 ? 'selected' : '' ?>>
                                بله، مجاز است
                            </option>
                            <option value="2" <?= ($item['allow_negative'] ?? '') == 2 ? 'selected' : '' ?>>
                                خیر، مجاز نیست
                            </option>
                        </select>
                    </div>
                </div>
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">موجودی اولیه</div>
                        <input
                            type="number"
                            name="opening_balance"
                            value="<?= (isset($item['opening_balance']) && floatval($item['opening_balance']) != 0) ? $item['opening_balance'] : '' ?>"
                            placeholder="موجودی اولیه را وارد نمایید"
                            autocomplete="off" />
                    </div>
                </div>
                <?= $this->branchSelectField(); ?>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="submit" id="submit" value="ویرایش" class="btn bold" />
            </form>
        </div>
        <?=$this->back_link('cash-boxes')?>
    </div>
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>