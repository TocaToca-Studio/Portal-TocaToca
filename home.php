<?php
require __DIR__.'/includes/header.php';
require __DIR__.'/includes/footer.php';

$div_banners=DIV()->class("w-100 banner-home");
foreach(glob(__DIR__.'/assets/img/banners/*') as $img_path) {
    $url=site_url('assets/img/banners/'.basename($img_path));
    $div_banners->add([ 
        IMAGE($url,false,400)->img_cover()
    ]);
}

$page->add([
    $header,
    PAGE_MAIN([
        DIV()->w_100()->relative()->add([
            $div_banners,
            DIV()->w_100()->h_100()->absolute(0)->flexcol()
            ->content_center()->class("align-items-start align-items-lg-center")
            ->bg_color("#00000066")
            ->add([
                DIV(__("Os jogos que você estava esperando!"))
                ->d3()->warning()->bold()
                ->style('text-shadow','3px 3px black'),
                DIV("TocaToca é uma comunidade criada para tornar realidade um sonho.")->white()->fs(2)->normal()
                ->style('text-shadow','2px 2px black'),
                FLEXROW([
                    A(__("Saiba mais"))->url(site_url('sobre-nos'))->class("btn btn-outline-light")->bold(),
                    A(__("Junte-se a nós"))->url(site_url("comunidade"))->class("junte_se")->class("btn btn-outline-warning")->bold(),
                ])->gap(10)
            ])
        ])
    ]),
    $footer
])->send();