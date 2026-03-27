    <!-- start sidebar -->
    <?php
    $title = 'جستجوی مشتری';
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php');
    include_once('resources/views/scripts/users/live-search-link.php');
    ?>
    <!-- end sidebar -->

    <div class="content">
        <div class="content-title"> جستجوی کاربر
            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
        </div>
        <?php
        $help_title = _help_title;
        $help_content = _help_desc;
        include_once('resources/views/helps/help.php');
        ?>

        <div class="search-content scroll-not flex-justify-align">
            <div class=" flex-justify-align mb10 w100">
                <div class="border search-database flex-justify-align">
                    <a href="#" class="color">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-10 search-icon w17">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </a>
                    <input type="text" class="p5 fs15 input w100" id="search_item" autofocus placeholder="جستجوی کاربر..." autocomplete="off" />
                    <ul class="item-search-back d-none" id="backResponseSeller">
                        <li class="resSel search-item color" role="option"></li>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>