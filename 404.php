<?php
$title = 'صفحه مورد نظر یافت نشد!';
$keyWords = '';
$description = '404 not found';
$subject = '';
$short_link = '';
$og_title = '';
$og_image = '';
$op_short_link = '';
$t_title = '';
$t_image = '';
require_once(BASE_PATH . '/resources/views/layouts/header.php') ?>
<div class="content">
	<div class="content-container center">
		<div class="stars">
			<div class="central-body">
				<img class="image-404" src="<?= asset('public/404/image/404.png') ?>" width="300px">
			</div>
			<div class="objects">
				<img class="object_rocket" src="<?= asset('public/404/image/rocket.svg') ?>" width="40px">
				<div class="earth-moon">
					<img class="object_earth" src="<?= asset('public/404/image/earth.svg') ?>" width="100px">
					<img class="object_moon" src="<?= asset('public/404/image/moon.svg') ?>" width="80px">
				</div>
				<div class="box_astronaut">
					<img class="object_astronaut" src="<?= asset('public/404/image/astronaut.svg') ?>" width="140px">
				</div>
			</div>
			<div class="glowing_stars">
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
				<div class="star"></div>
			</div>
		</div>
	</div>
</div>


</body>

</html>
<?php require_once(BASE_PATH . '/resources/views/layouts/footer.php') ?>