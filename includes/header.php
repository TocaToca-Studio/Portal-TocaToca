<?php 
require_once __DIR__.'/load.php';

 /** SLICK */
 
$links_menu=[
    "Sobre nós"=>"#",
    "Nossos projetos"=>"#",
    "Comunidade"=>"#",
    "Novidades"=>"#",
    "Contribuir"=>"#"
];

$ul_menu=UL()->nav()->flex()->items_center();
foreach($links_menu as $text=>$url) {
    $ul_menu->add(
        LI(
            A($text)->url($url)->nav_link()->h4()->px(2)
        )->nav_item()
    );
}
 
 
$navbar=DIV(
    CONTAINER()->add([ 
        FLEXROW([
            A()->url(site_url())->add(
                IMAGE(site_url('assets/img/logo.png'),100)
            ),
            $ul_menu->fill()->content_center(),
            
            A(["Fazer uma doação ",I("heart")])
            ->url($url)
            ->class("text-danger")
            ->nav_link()->h4()->px(2),

        ])->content_between()->w_100()->items_center(),
    ])
);
$header=PAGE_HEADER(
    $navbar
);
