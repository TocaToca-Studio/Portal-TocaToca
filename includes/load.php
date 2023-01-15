<?php 
require_once __DIR__.'/../core/config.inc.php';
require_once __DIR__.'/static.php';
 



$page=PAGE($NOME_SITE)->configure()
    ->script("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js")
    ->css("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css")
    ->css("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css")
    ->css(site_url('assets/css/common.css'))
    ->script(site_url('assets/js/common.js?'.time()))
    ->icon(site_url("assets/favicon.png"))
    ->overflow_x_hidden();
$page->head->add(
    '<!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BTHLT7Q7YQ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag(\'js\', new Date());
    
      gtag(\'config\', \'G-BTHLT7Q7YQ\');
    </script>
    '
);