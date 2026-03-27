<?php
$title = 'عدم دسترسی به دوسیه';
$keyWords = '';
$description = 'Not Access This Page';
$subject = '';
$short_link = '';
$og_title = '';
$og_image = '';
$op_short_link = '';
$t_title = '';
$t_image = '';
require_once(BASE_PATH . '/resources/views/layouts/header.php') ?>

<!-- contetn start -->
<div class="content">
	<div class="box-container">
		<div class="center">
			<img src="<?= asset('public/assets/img/not-found.png') ?>" class="w200" alt="warning">
		</div>
		<div class="main-content app-content mt-0">
			<div class="side-app">
				<div class="main-container center">
					<!-- PAGE-HEADER -->
					<div class="page-header">
						<h1 class="page-title a-mt10 color-red">عدم دسترسی</h1>
					</div>
					<!-- PAGE-HEADER END -->

					<!-- main content start -->
					<div class="box-content-container mb-100 color-red mt15">
						دوسیه مورد نظر در دسترس نیست!
					</div>
					<!-- main content end -->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- content end -->
<?php require_once(BASE_PATH . '/resources/views/layouts/footer.php') ?>