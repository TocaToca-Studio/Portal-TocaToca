<?php
require __DIR__.'/includes/header.php';
require __DIR__.'/includes/footer.php';


$tablist= UL()->class("nav nav-tabs")->role("tablist");

$tabcontent=DIV()->class("tab-content")->id("myTabContent");

$i=0;
foreach($projetos as $p) {
    $i++;$active=($i==1 ? 'active' : '');
    $tablist->add([
        LI()->nav_item()->role("presentation")->add([
            A($p['nome'],'#tab-'.$i)->nav_link()->h4()
            ->attr('data-toggle',"tab")->class($active)
        ])
    ]);
    $tabcontent->add([
        DIV($p['content'])->class("tab-pane ".$active)
            ->id("tab-".$i)->role("tabpanel")
    ]);
}

$page->add([
    $header,
    PAGE_MAIN([
        DIV([
            CONTAINER([
                T("Nossos projetos")->h1()
            ])->py(4),           
        ])->w_100()->bg_primary()->white(),
        CONTAINER()->add([
            $tablist,
            $tabcontent
        ])->py(3)
    ])->pb(4),
    $footer
])->send();