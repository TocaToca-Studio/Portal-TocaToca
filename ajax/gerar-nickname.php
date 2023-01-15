<?php 
require_once __DIR__.'/../core/config.inc.php';

function ja_existe($nick) {
    $nick=db()->escape(trim($nick));
    return db()->fetch_value("SELECT id FROM usuario WHERE nick='$nick'");
}

if(_get('verifica_nick')) {
    die(json_encode(ja_existe(_get('verifica_nick'))));
}


$nome=trim(_get('nome'));
if(!$nome) exit;



$sufixos=[
    'dbv','aventura','gamer'
];

$nick=strtolower($nome);
$nick=strtr($nick,[
    " "=>"", 
]);

$achou_valido=!ja_existe($nick);

if(!$achou_valido) {
    $sugestao=$nick;
    $i=0;
    while(ja_existe($sugestao)) $sugestao=$nick.(++$i);
    $nick=$sugestao;
}

die($nick);