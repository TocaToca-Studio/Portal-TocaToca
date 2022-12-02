<?php
require_once __DIR__ . '/../../core/config.inc.php';
require_once __DIR__.'/murais-populares.php';
require_once __DIR__.'/panelinhas.php';
 

$rightbar=FLEXCOL()->add([
    $murais_populares,
    $panelinhas->mt(3)
])->my(2);