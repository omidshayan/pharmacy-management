<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="<?= asset('resources/views/app/prints/sale-invoice/print.css') ?>" media="screen" />
  <link rel="stylesheet" href="<?= asset('resources/views/app/prints/layouts/print.css') ?>" media="print" />
  <script src="<?= asset('public/assets/js/sweetAlert.js') ?>"></script>
  <script src="<?= asset('public/assets/js/jquery.min.js') ?>"></script>
  <link rel="icon" href="<?= asset('public/assets/img/fav.png') ?>">
  <link href="<?= asset('lib/datePicker/datePicker.min.css') ?>" rel="stylesheet" />

  <title><?= $title ?></title>
</head>
<?php
$phones = [
  $factor_infos['phone1'] ?? '',
  $factor_infos['phone2'] ?? '',
  $factor_infos['phone3'] ?? '',
  $factor_infos['phone4'] ?? '',
];

$phones_filtered = array_filter($phones, fn($phone) => !empty(trim($phone)));
?>

<!-- invoice print -->
<div class="form-container print" id="print">

  <!-- top header -->
  <div class="border p5">

    <!-- company infos -->
    <div class="top-inv d-flex align-center pr">
      <div class="top-inv-text center">
        <div class="color-print bold fs23"><?= $factor_infos['center_name'] ?></div>
        <div class="color-print fs14 bold"><?= $factor_infos['slogan'] ?></div>
        <div class="color-print fs12 bold">
          <span><?= implode(' - ', $phones_filtered); ?></span>
        </div>
      </div>
      <div class="top-inv-logo">
        <img src="<?= asset('public/assets/img/logo.png') ?>" alt="logo">
      </div>
      <div class="factor-type pa">
        <span class="factor-type-name bold border"><?= $factorType ?></span>
      </div>
    </div>
    <hr class="hr">

    <!-- invoice and customer infos -->
    <div class="d-flex justify-between pr">
      <div class="top-desc-one mt5">
        <div class="fs15 color-print">پرداخت کننده: <?= (isset($transaction['user_name']) && $transaction) ? $transaction['user_name'] : 'عمومی' ?></div>
        <div class="fs14 color-print">
          شماره موبایل:
          <?= htmlspecialchars(
            trim($transaction['phone'] ?? '') !== '' ? $transaction['phone'] : 'ثبت نشده',
            ENT_QUOTES,
            'UTF-8'
          ) ?>
        </div>
        <div class="fs14 color-print">
          آدرس:
          <?= htmlspecialchars(
            trim($transaction['address'] ?? '') !== '' ? $transaction['address'] : 'ثبت نشده',
            ENT_QUOTES,
            'UTF-8'
          ) ?>
        </div>
      </div>

      <div id="qrcode" class="qrcode-financial"></div>

      <div class="top-desc-one mt5 center">
        <div class="fs15 color-print bold">شماره بِل: <?= $this->to_farsi_number($transaction['id']) ?></div>
        <div class="fs15 color-print">تاریخ: <?= jdate('Y/m/d', $transaction['date']) ?> <span class="fs11">(<?= jdate('H:i', $transaction['date']) ?>)</span>
        </div>
        <div class="fs15 color-print">توسط: <?= $transaction['who_it'] ?></div>
      </div>
    </div>
    <!-- <hr class="hr"> -->
  </div>
  <!-- end top header -->