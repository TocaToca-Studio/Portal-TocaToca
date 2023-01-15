<?php
require __DIR__.'/includes/header.php';
require __DIR__.'/includes/footer.php';
 
$page->add([ 
    CONTAINER([
        ROW([
            COL([
                IMG(site_url('assets/img/logo.png'),300),
                BR,
                T("Site em construção!")->d3()
            ])->xs(12)->center()->py(5)
        ])
    ])
])->send();