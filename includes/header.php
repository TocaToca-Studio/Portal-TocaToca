<?php 
require_once __DIR__.'/load.php'; 
/** SLICK */
 
$links_menu=[
    "Sobre nós"=>site_url('sobre-nos'),
    "Nossos projetos"=>site_url('projetos'),
    "Comunidade"=>site_url('comunidade'),
    "Novidades"=>site_url('blog'), 
];

$ul_menu=UL()->nav()->flex()->items_center();
foreach($links_menu as $text=>$url) {
    $ul_menu->add(
        LI(
            A($text)->url($url)->nav_link()->mb(0)->h4()->px(2)->decoration_none()
        )->nav_item()
    );
} 
$navbar=DIV(
    CONTAINER()->add([ 
        FLEXROW([
            A()->url(site_url("home"))->add(
                IMAGE(site_url('assets/img/logo.png'),100)
            ),
            $ul_menu->fill()->content_center(),

            A(["Apoiar ",I("heart")])
                ->url($url)
                ->class("text-danger")
                ->nav_link()->h4()->px(2), 
        ])->content_between()->w_100()->items_center(),
    ])
)->w_100()->mb(1) ;
$header=PAGE_HEADER(
    $navbar
);
