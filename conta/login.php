<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';

$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER(
      FLEXROW()->w_100()->center()->add([
        CARD()->shadow()->bg_white()->add([
          CARDHEADER(T(__("Entre ou cadastre-se"))->fs(1.6)),
          CARDBODY([
            FORM([
              TEXTINPUT(__("E-mail"))->name("email")->mb(1),
              TEXTINPUT(__("Senha"))->name("senha")->password(),
              FLEXROW(
                DIV([I("lock")->mx(1),A(__("Esqueci minha senha"))->url(site_url('conta/esqueci-a-senha.php'))])
              )->content_end()->py(2),
              BUTTON(__("Entrar"))->submit()->primary()->mt(1)
            ])
          ])
        ])
      ])->style('min-height','600px')
    )
  ])->bg_image(site_url("assets/img/fundo-login.png")),
  $footer
])->send();
