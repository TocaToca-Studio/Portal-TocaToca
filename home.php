<?php
$titulo_pagina="Página Inicial";
require __DIR__.'/includes/header.php';
require __DIR__.'/includes/footer.php';

$div_banners=DIV()->class("w-100 banner-home");
foreach(glob(__DIR__.'/assets/img/banners/*') as $img_path) {
    $url=site_url('assets/img/banners/'.basename($img_path));
    $div_banners->add([ 
        IMAGE($url,false,400)->img_cover()
    ]);
}

$secao_noticias=DIV();

$botoes_jumbo=FLEXROW([
    A(__("Saiba mais"))->url(site_url('sobre-nos'))->class("btn btn-outline-light")->bold(),
    A(__("Junte-se a nós"))->url(site_url("comunidade"))->class("junte_se")->class("btn btn-outline-warning")->bold(),
])->gap(10)->class("justify-content-lg-center");

$jumbotron_media=DIV()->class("d-none d-sm-flex flex-column")->add([
    DIV(__("Os jogos que você estava esperando!"))
    ->d3()->warning()->bold()
    ->style('text-shadow','3px 3px black'),
    DIV("TocaToca é uma comunidade criada para tornar realidade um sonho.")->white()->fs(2)->normal()
    ->style('text-shadow','2px 2px black'),
])->class("align-items-start align-items-lg-center text-left text-lg-center");
$jumbotron_pequena=DIV()->class("d-block d-sm-none")->add([
    DIV(__("Os jogos que você estava esperando!"))
    ->d4()->warning()->bold()
    ->style('text-shadow','3px 3px black'),
    DIV("TocaToca é uma comunidade criada para tornar realidade um sonho.")->white()->fs(1.2)->normal()
    ->style('text-shadow','2px 2px black'), 
]);

$page->add([
    $header,
    PAGE_MAIN([
        DIV()->w_100()->relative()->add([
            $div_banners,
            DIV()->w_100()->h_100()->absolute(0)->flexcol()
            ->content_center()->class("align-items-start align-items-lg-center")
            ->bg_color("#00000066")
            ->add([
                CONTAINER([
                   $jumbotron_media,$jumbotron_pequena,
                   $botoes_jumbo
                ])
            ])
        ])
    ]),
    $footer
])->send();