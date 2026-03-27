<?php
$title = 'صندوق مرکزی';
include_once('resources/views/layouts/header.php');
include_once('public/alerts/check-inputs.php');
include_once('public/alerts/toastr.php');
include_once('resources/views/scripts/datePicker.php');
?>

<!-- Start content -->
<div class="content">
    <div class="content-title"> صندوق مرکزی
        <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
    </div>
    <?php
    $help_title = _help_title;
    $help_content = '
        به صورت پیشفرض، مجموع دخل و موجودی صندوق، وارد شده است!
    ';
    include_once('resources/views/helps/help.php');
    ?>


    <!-- <div class="mini-container">
        <form action="<?= url('transfer-to-center-fund') ?>" method="post" class="mt20">
            <div class="center">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="submit" class="btn p5 fs15 bold" value="انتقال تمام موجودی به صندوق مرکزی">
            </div>
        </form>
    </div> -->

    <div class="mini-container">
        <div class="insert">
            <form action="<?= url('transfer-to-center-fund') ?>" method="POST">
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">مبلغ انتقال <?= _star ?></div>
                        <input type="text" name="amount" class="checkInput"
                            value="<?= rtrim(rtrim($fund['total'], '0'), '.') ?>"
                            placeholder="مبلغ انتقال به صندوق مرکزی را وارد نمایید" />
                    </div>
                </div>
                <div class="text-right mr25 color-orange" id="dariWords"> <span class="fs12 color">به حروف: </span>
                    <?= $this->number_to_dari_words($fund['total']) ?>
                </div>
                <div class="inputs d-flex d-none">
                    <div class="one">
                        <div class="label-form mb5 fs14">تاریخ پرداخت <?= _star ?></div>
                        <input type="text" class="checkInput date-view" data-jdp placeholder="تاریخ پرداخت را وارد نمایید" maxlength="40" />
                        <input type="hidden" name="date" class="date-server" />
                    </div>
                </div>
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">توضیحات</div>
                        <textarea name="description" placeholder="توضیحات را وارد نمایید"></textarea>
                    </div>
                </div>

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                <input type="submit" id="submit" value="ثبت" class="btn" />
            </form>
        </div>
    </div>

    <div class="mini-container mt15">
        <div class="accordion-title color-orange">نمایش تراکنش‌ها</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="bg-success p5 fs12"> مجموع انتقال یافته: <span class="fs15"><?= $this->formatNumber($fund['transferred']) . _afghani ?></span></div>
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>مبلغ انتقال یافته</th>
                            <th>تاریخ</th>
                            <th>ویرایش</th>
                            <th>جزئیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $perPage = 10;
                        $data = paginate($centerFundTran, $perPage);
                        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $number = ($currentPage - 1) * $perPage + 1;
                        foreach ($data as $item) {
                        ?>
                            <tr>
                                <td class="color-orange"><?= $number ?></td>
                                <td><?= $this->formatNumber($item['amount']) ?></td>
                                <td><?= jdate('Y/m/d', $item['date']) ?></td>
                                <td>
                                    <a href="<?= url('edit-employee/' . $item['id']) ?>" class="color-orange flex-justify-align">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" class="color-orange" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= url('employee-details/' . $item['id']) ?>" class="flex-justify-align">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" class="color-orange" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php
                            $number++;
                        }
                        ?>
                    </tbody>
                    <tbody></tbody>
                </table>
                <div class="flex-justify-align mt20 paginate-section mb10">
                    <div class="table-info fs12">تعداد کل: <?= count($centerFundTran) ?></div>
                    <?php
                    if (count($centerFundTran) == null) { ?>
                        <div class="center color-red fs12">
                            <i class="fa fa-comment"></i>
                            <?= _not_infos ?>
                        </div>
                    <?php } else {
                        if (count($centerFundTran) > 10) {
                            echo paginateView($centerFundTran, 10);
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>

    <!-- end page content -->
</div>
<!-- End content -->

<!-- convert to persion -->
<script>
    function toDariWords(num) {
        const ones = ["", "یک", "دو", "سه", "چهار", "پنج", "شش", "هفت", "هشت", "نه"];
        const tens = ["", "ده", "بیست", "سی", "چهل", "پنجاه", "شصت", "هفتاد", "هشتاد", "نود"];
        const teens = ["ده", "یازده", "دوازده", "سیزده", "چهارده", "پانزده", "شانزده", "هفده", "هجده", "نوزده"];
        const hundreds = ["", "صد", "دوصد", "سیصد", "چهارصد", "پنجصد", "ششصد", "هفتصد", "هشتصد", "نهصد"];

        num = parseInt(num);
        if (isNaN(num)) return "";

        if (num === 0) return "صفر";

        function threeDigit(n) {
            let h = Math.floor(n / 100);
            let t = Math.floor((n % 100) / 10);
            let o = n % 100 % 10;

            let words = [];
            if (h) words.push(hundreds[h]);
            if (t === 1) {
                words.push(teens[o]);
            } else {
                if (t) words.push(tens[t]);
                if (o) words.push(ones[o]);
            }
            return words.join(" و ");
        }

        let parts = [];
        let billions = Math.floor(num / 1_000_000_000);
        let millions = Math.floor((num % 1_000_000_000) / 1_000_000);
        let thousands = Math.floor((num % 1_000_000) / 1000);
        let units = num % 1000;

        if (billions) parts.push(threeDigit(billions) + " میلیارد");
        if (millions) parts.push(threeDigit(millions) + " میلیون");
        if (thousands) parts.push(threeDigit(thousands) + " هزار");
        if (units) parts.push(threeDigit(units));

        return parts.join(" و ");
    }

    document.addEventListener("DOMContentLoaded", () => {
        const input = document.querySelector('.checkInput');
        const wordsBox = document.getElementById('dariWords');
        const def = input.value;
        const defWords = wordsBox.innerHTML;

        // focus
        input.onfocus = () => {
            if (input.value === def) input.value = "";
        };

        // blur
        input.onblur = () => {
            if (input.value.trim() === "") {
                input.value = def;
                wordsBox.innerHTML = defWords;
            }
        };

        // live convert
        input.oninput = () => {
            const val = input.value.trim();
            wordsBox.innerHTML = val ? toDariWords(val) : "";
        };
    });
</script>

<?php include_once('resources/views/layouts/footer.php') ?>