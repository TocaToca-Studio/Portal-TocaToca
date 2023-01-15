<?php 
require_once __DIR__.'/../core/config.inc.php'; 
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';
 
$token=_get('token');  
$success=false;
$invalid_token=false;  
 
if($token) { 
    /**
     * o retorno da função validate_token é o id do usuario se existir
     * pode ser usado como um valor booleano porque qualquer valor acima de zero é true em PHP
     */
    $user_id=JWT::validate_token($token,LoginTool::SECRET_KEY);
    if($user_id) {
        LoginTool::login($user_id);
        Utils::redirect(Utils::self_url());
    }
    if(!$user_id) { 
        form_error(__("Token de recuperação inválido!!"));
        $invalid_token=true;
    }
} else {
    LoginTool::deny_unlogged_users(site_url('conta/login'));
}

$pass=@trim(_post('senha')); 
$pass_cert=@trim(_post('confirmacao_senha'));
if($pass) {
  if(!Utils::validate_recaptcha(_post('g-recaptcha-response'),$RECAPTCHA_SECRETKEY)) {
    form_error(__("Por favor complete o desafio do google! utilizamos este sistema para preverir robôs e spams indesejados."));
  } 
  if(!has_form_errors() && strlen($pass)<6) {
    form_error("Sua senha é pequena demais.");
  }
  if(!has_form_errors() && $pass!=$pass_cert) { 
    form_error(__("As senhas inseridas não coincidem!"));
  }
  if(!has_form_errors() && !filter_has_var(INPUT_POST,'token')) { 
      if(Usuario::logged_user()->update_password($pass)) {
          $success=__("Sua senha foi redefinida!");
      } else {
        form_error(__("Ocorreu um erro interno ao redefinir sua senha, por favor contate-nos"));
      }
  }

}
$formulario=[
  ALERT(__("Digite uma nova senha para sua conta!. &#128521;"))->info(),
  FORM()->post()->add([
    TEXTINPUT(__("Senha"))->name("senha")->from_post()
    ->password()->minlength(6)->required(),

    TEXTINPUT(__("Confirmação de senha")) 
      ->name("confirmacao_senha")->from_post()
      ->password()->minlength(6)->required(), 
      DIV()->class("g-recaptcha")
              ->attr('data-sitekey',$RECAPTCHA_SITEKEY),
    BUTTON([
      I("envelope"),"&nbsp",
      __("Definir nova senha")] 
    )->submit()->primary()->mt(1)
  ])
];

if($success) $formulario=ALERT($success)->success();
elseif($invalid_token) {
  $formulario=draw_form_errors();
}

$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER(
      FLEXROW()->w_100()->center()->add([
        CARD()->shadow()->bg_white()->add([
          CARDHEADER(T(__("Recuperar conta"))->fs(1.6)),
          CARDBODY([$formulario])
        ])
      ])->style('min-height','600px')
    )
  ])->bg_image(site_url("assets/img/fundo-login.png")), 
  MODAL(__("Erros"),draw_form_errors())->renderizable(!$invalid_token && draw_form_errors())->modalshow(),
  $footer
])->send();
