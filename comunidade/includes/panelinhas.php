<?php
require_once __DIR__ . '/../../core/config.inc.php';
 

$_panelinhas=[]; 
foreach(range(0,4) as $m) {
    $_panelinhas[]=LI()->nav_item()->add([
        A()->px(2)->nav_link()->decoration_none()->h5()
        ->mb(0)->py(1)->px(2)
        ->flexrow()->items_center()->content_between()
        ->url("#")
        ->add([
            DIV([I("hashtag"),SPACE,__("Amiguitos")]),
            BADGE("5")->warning()
        ])
    ])->mt(1);

}

$panelinhas=CARD()->add([
    CARDHEADER(T([I("users"),SPACE,__("Panelinhas")])->h5()),
    UL()->nav()->flexcol()->add([
        $_panelinhas
    ])->py(2)
]);