<?php
/** GERA AS MINIATURAS USANDO A CLASSE IMAGETOOL E TAMBEM CACHEIA NO DISCO :) */

require_once __DIR__.'/ImageTool.php';
// funcao para enviar uma imagem generica para o navegador quando ocorre um erro
function send_blank() {
    $file = __DIR__.'/../img/prod_place.jpg';
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

// se o caminho da imagem nao for valido, envia uma imagem generica
$src=filter_input(INPUT_GET, 'src', FILTER_SANITIZE_URL);
if(!$src) send_blank();

// se o caminho da url for local do servidor, converte para o caminho absoluto no disco 
if(count($ar=explode('uploads', $src))==2) {
    $file=__DIR__.'/../original/uploads'.$ar[1];
    if(!file_exists($file) || is_dir($file)) send_blank();
    $src=$file;
} 

$w=1000;
if(isset($_GET['w']) && isset($_GET['w'])) $w=intval($_GET['w']);
$h=1000;
if(isset($_GET['h']) && isset($_GET['h'])) $h=intval($_GET['h']);

$cached_file=__DIR__.'/cache/'.$w.'x'.$h.'/'.md5($src).'.jpg';
if(file_exists($cached_file)) {
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . filesize($cached_file));
    readfile($cached_file); 
} else { 
    $im=(new ImageTool($src));
    $im->resize_max($w,$h);
    $im->send_to_browser();
    // se a pasta do arquivo não existir, cria
    if(!file_exists(dirname($cached_file))) mkdir(dirname($cached_file), 0777, true);
    // salva a imagem no cache
    $im->saveJPEG($cached_file);
    
}

