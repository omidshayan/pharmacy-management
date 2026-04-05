    <?php
    $title = 'ثبت مشتری / فروشنده';
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php'); ?>

    <div class="content">
        <div class="content-title">ثبت مشتری / فروشنده جدید</div>
        <div class="box-container">
            <div class="insert">
                <form action="<?= url('user-store') ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام و تخلص <?= _star ?> </div>
                            <input type="text" class="checkInput" name="user_name" placeholder="نام و تخلص را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره <span class="fs12 color-orange">(تلگرام)</span> <?= _star ?> </div>
                            <input type="number" class="checkInput" name="phone" placeholder="شماره را وارد نمایید" />
                        </div>
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

                    <div class="accordion-title color-orange mb10">جزئیات مالی</div>
                    <div class="accordion-content">
                        <div class="child-accordioin">

                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">باقیداری گذشته </div>
                                    <input type="text" id="remnants_past" name="remnants_past" placeholder="باقیداری قبلی مشتری را وارد نمایید" maxlength="40" />
                                </div>
                                <div class="one">
                                    <div class="label-form mb5 fs14">طلب گذشته </div>
                                    <input type="text" id="credit_past" name="credit_past" placeholder="طلب قبلی مشتری را وارد نمایید" maxlength="40" />
                                </div>
                            </div>

                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">توضیحات مربوط به باقیداری</div>
                                    <textarea name="note" placeholder="توضیحات مربوط به باقیداری را وارد نمایید"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="inputs d-flex d-none">
                        <div class="one ">
                            <label class="d-flex justify-center">
                                <input type="checkbox" class="checkbox-select" name="is_customer" checked>
                                <div class="label-form mb5 fs22" for="name">مشتری</div>
                            </label>
                        </div>
                        <div class="one ">
                            <label class="d-flex justify-center">
                                <input type="checkbox" class="checkbox-select" name="is_seller" checked>
                                <div class="label-form mb5 fs22" for="name">فروشنده</div>
                            </label>
                        </div>
                    </div>

                    <?= $this->branchSelectField(); ?>

                    <div class="accordion-title color-orange">جزئیات بیشتر</div>
                    <div class="accordion-content">
                        <div class="child-accordioin">
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">نام معرف </div>
                                    <input type="text" name="reagent" placeholder="نام معرف را وارد نمایید" />
                                </div>
                                <div class="one">
                                    <div class="label-form mb5 fs14">شماره معرف <?= _star ?> </div>
                                    <input type="number" name="reagent_phone" placeholder="شماره معرف را وارد نمایید" />
                                </div>
                            </div>

                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">انتخاب عکس</div>
                                    <input type="file" id="image" name="user_image" accept="image/jpeg, image/png, image/gif">
                                </div>
                            </div>
                            <div id="imagePreview" class="mb100">
                                <img src="" class="img" alt="">
                            </div>

                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
        </div>
    </div>

    <!-- check for account -->
    <div id="tooltip" style="position:absolute; display:none; background:#111; color:#fff; padding:6px 10px; border-radius:4px; font-size:13px; pointer-events:none; z-index:1000;">
        نمی‌توانید همزمان قرضداری و طلب وارد نمایید
    </div>
    <script>
        const remnants = document.getElementById('remnants_past');
        const credit = document.getElementById('credit_past');
        const tip = document.getElementById('tooltip');

        function updateDisabled() {
            credit.disabled = remnants.value.trim() !== '';
            remnants.disabled = credit.value.trim() !== '';
        }

        [remnants, credit].forEach(input => {
            input.addEventListener('input', updateDisabled);
            input.addEventListener('mousemove', e => {
                if (input.disabled) {
                    tip.style.display = 'block';
                    tip.style.left = e.pageX + 10 + 'px';
                    tip.style.top = e.pageY + 10 + 'px';
                } else tip.style.display = 'none';
            });
            input.addEventListener('mouseout', () => tip.style.display = 'none');
        });
    </script>

    <?php include_once('resources/views/layouts/footer.php') ?>