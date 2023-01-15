<?php
require_once __DIR__ . '/../../core/config.inc.php';

$navbar_comunidade=NAVBAR("Comunidade")->add([
    FORM()->url(site_url('comunidade/pesquisa'))->add([
      INPUTGROUP(
        false,
        SEARCHINPUT("Pesquisar")->name("q"),
        BUTTON(I("search"))->primary()->submit()
      )
    ])
])->mb(2)->bg_white();