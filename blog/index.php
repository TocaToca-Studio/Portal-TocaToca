<?php
require __DIR__.'/../includes/header.php';
require __DIR__.'/../includes/footer.php';

$div_noticias=ROW();

foreach($noticias as $id=>$n) {
    $div_noticias->add(
        COL()->grid(12,6,4,3)->add([
            A()->url(site_url('blog/noticia?id='.$id))->btn()->w_100()->px(1)->py(3)
            ->add(
                CARD()->w_100()->add([
                    IMG($n['thumb'],"100%","140")->img_cover(),
                    CARDFOOTER([
                        DIV($n['titulo'])->bold(),
                        T($n['subtitulo'])->normal()
                    ]) 
                ])
            )
        ])
    );
}


$page->add([
    $header,
    PAGE_MAIN([ 
        DIV([
            CONTAINER([
                T("Novidades")->h1()
            ])->py(4),           
        ])->w_100()->bg_primary()->white(),
        CONTAINER($div_noticias)->py(4)
      
    ]),
    $footer
])->send();