<?php 
require_once __DIR__.'/../core/config.inc.php';

require_once __DIR__.'/newsletter.php';
$footer=PAGE_FOOTER([
    $newsletter,
    CONTAINER(
        ROW([
            COL([
                T("Institucional")->h4()
            ])->grid(12,false,false,3),

            COL([
                T("Projetos")->h4()
            ])->grid(12,false,false,3),

            COL([
                T("Comunidade")->h4()
            ])->grid(12,false,false,3),
            COL([
                T("Redes sociais")->h4(),
                UL([
                    LI(A(I("whatsapp",true)->h3())->url("#")),
                    LI(A(I("instagram",true)->h3())->url("#")),
                    LI(A(I("facebook",true)->h3())->url("#"))
                ])->nav()->flex()->style('gap','10px')->py(3)
            ])->grid(12,false,false,3)
            
        ])
    )->py(4)
]) ;