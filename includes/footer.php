<?php 
require_once __DIR__.'/../core/config.inc.php';

require_once __DIR__.'/newsletter.php';

$alerta_cookies=
ALERT()->id("alerta-cookies")
->bg_dark()->white()->p(3)->mb(0)
->style([
    'position'=>'fixed',
    'bottom'=>'0px',
    'left'=>'0px',
    'width'=>'100%',
    'z-index'=>'1000'
])
->add([
    CONTAINER(
        ROW([
            COL([
                T("Ao utilizar nosso site você concorda com o uso de cookies no seu dispositivo para melhorar a navegação no site, e ajudar nas nossas iniciativas de marketing.")
            ])->grid(12,12,8,10),
            COL(
                BUTTON("Aceitar cookies")->id("botao-cookies")->warning()
            )->grid(12,12,4,2)
        ])->items_center()->content_between()
    )
])->renderizable(!isset($_COOKIE['aceitou_cookies']));

$footer=[
    $alerta_cookies,
    PAGE_FOOTER([
    $newsletter,
    CONTAINER(
        ROW([
            COL([
                T("Institucional")->h4()
            ])->grid(12,false,false,3), 
            COL([
                T("Projetos")->h4(),
                
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
    )->py(4)->renderizable(false),
    DIV()->w_100()->add([
        CONTAINER([
            FLEXROW()->w_100()->content_between()->items_center()->py(3)
            //->style("border-top",'1px solid black')
            ->add([
                DIV("TocaToca Studio - Conteúdo livre de direitos autorais"),
                DIV(
                    A()->url(site_url("home"))
                    ->add(
                        IMAGE(site_url('assets/img/logo.png'),80)
                    )
                )
            ])
        ])
    ]) 
]),
$modais
];