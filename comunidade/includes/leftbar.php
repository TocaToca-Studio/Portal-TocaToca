<?php
require_once __DIR__ . '/../../core/config.inc.php';


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

$ul_leftbar=UL()->py(3)->nav()->flex()->items_stretch()->class("flex-column");
foreach($links_leftbar as $i) {
    $ul_leftbar->add(
        LI(
            A()->decoration_none()->url($url)->nav_link()->flex()->items_center()->w_100()
            ->py(1)
            ->add([
                I($i['icone'])->fs(1.6),
                T(__($i['titulo']))->fs(1.6)->px(2)->fill(),
                BADGE(strval($i["badge"]))->info()->renderizable(intval($i['badge'])>0)
            ])
        )->nav_item()
    );
}
$leftbar=$ul_leftbar;