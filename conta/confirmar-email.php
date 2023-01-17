<?php 
require_once __DIR__.'/../core/config.inc.php'; 
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';
 
 
$token=_get('token');    

$confirmou_email=false; 


$nick=@trim(_get('nick'));
if($nick) {

  $user_id=Usuario::get_userid_by_nick($nick);
  if(!verifica_assinatura($nick,_get('assinatura'))) {
      Utils::redirect(site_url('conta/login'));
  } 

  if(!Usuario::send_mail_confirmation($nick)) {
    form_error(__("Estamos com problema para enviar seu e-mail de confirmação, por favor contate-nos!"));
  }
}elseif($token) {  
    /**
     * o retorno da função validate_token é o id do usuario se existir
     * pode ser usado como um valor booleano porque qualquer valor acima de zero é true em PHP
     */
    $user_id=JWT::validate_token($token,LoginTool::SECRET_KEY);
    if($user_id) {
      $user=(new Usuario($user_id));
      $user->update_info('confirmou_email','1');
      $confirmou_email=true;
      Usuario::force_login($user->get_info('email'));
    } else {
      form_error(__("Token inválido ou expirado!!"));
    }
}
 
if(has_form_errors()) {
  $titulo=__("Ops, isso não era para acontecer :(");
  $mensagem=draw_form_errors();
} else {
  if($confirmou_email) {
      $nome_usuario=(new Usuario($user_id))->get_info('nome');
      $titulo=I("heart").SPACE.__("Obrigado, de coração!");
      $mensagem=("Oi, ".$nome_usuario." .<br>

      Agradecemos por se inscrever em nossa comunidade!<br> 
      Nós não fazemos spam e respeitamos o direito de todo mundo à privacidade online.
      <br> Esperamos que se divirta enquanto acompanha todas as novidades.<br>"
      .A("ACESSAR COMUNIDADE")->btn()->primary()->href(site_url("comunidade")));
      $mensagem=ALERT($mensagem)->success();
    } else {
      $titulo=__("Confirme seu email");
      $mensagem=__("OK. Sabemos que isso é um pouco chato,  mas precisamos que você confirme que este email é realmente seu.
                   <br> Não queremos nenhum engraçadinho usando seu nome! 
                   Então por gentileza, acesse sua caixa de entrada e verifique a mensagem que enviamos para ti.");
                   $mensagem=ALERT($mensagem)->info();
                   $mensagem.=A("Não posso confirmar agora")->small()->url(site_url("conta/logout"));
    }
    
}


$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER(
      FLEXROW()->w_100()->center()->add([
        CARD()->shadow()->bg_white()->add([
          CARDHEADER(T(__($titulo))->fs(1.6)),
          CARDBODY([$mensagem])->text_left()
        ])->style('max-width','600px')
      ])->style('min-height','600px')
    )
  ])->bg_image(site_url("assets/img/fundo-login.png")), 
 $footer
])->send();
