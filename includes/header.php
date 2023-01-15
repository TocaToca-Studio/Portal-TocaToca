<?php 
require_once __DIR__.'/load.php'; 
/** SLICK */
 
$links_menu=[
    "Home"=>site_url('home'),
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
$navbar=DIV()->hidden_mobile()->w_100()->mb(1)->add(
    CONTAINER()->add([ 
        FLEXROW([
            A()->url(site_url("home"))->add(
                IMAGE(site_url('assets/img/logo.png'),100)
            ),
            $ul_menu->fill()->content_center(), 
            A(["Apoiar ",I("heart")])->text_center()
                ->url(site_url('apoie'))
                ->class("text-danger")
                ->nav_link()->h4()->px(2), 
        ])->content_between()->w_100()->items_center()
    ])
);
/** MOBILE */


$navbar_mobile=CONTAINER([
    ROW([
        COL([
            BUTTON(I("bars"))->class("btn btn-outline-dark")->onclick("open_sidemenu()")
        ])->grid(4),
        COL([
            A()->url(site_url("home"))->add(
                IMAGE(site_url('assets/img/logo.png'),70)
            )
        ])->grid(4)->center(),
        COL([
            A([
                SPAN("Apoiar ")->class("d-none d-sm-inline")
                ,I("heart")])
                    ->url(site_url('apoie'))
                    ->class("text-danger")
                    ->nav_link()->px(2)
        ])->grid(4)->text_right()
    ])->items_center()
])->hidden_desktop();


$ul_menu_mobile=UL()->nav()->flex()->items_center();
foreach($links_menu as $text=>$url) {
    $ul_menu_mobile->add(
        LI(
            A($text)->url($url)->nav_link()->mb(0)->h6()->px(2)->decoration_none()
        )->nav_item()
    );
} 

$sidebar_menu=
    DIV()->class("sidemenu-overlay p-0")->add([
        CARD()->style('width','200px')->h_100()->class("sidemenu")
        ->add([
            CARDHEADER([ 
                FLEXROW()
                ->content_between()->items_center()
                ->style('cursor','pointer')->onclick("close_sidemenu()")

                ->add([
                    DIV([I("bars")->lg()." Menu"])->pr(2),
                    DIV(I("times"))
                ])
            ]) ,
            CARDBODY([
            $ul_menu_mobile->w_100()->flexcol()->items_stretch()
            ])
        ])
    ]);




$header=PAGE_HEADER([
    $navbar,
    $navbar_mobile,
    $sidebar_menu
]);
