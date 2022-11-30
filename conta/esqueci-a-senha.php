<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';

$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER(
      FLEXROW()->w_100()->center()->add([
        CARD()->shadow()->bg_white()->add([
          CARDHEADER(T(__("Recuperar conta"))->fs(1.6)),
          CARDBODY([
            ALERT(__("Digite seu e-mail, enviaremos um link de recuperação. &#128521;"))->info(),
            FORM([
              TEXTINPUT(__("E-mail"))->name("email")->mb(1), 
              FLEXROW(
                DIV([I("user")->mx(1),A(__("Voltar à tela de login"))->url(site_url('conta/cadastro.php'))])
              )->content_end()->py(2),
            
              BUTTON([
                I("envelope"),"&nbsp",
                __("Enviar e-mail")]
              )->submit()->primary()->mt(1)
            ])
          ])
        ])
      ])->style('min-height','600px')
    )
  ])->bg_image(site_url("assets/img/fundo-login.png")),
  $footer
])->send();
