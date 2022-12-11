<?php
require __DIR__.'/../includes/header.php';
require __DIR__.'/../includes/footer.php';
 
$page->add([
    $header,
    PAGE_MAIN([
      
    ]),
    $footer
])->send();