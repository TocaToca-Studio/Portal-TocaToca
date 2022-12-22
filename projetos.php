<?php
require __DIR__.'/includes/header.php';
require __DIR__.'/includes/footer.php';
 
$page->add([
    $header,
    PAGE_MAIN([
        DIV([
            CONTAINER([
                T("Nossos projetos")->h1()
            ])->py(4)
        ])->w_100()->bg_primary()->white(),
    ])->py(4),
    $footer
])->send();