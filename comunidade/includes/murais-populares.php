<?php
require_once __DIR__ . '/../../core/config.inc.php';
 

$murais_populares=[]; 
foreach(range(0,4) as $m) {
    $murais_populares[]=LI()->nav_item()->add([
        A()->px(2)->nav_link()->decoration_none()->h5()
        ->flexrow()->items_center()->content_between()
        ->url("#")
        ->add([
            DIV([I("hashtag"),SPACE,__("Example mural")]),
            BADGE(number_shorten("500000"))->warning()
        ])
    ])->mt(1);

}

$murais_populares=CARD()->add([
    CARDHEADER(T([I("pen-square"),SPACE,__("Murais populares")])->h5()),
    UL()->nav()->flexcol()->add([
        $murais_populares
    ])->py(2)
]);