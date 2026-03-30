<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="<?= asset('public/assets/style/style.css') ?>" />
  <link rel="stylesheet" href="<?= asset('public/assets/style/main.css') ?>" />
  <script src="<?= asset('public/assets/js/sweetAlert.js') ?>"></script>
  <script src="<?= asset('public/assets/js/jquery.min.js') ?>"></script>
  <link rel="icon" href="<?= asset('public/assets/img/fav.png') ?>">
  <!-- <link href="<?= asset('lib/timePicker/persian-datepicker.min.css') ?>" rel="stylesheet" /> -->
  <link href="<?= asset('lib/datePicker/datePicker.min.css') ?>" rel="stylesheet" />

  <title><?= $title ?></title>
</head>

<body>
  <input type="text" id="menu-toggle" />
  <input type="text" id="left-menu-active" />

  <!-- start sidebar -->
  <div class="sidebar">
    <div class="sidebar-section">
      <div class="brand-name">سیستم مدیریت</div>
      <div class="avatar">
        <div class="img-avatar">
          <img src="<?= asset('public/assets/img/profile.png') ?>" alt="" />
        </div>
        <div class="info-avatar">
          <div class="text-avatar">
            <div>
              <?php
              if (isset($_SESSION['phy_employee'])) {
                echo 'مدیر کل ' . $_SESSION['phy_employee']['name'];
              } else {
                echo $_SESSION['phy_employee']['name'];
              }
              ?>
            </div>
          </div>
        </div>
      </div>
      <div class="sidebar-item">
        <ul>

          <!-- dashboard -->
          <li class="sidebar-menu">
            <a href="<?= url('/') ?>" class="d-flex align-center justify-between">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 16 16">
                  <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z" />
                </svg>
                <span class="mr5">داشبورد</span>
              </span>
            </a>
          </li>

          <!-- drugs -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M20 3h-2V1h-2v2H8V1H6v2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM4 5h16v12H4V5zm9 6h-2v2h-2v-2H7v-2h2V7h2v2h2v2z" />
                  </svg>
                  <span class="mr5">مدیریت دواها</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('add-product') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت دوا</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('products') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش دواها</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('product-categories') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت کمپانی‌ها</li>
                  </a>
                <?php endif; ?>
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('products-units') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت نوع‌های دوا</li>
                  </a>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <!-- sales -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M20 3h-2V1h-2v2H8V1H6v2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM4 5h16v12H4V5zm9 6h-2v2h-2v-2H7v-2h2V7h2v2h2v2z" />
                  </svg>
                  <span class="mr5">فروشات</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('add-sale') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت بِل فروش <span class="fs14">(F5)</span></li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('sales') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش فروشات</li>
                  </a>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>

          <!-- buy produts -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M20 3h-2V1h-2v2H8V1H6v2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM4 5h16v12H4V5zm9 6h-2v2h-2v-2H7v-2h2V7h2v2h2v2z" />
                  </svg>
                  <span class="mr5">خرید کالا</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('add-product-inventory') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت بِل خرید</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('purchase-invoices') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش بِلهای خرید</li>
                  </a>
                <?php endif; ?>
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('inventories') ?>">
                    <li class="sidebar-dropdown-menu-item">موجودی</li>
                  </a>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>

          <!-- inventory -->
          <li class="sidebar-menu">
            <a href="<?= url('inventories') ?>" class="d-flex align-center justify-between">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 16 16">
                  <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z" />
                </svg>
                <span class="mr5">موجودی</span>
              </span>
            </a>
          </li>

          <!-- deposit money -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M20 3h-2V1h-2v2H8V1H6v2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM4 5h16v12H4V5zm9 6h-2v2h-2v-2H7v-2h2V7h2v2h2v2z" />
                  </svg>
                  <span class="mr5">پرداخت / برداشت پول</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('deposit-money') ?>">
                    <li class="sidebar-dropdown-menu-item">پرداخت پول</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('financial-sector') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش تراکنش‌ها</li>
                  </a>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>

          <!-- fund -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-6 2c0-2.67 3.33-4 6-4s6 1.33 6 4v1h-12v-1zm10-9h-2V5h-4v1H8V5H6v2h12V5z" />
                  </svg>
                  <span class="mr5">موجودی دخل و صندوق</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('view-cash-boxes') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش صندوق‌ها</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('money-balance') ?>">
                    <li class="sidebar-dropdown-menu-item">موجودی</li>
                  </a>
                <?php endif; ?>

                <!-- // NOTE باید برسی کنه که آیا مرکز دارای شعبه است یا نه، اگر نبود نباید نمایش داده بشه  -->
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('center-fund') ?>">
                    <li class="sidebar-dropdown-menu-item">صندوق اصلی</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('cash-boxes') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت صندوق و حساب‌ها</li>
                  </a>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>

          <!-- employee salary -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-6 2c0-2.67 3.33-4 6-4s6 1.33 6 4v1h-12v-1zm10-9h-2V5h-4v1H8V5H6v2h12V5z" />
                  </svg>
                  <span class="mr5">معاش کارمندان</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('add-salary') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت معاش</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('salaries') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش معاشات</li>
                  </a>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <!-- saller and customer -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M20 3h-2V1h-2v2H8V1H6v2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM4 5h16v12H4V5zm9 6h-2v2h-2v-2H7v-2h2V7h2v2h2v2z" />
                  </svg>
                  <span class="mr5">مدیریت مشتری / فروشنده</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('add-user') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('users') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('account-statement') ?>">
                    <li class="sidebar-dropdown-menu-item">صورت حساب‌ها</li>
                  </a>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>

          <!-- warehouse -->
          <?php if (
            $this->hasAccess('general') &&
            isset($_SESSION['settings']['warehouse']) &&
            $_SESSION['settings']['warehouse'] == 1
          ): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M20 3h-2V1h-2v2H8V1H6v2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM4 5h16v12H4V5zm9 6h-2v2h-2v-2H7v-2h2V7h2v2h2v2z" />
                  </svg>
                  <span class="mr5">انبار</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('create-warehouse') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت انبار جدید</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="#">
                    <li class="sidebar-dropdown-menu-item">نمایش انبارها</li>
                  </a>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>

          <!-- returns -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M20 3h-2V1h-2v2H8V1H6v2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM4 5h16v12H4V5zm9 6h-2v2h-2v-2H7v-2h2V7h2v2h2v2z" />
                  </svg>
                  <span class="mr5">برگشت از (خرید / فروش)</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('return-from-sale') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت برگشت از فروش</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('return-from-buy') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت برگشت از خرید</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('returns') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش تمام برگشت‌ها</li>
                  </a>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>

          <!-- reports -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5" />
                    <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
                  </svg>
                  <span class="mr5">گزارشات</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('financial-summary') ?>">
                    <li class="sidebar-dropdown-menu-item">خلاصه گزارش مالی</li>
                  </a>
                <?php endif; ?>
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('general-settings') ?>">
                    <li class="sidebar-dropdown-menu-item">تنظیمات عمومی</li>
                  </a>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <!-- expenses -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M20 3h-2V1h-2v2H8V1H6v2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM4 5h16v12H4V5zm9 6h-2v2h-2v-2H7v-2h2V7h2v2h2v2z" />
                  </svg>
                  <span class="mr5">مصارف</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('add-expense') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت مصرفی</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('expenses') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش مصارف</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('expenses_categories') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت دسته بندی‌ها</li>
                  </a>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <!-- employees -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-6 2c0-2.67 3.33-4 6-4s6 1.33 6 4v1h-12v-1zm10-9h-2V5h-4v1H8V5H6v2h12V5z" />
                  </svg>
                  <span class="mr5">کارمندان</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('add-employee') ?>">
                    <li class="sidebar-dropdown-menu-item">ثبت کارمند جدید</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('employees') ?>">
                    <li class="sidebar-dropdown-menu-item">نمایش کارمندان</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('positions') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت وظایف کارمندان</li>
                  </a>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <!-- main settings for me -->
          <?php if (
            $this->hasAccess('settings') &&
            isset($_SESSION['settings']['warehouse']) &&
            $_SESSION['settings']['warehouse'] == 1
          ): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 24 24">
                    <path d="M20 3h-2V1h-2v2H8V1H6v2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM4 5h16v12H4V5zm9 6h-2v2h-2v-2H7v-2h2V7h2v2h2v2z" />
                  </svg>
                  <span class="mr5">تنظیمات سیستم</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('addExpenses')): ?>
                  <a href="<?= url('warehouses') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت انبارها</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('showExpenses')): ?>
                  <a href="<?= url('branches') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت شعبات</li>
                  </a>
                <?php endif; ?>

                <?php if ($this->hasAccess('showExpenses')): ?>
                  <a href="<?= url('funds') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت صندوق‌ها</li>
                  </a>
                <?php endif; ?>

              </ul>
            </li>
          <?php endif; ?>

          <!-- basic sections -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5" />
                    <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
                  </svg>
                  <span class="mr5">بخش‌های پایه</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('products-category') ?>">
                    <li class="sidebar-dropdown-menu-item">دسته‌بندی‌های دواها</li>
                  </a>
                <?php endif; ?>
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('attributes') ?>">
                    <li class="sidebar-dropdown-menu-item">ویژه‌گی‌‌های دوا</li>
                  </a>
                <?php endif; ?>
                <!-- <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('companies') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت کمپانی‌ها</li>
                  </a>
                <?php endif; ?> -->
              </ul>
            </li>
          <?php endif; ?>

          <!-- settings -->
          <?php if ($this->hasAccess('general')): ?>
            <li class="sidebar-menu ri-dashboard-line sidebar-menu-item has-dropdown">
              <a href="#" class="d-flex align-center justify-between dddd">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5" />
                    <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
                  </svg>
                  <span class="mr5">تنظیمات</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w14 sidebar-arrow" viewBox="0 0 16 16">
                  <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                </svg>
              </a>
              <ul class="sidebar-dropdown-menu">
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('manage-years') ?>">
                    <li class="sidebar-dropdown-menu-item">مدیریت سال</li>
                  </a>
                <?php endif; ?>
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('general-settings') ?>">
                    <li class="sidebar-dropdown-menu-item">تنظیمات عمومی</li>
                  </a>
                <?php endif; ?>
                <?php if ($this->hasAccess('general')): ?>
                  <a href="<?= url('factor-settings') ?>">
                    <li class="sidebar-dropdown-menu-item">تنظیمات بِل</li>
                  </a>
                <?php endif; ?>
              </ul>
            </li>
          <?php endif; ?>

          <!-- profile -->
          <li class="sidebar-menu">
            <a href="<?= url('profile') ?>" class="d-flex align-center justify-between">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 16 16">
                  <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z" />
                </svg>
                <span class="mr5">تنظیمات حساب</span>
              </span>
            </a>
          </li>

          <!-- exit -->
          <li class="sidebar-menu">
            <a href="<?= url('logout') ?>" class="d-flex align-center justify-between">
              <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w17" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                  <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                </svg>
                <span class="mr5">خروج</span>
              </span>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </div>
  <!-- end sidebar -->

  <!-- start appbar  -->
  <div class="d-flex justify-between align-center appbar">
    <div class="d-flex align-center">
      <!-- humber icon -->
      <span class="hamber cursor-p">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w20 ml-10" viewBox="0 0 16 16">
          <path d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1h-6zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1h-6zm0 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11zm10.113-5.373a6.59 6.59 0 0 0-.445-.275l.21-.352c.122.074.272.17.452.287.18.117.35.26.51.428.156.164.289.351.398.562.11.207.164.438.164.692 0 .36-.072.65-.216.873-.145.219-.385.328-.721.328-.215 0-.383-.07-.504-.211a.697.697 0 0 1-.188-.463c0-.23.07-.404.211-.521.137-.121.326-.182.569-.182h.281a1.686 1.686 0 0 0-.123-.498 1.379 1.379 0 0 0-.252-.37 1.94 1.94 0 0 0-.346-.298zm-2.168 0A6.59 6.59 0 0 0 10 6.352L10.21 6c.122.074.272.17.452.287.18.117.35.26.51.428.156.164.289.351.398.562.11.207.164.438.164.692 0 .36-.072.65-.216.873-.145.219-.385.328-.721.328-.215 0-.383-.07-.504-.211a.697.697 0 0 1-.188-.463c0-.23.07-.404.211-.521.137-.121.327-.182.569-.182h.281a1.749 1.749 0 0 0-.117-.492 1.402 1.402 0 0 0-.258-.375 1.94 1.94 0 0 0-.346-.3z"></path>
        </svg>
      </span>

      <!-- search box -->
      <div class="d-flex border appbar-search">
        <form action="">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-10 search-icon w17">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" class="p5 fs15 input" placeholder="search..." />
        </form>
      </div>
    </div>

    <div class="w60 d-flex justify-between ml-20">

      <!-- notif icon -->
      <div class="notification-container pr cursor-p">
        <!-- notif icon -->
        <?php
        $notifications = $this->loadNotifications();
        $count = count($notifications);
        ?>

        <?php if ($count > 0): ?>
          <div class="other-events pa">
            <span class="text-white fs15">
              <?= ($count > 9 ? '9' : $count) ?>
            </span>
            <?php if ($count > 9): ?>
              <span class="fs11 text-white">+</span>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="fs18 w20" viewBox="0 0 16 16">
          <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"></path>
        </svg>

        <!-- notif data -->
        <div class="notification-dropdown">
          <div class="notification-list color">
            <?php if ($count > 0): ?>
              <?php foreach ($notifications as $notification): ?>
                <?php
                $routes = [
                  1 => 'sale-invoice-details',
                  2 => 'purchase-invoice-details',
                  3 => 'return-sale-invoice-details',
                  4 => 'return-purchase-invoice-details',
                  5 => 'financial-sector-details',
                  6 => 'financial-sector-details',
                  7 => 'salary-details',
                ];

                $type = $notification['notif_type'];
                $link = isset($routes[$type])
                  ? url($routes[$type] . '/' . $notification['ref_id'])
                  : url('notification/' . $notification['id']);

                ?>
                <a href="<?= $link ?>" class="notification-item color bold">
                  <?= htmlspecialchars($notification['title']) ?>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="notification-item color center">موردی وجود ندارد</div>
            <?php endif; ?>

          </div>

          <div class="notification-footer-wrapper">
            <?php if ($count > 0): ?>
              <a href="<?= url('notifications') ?>" class="notification-footer">نــمـایـش هـمـه</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- setting icon -->
      <span class="temp-settings cursor-p">
        <svg x-description="Icon closed" x-state:on="Menu open" x-state:off="Menu closed" class="w20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"></path>
          <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"></path>
        </svg>
      </span>

    </div>
  </div>
  <!-- end appbar -->

  <!-- left sidebar -->
  <div class="setting">
    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="closeLeftMenu w17 cursor-p" viewBox="0 0 16 16" id="x-lg">
      <path d="M1.293 1.293a1 1 0 011.414 0L8 6.586l5.293-5.293a1 1 0 111.414 1.414L9.414 8l5.293 5.293a1 1 0 01-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 01-1.414-1.414L6.586 8 1.293 2.707a1 1 0 010-1.414z"></path>
    </svg>
    <div class="center">settings</div>
    <div class="mode">
      <label class="switch">
        <input type="checkbox" onclick="toggleDarkMode()" />
        <span class="slider"></span>
      </label>
      <span>تغییر تم</span>
    </div>
  </div>
  <br />
  <br />
  <br />

  <!-- show notif list dilay -->
  <script>
    const container = document.querySelector('.notification-container');
    const dropdown = container.querySelector('.notification-dropdown');
    let hideTimeout;

    container.addEventListener('mouseenter', () => {
      clearTimeout(hideTimeout);
      dropdown.style.opacity = '1';
      dropdown.style.transform = 'translateY(0)';
      dropdown.style.pointerEvents = 'auto';
    });

    container.addEventListener('mouseleave', () => {
      hideTimeout = setTimeout(() => {
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(-10px)';
        dropdown.style.pointerEvents = 'none';
      }, 200);
    });
  </script>