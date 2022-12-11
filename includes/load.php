<?php 
require_once __DIR__.'/../core/config.inc.php';

 

$NOME_SITE="Portal Tocatoca";


$page=PAGE($NOME_SITE)->configure()
    ->script("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js")
    ->css("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css")
    ->css("https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css")
    ->css(site_url('assets/css/common.css'))
    ->script(site_url('assets/js/common.js?'.time()))
    ->icon(site_url("assets/favicon.png"))
    ->overflow_x_hidden();