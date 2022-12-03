Website em construção...

<?php
exit;

require __DIR__.'/includes/header.php';
require __DIR__.'/includes/footer.php';

$div_banners=DIV()->class("w-100 banner-home");
foreach(glob(__DIR__.'/assets/img/banners/*') as $img_path) {
    $url=site_url('assets/img/banners/'.basename($img_path));
    $div_banners->add([ 
        IMAGE($url,false,400)->img_cover()
    ]);
}

$page->add([
    $header,
    PAGE_MAIN([
        $div_banners
    ]),
    $footer
])->send();