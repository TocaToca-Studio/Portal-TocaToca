<?php
require_once __DIR__.'/../core/config.inc.php';
LoginTool::deny_logged_users();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';


$email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL); 
 
$success=false;
$error=false; 
if(count($_POST)) {
  if(!Utils::validate_recaptcha(_post('g-recaptcha-response'),$RECAPTCHA_SECRETKEY)) {
    form_error(__("Por favor complete o desafio do google! utilizamos este sistema para preverir robôs e spams indesejados."));
  } else
	if($email) {    
    if(Usuario::send_reset_password_email($email)) {
        $success=__("O email foi enviado, verifique sua caixa de entrada.");
    } else {
      form_error(__("O Email de redefinição não pode ser enviado. Verifique o endereço ou tente novamente mais tarde."));
    }
	} else { 
		form_error(__("Ocorreu um erro ao fazer tentar enviar o email, verifique seus dados e tente novamente."));
	}
} 


$formulario=[
  ALERT(__("Digite seu e-mail, enviaremos um link de recuperação. &#128521;"))->info(),
  FORM()->post()->add([
    TEXTINPUT(__("E-mail"))->name("email")->from_post()->mb(1), 
    FLEXROW(
      DIV([I("user")->mx(1),A(__("Voltar à tela de login"))->url(site_url('conta/cadastro'))])
    )->content_end()->py(2),
    DIV()->class("g-recaptcha")
              ->attr('data-sitekey',$RECAPTCHA_SITEKEY), 
    BUTTON([
      I("envelope"),"&nbsp",
      __("Enviar e-mail")]
    )->submit()->primary()->mt(1)
  ])
];
if($success) $formulario=ALERT($success)->success();

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
  MODAL(__("Erros"),draw_form_errors())->renderizable(has_form_errors())->modalshow(),
  $footer
])->send();
