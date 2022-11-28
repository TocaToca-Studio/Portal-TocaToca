<?php 
require_once __DIR__.'/../core/config.inc.php';

 

$NOME_SITE="Portal Tocatoca";


$page=PAGE($NOME_SITE)->configure()
    ->icon(site_url("assets/favicon.png"))
    ->overflow_x_hidden();