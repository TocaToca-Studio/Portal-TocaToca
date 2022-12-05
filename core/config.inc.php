<?php
# importa a biblioteca geovana.
require_once __DIR__.'/../geovana/config.php';

require_once __DIR__.'/utils/DB.php';
require_once __DIR__.'/utils/LoginTool.php';
require_once __DIR__.'/utils/SimpleMail.php';
require_once __DIR__.'/utils/Utils.php';
require_once __DIR__ .'/helpers.php';


require_once __DIR__ .'/models/Model.php';
require_once __DIR__ .'/models/Usuario.php';

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
    return strval($texto);
}

define('UPLOADS_FOLDER',realpath(__DIR__.'/../uploads'));

require_once __DIR__.'/config.php';
// essas variaveis $DB_ estão em um arquivo secreto para preservar
// a segurança do site.
$db=new DB($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_DATABASE);
function db() {
    global $db;
    if(!$db->connected) $db->connect();
    return $db;
}
 

/** cria uma instância da biblioteca simplemail para ser usada como email do sistema */
function simplemail():SimpleMail {
    global $SMTP_HOST,$SMTP_PORT,$SMTP_PASSWORD,$SMTP_MAIL;
    $mail = new SimpleMail($SMTP_HOST, $SMTP_PORT, 'tls');
    $mail->auth($SMTP_MAIL, $SMTP_PASSWORD)
        ->from($SMTP_MAIL, "Portal TocaToca");
        
    return $mail;
}