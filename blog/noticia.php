<?php
require __DIR__.'/../includes/header.php';
require __DIR__.'/../includes/footer.php';

$noticia=@$noticias[intval(_get('id'))];

$page->add([
    $header,
    PAGE_MAIN([ 
        DIV([
            CONTAINER([
                T($noticia['titulo'])->h1()
            ])->py(4),           
        ])->w_100()->bg_primary()->white(),
        CONTAINER([
            DIV($noticia['subtitulo'])->d4(),
            ROW([
                COL(
                    IMG($noticia['thumb'])->w_100()->fluid()
                )->grid(12,12,6,4),
                COL(
                    $noticia['content']
                )->grid(12,12,6,8)
            ]),
        ])->py(4)
      
    ]),
    $footer
])->send();