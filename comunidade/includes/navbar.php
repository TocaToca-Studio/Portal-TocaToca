<?php
require_once __DIR__ . '/../../core/config.inc.php';

$navbar_comunidade=NAVBAR("Comunidade")->add([
    FORM()->add([
      INPUTGROUP(false,SEARCHINPUT("Pesquisar"),BUTTON(I("search"))->primary())
    ])
])->mb(2);