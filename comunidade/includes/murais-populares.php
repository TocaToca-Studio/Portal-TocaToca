<?php
require_once __DIR__ . '/../../core/config.inc.php';
 

$dbmurais=db()->fetch_all(
    "SELECT m.id,m.nome,COUNT(b.id) AS n_bilhetes FROM mural m 
    LEFT JOIN bilhete b ON b.fk_mural=m.id
    GROUP BY m.id
    ORDER BY COUNT(b.id) DESC"
);


$murais_populares=[]; 
foreach($dbmurais as $m) {
    $murais_populares[]=LI()->nav_item()->add([
        A()->mb(0)->py(1)->px(2)
        ->nav_link()->decoration_none()->h5()
        ->flexrow()->items_center()->content_between()
        ->url(site_url('comunidade/mural?id='.$m['id']))
        ->add([
            DIV([I("hashtag"),SPACE,__($m['nome'])]),
            BADGE(number_shorten($m['n_bilhetes']))->warning()
        ])
    ])->mt(1);

}

if(!count($murais_populares)) {
    $murais_populares=ALERT(__("Ainda não existe nenhum mural no site :("))->warning();
}
$murais_populares=CARD()->add([
    CARDHEADER(T([I("pen-square"),SPACE,__("Murais populares")])->h5()),
    UL()->nav()->flexcol()->add([
        $murais_populares
    ])->py(2)
]);