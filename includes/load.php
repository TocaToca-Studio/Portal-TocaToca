<?php 
require_once __DIR__.'/../core/config.inc.php';
require_once __DIR__.'/static.php';
 
$info_usuario=[];
$logado=LoginTool::is_logged();
if($logado) {
  $info_usuario=Usuario::logged_user()->get_infos(['*']);

  // se nao confirmou email, direciona para a página de confirmação de e-mail
  if(!$info_usuario['confirmou_email']) {
    if(strpos(Utils::self_url(),"confirmar-email")==false) {
      $nick=$info_usuario['nick'];
      Utils::redirect(
        site_url("conta/confirmar-email")
          .'?'.http_build_query([
            'nick'=>$nick,
            'assinatura'=>gera_assinatura($nick)
          ])
        );
    }
  }
}
if(isset($titulo_pagina)) {
  $titulo_pagina=$NOME_SITE. ' - '. $titulo_pagina;
} else { 
$titulo_pagina=$NOME_SITE;
}
function gera_assinatura($dados):int {
  return crc32(json_encode($dados).MASTER_PASSWORD);
}
function verifica_assinatura($dados,$assinatura):bool {
  return gera_assinatura($dados)==intval($assinatura);
}

$page=PAGE($titulo_pagina)->configure()
    ->script("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js")
    ->css("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css")
    ->css("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css")
    ->css(site_url('assets/css/common.css?'.time()))
    ->script(site_url('assets/js/common.js?'.time()))
    ->icon(site_url("assets/favicon.png"))
    ->overflow_x_hidden()
    ->script("https://www.google.com/recaptcha/api.js");



$page->head->add(
    "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-BTHLT7Q7YQ\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-BTHLT7Q7YQ');
  </script>
");

if($logado) {
  $page->head->add(
  "<script>
    gtag('config', 'GA_MEASUREMENT_ID', {
      'user_id': ".json_encode($info_usuario['email'].'-'.$info_usuario['id'])."
    });
  </script>
");
}