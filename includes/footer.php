<?php 
require_once __DIR__.'/../core/config.inc.php';
 
$footer=PAGE_FOOTER([
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
                    LI(A(I("whatsapp",true)->h3()->white())->url("#")),
                    LI(A(I("instagram",true)->h3()->white())->url("#")),
                    LI(A(I("facebook",true)->h3()->white())->url("#"))
                ])->nav()->flex()->style('gap','10px')->py(3)
            ])->grid(12,false,false,3)
            
        ])
    )
])->bg_dark()->py(4);