<?php
require_once __DIR__ . '/../../core/config.inc.php';
 

$dbpanelinhas=db()->fetch_all(
    "SELECT p.id,p.nome,COUNT(u_p.fk_usuario) AS n_membros FROM panelinha p 
    LEFT JOIN usuario_to_panelinha u_p ON u_p.fk_panelinha=p.id
    GROUP BY p.id
    ORDER BY COUNT(u_p.fk_usuario) DESC"
);

$_panelinhas=[]; 
foreach($dbpanelinhas as $p) {
    $_panelinhas[]=LI()->nav_item()->add([
        A()->px(2)->nav_link()->decoration_none()->h5()
        ->mb(0)->py(1)->px(2)
        ->flexrow()->items_center()->content_between()
        ->url(site_url('comunidade/panelinha?id='.$p['id'])) 
        ->add([
            DIV([I("hashtag"),SPACE,__($p['nome'])]),
            BADGE("5")->warning()
        ])
    ])->mt(1);

}
if(!count($_panelinhas)) {
    $_panelinhas=ALERT(__("Ainda não existe nenhuma panelinha no site :("))->warning();
}

$panelinhas=CARD()->add([
    CARDHEADER(T([I("users"),SPACE,__("Panelinhas")])->h5()),
    UL()->nav()->flexcol()->add([
        $_panelinhas
    ])->py(2)
]);