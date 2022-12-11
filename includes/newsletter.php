<?php 
require_once __DIR__.'/../core/config.inc.php';


$newsletter=
DIV()->w_100()->py(4)->bg_primary()->white()->add([
    CONTAINER([
        ROW([
            COL([
                FLEXROW([
                    DIV(I("envelope")->scale(4)),
                    DIV(__("Cadastre seu e-mail e fique por dentro das últimas novidades!"))->fill()->px(2)->h4()
                ])->items_center()
            ])->xs(12)->lg(6),
            COL([
                FORM()->id("form-newsletter")->add([
                    INPUTGROUP(false,
                        TEXTINPUT("Endereço de email")->email()->name('email')->minlength(4)->required(),
                        BUTTON("Inscrever-se")->submit()->warning()->class("text-primary")
                    )
                ])
            ])->xs(12)->lg(6),
        ])->items_center()
    ])
]);

$modais[]=MODAL("Canal de notícias TocaToca",DIV()->id("nl-response"))->id("modal-nl");
