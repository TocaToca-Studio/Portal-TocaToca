<?php
require_once __DIR__.'/../core/config.inc.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';
 
$email=		filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
$password=  _post('senha'); 
 
$redir=""; 

if(count($_POST)) {   
  //	$is_token_valid=;
  if(!Utils::validate_recaptcha(_post('g-recaptcha-response'),$RECAPTCHA_SECRETKEY)) {
    form_error(__("Por favor complete o desafio do google! utilizamos este sistema para preverir robôs e spams indesejados."));
  } else if($email && $password) { 
		//$is_token_valid=true;
		$id=Usuario::try_login($email,$password);
		
		if($id) {
      $redir=_get('redir');
			if(!$redir) $redir=_get('redirect'); 
			if($redir=base64_decode($redir)) {
        Utils::redirect($redir); exit;
			}   
			Utils::redirect(site_url("comunidade"));  
		} else {
			form_error(__("Não foi possível completar a autenticação, verifique seus dados e tente novamente."));
		}
	} else { 
		form_error(__("Ocorreu um erro interno do sistema, por favor aguarde para que o site seja corrigido e contate-nos!"));
	}
}


LoginTool::deny_logged_users(site_url('comunidade'));

$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER([
      FLEXROW()->w_100()->center()->add([
        CARD()->shadow()->bg_white()->add([
          CARDHEADER([
            T(__("Entre ou")),SPACE,
            A(__("cadastre-se"))->url(site_url('conta/cadastro'))
          ])->fs(1.6),
          CARDBODY([
            FORM()->id("formulario-login")->class("blockcaptcha")->post()->add([
              TEXTINPUT(__("E-mail ou Nick"))->name("email")->from_post()->mb(1)->minlength(4)->required(),
              TEXTINPUT(__("Senha"))->name("senha")->from_post()->password()->minlength(6),
              FLEXROW(
                DIV([I("lock")->mx(1),A(__("Esqueci minha senha"))->url(site_url('conta/esqueci-a-senha'))])
              )->content_end()->py(2),
              DIV()->class("g-recaptcha")
              ->attr('data-sitekey',$RECAPTCHA_SITEKEY),
              BUTTON([I("sign-in"),__("Entrar")])->submit()->primary()->mt(1)
            ])
          ])          
        ])
      ])->style('min-height','600px'), 
      
    ])
  ])->bg_image(site_url("assets/img/fundo-login.png")), 
  MODAL(__("Erros"),draw_form_errors())->renderizable(has_form_errors())->modalshow(),
  $footer
])->send();
