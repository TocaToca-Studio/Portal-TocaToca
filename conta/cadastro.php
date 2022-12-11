<?php
require_once __DIR__.'/../core/config.inc.php';
LoginTool::deny_logged_users();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';
if(_post('senha')!=_post('confirmacao_senha')) {
  form_error(__("As senhas inseridas não coincidem!"));
}

if(_post('nick') && !has_form_errors()) { 
  /* TODO: implementar todos os possiveis caso de dados invalidos 
   e fazer a verificação aqui no servidor */
   
  $nick=db()->escape(_post('nick'));
  $nick_already_exists=db()->fetch_value("SELECT COUNT(*)>0 FROM usuario WHERE nick='$nick'");
  if($nick_already_exists) {
    form_error(__("Seu nickname já foi escolhido, por favor digite outro!"));
  }

  $email=db()->escape(_post('email'));
  $email_already_exists=db()->fetch_value("SELECT COUNT(*)>0 FROM usuario WHERE email='$nick'");
  if($email_already_exists) {
    form_error([
      __("O email digitado já foi cadastrado, por favor digite outro ou"),
      A(__("recupere sua senha"))
    ]);
  }
  //die(json_encode(form_errors()));
  if(!has_form_errors()) {
    $novo_id=Usuario::try_register($email,_post('senha'));
    if($novo_id) {
      (new Usuario($novo_id))->update_infos([
        "nick"=>_post('nick'),
        "nome"=>_post('nome'),
        "clube"=>_post('clube'),
        "cidade"=>_post('cidade'),
      ]); 
      Utils::redirect(site_url("comunidade"));
    } else {
      form_error("Ocorreu um erro ao realizar seu cadastro, por favor contate-nos!");
    }
  } 

}



$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER(
      FLEXROW()->w_100()->center()->add([
        CARD()->shadow()->bg_white()->add([
          CARDHEADER(T(__("Insira seus dados de cadastro:"))->fs(1.6)),
          CARDBODY([
            FORM()->action((Utils::self_url()))->post()
            ->add([
              INPUTGROUP(
                T('@'),
                TEXTINPUT(__("Nick de usuário")) 
                ->name("nick")->from_post()
                ->minlength(4)->required()
              ),
              TEXTINPUT(__("Email"))->email()
                ->name("email")->from_post()
                ->minlength(4)->required(),

              TEXTINPUT(__("Seu nome"))
                ->name("nome")->from_post()
                ->minlength(3)->required(),

              TEXTINPUT(__("Nome do seu clube")) 
              ->name("clube")->from_post(),

              TEXTINPUT(__("Sua cidade")) 
              ->name("cidade")->from_post(),

              TEXTINPUT(__("Senha"))->name("senha")->from_post()
              ->password()->minlength(6)->required(),

              TEXTINPUT(__("Confirmação de senha")) 
                ->name("confirmacao_senha")->from_post()
                ->password()->minlength(6)->required(), 
              BUTTON(__("Finalizar cadastro!"))->submit()->primary()->mt(1)
            ])->flexcol()->gap(4)
          ])
        ])
      ])->style('min-height','600px')
    )
  ])->bg_image(site_url("assets/img/fundo-login.png")),
  MODAL(__("Erros"),draw_form_errors())->renderizable(has_form_errors())->modalshow(),
  $footer
])->send();
