<?php
# importa a biblioteca geovana.
require_once __DIR__.'/../geovana/config.php';

/** funcao para obter o url relativo da pasta do site.   */
function site_url($relative_url="",$absolute=false) {
    $website_folder='/';
    if($absolute) {
        $host = $_SERVER['HTTP_HOST'];
        $protocol=$_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        return "$protocol://$host".$website_folder.$relative_url;
    } else {
        return $website_folder.$relative_url;
    }
} 
// função para permitir tradução do site posteriormente
function __($texto) {
    return $texto;
}