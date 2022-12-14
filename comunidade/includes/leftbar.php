<?php
require_once __DIR__ . '/../../core/config.inc.php';
require_once __DIR__.'/base.php';
$links_leftbar=[
    [
        "icone"=>"pen-square",
        "titulo"=>"Mural",
        "link"=>site_url('comunidade/mural'),
        "badge"=>1
    ],[
        "icone"=>"bell",
        "titulo"=>"Notificações",
        "link"=>site_url('comunidade/notificacoes'),
        "badge"=>3
    ],[
        "icone"=>"comments",
        "titulo"=>"Mensagens",
        "link"=>site_url('comunidade/mensagens'),
        "badge"=>1
    ],[
        "icone"=>"bookmark",
        "titulo"=>"Salvos",
        "link"=>site_url('comunidade/salvos'),
        "badge"=>110
    ],[
        "icone"=>"user",
        "titulo"=>"Meu perfil",
        "link"=>site_url('comunidade/perfil'),
        "badge"=>0
    ],[
        "icone"=>"cog",
        "titulo"=>"Configurações",
        "link"=>site_url('comunidade/configuracoes'),
        "badge"=>0
    ]
];

$ul_leftbar=UL()->nav()->flex()->items_stretch()->class("flex-column");
foreach($links_leftbar as $i) {
    $ul_leftbar->add(
        LI(
            A()->decoration_none()->url($url)->nav_link()->mb(0)->py(1)->px(0)
            ->flex()->items_center()->w_100()
            ->py(1)
            ->add([
                I($i['icone'])->fs(1.3),
                T(__($i['titulo']))->fs(1.3)->px(2)->fill(),
                BADGE(strval(number_shorten($i["badge"])))->info()->renderizable(intval($i['badge'])>0)
            ])
        )->nav_item()
    );
}
$leftbar=[
    CARD()->add([
        CARDHEADER([
            FLEXROW()->items_center()->add([
                IMG(site_url('assets/img/user.png'),70,70)->img_cover(),
                DIV([ 
                    A($info_usuario['nome'])->decoration_none()->url(site_url('comunidade/perfil/'.$info_usuario['nick']))->h5(),
                    DIV([
                        
                    ])->w_100()->pb(1),
                    FLEXROW([ 
                        A([I("heart"),SPACE,__(40)])->url("#")->fs(0.9)->muted()->decoration_none(),
                        A(["Sair",SPACE,I("sign-out-alt")])->url(site_url('conta/logout'))->muted()->decoration_none()
                    ])->content_between()//->items_center()
                ])->p(1)->fill()
            ])
        ])->p(2),
          CARDBODY([
            $ul_leftbar 
        ])
    ]),
];