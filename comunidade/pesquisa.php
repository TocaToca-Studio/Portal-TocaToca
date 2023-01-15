<?php
require_once __DIR__ . '/includes/base.php';

// BACKEND
$q=_get("q");  
 

// murais, panelinhas e usuários

$cond_pesq_panela=SqlUtils::search_string(["p.nome","p.id"],$q);
$panelinhas=db()->fetch_all(
  "SELECT p.id,p.nome,COUNT(u_p.fk_usuario) AS n_membros FROM panelinha p 
    LEFT JOIN usuario_to_panelinha u_p ON u_p.fk_panelinha=p.id
    WHERE $cond_pesq_panela
    GROUP BY p.id
    ORDER BY COUNT(u_p.fk_usuario) DESC"
);
$cond_pesq_perfis=SQLUtils::search_string(["nome","id","email"],$q);
$perfis=db()->fetch_all(
  "SELECT nome,id,email FROM usuario WHERE $cond_pesq_perfis"
);

$cond_pesq_murais=SQLUtils::search_string(["m.nome","m.id"],$q);
$murais=db()->fetch_all(
  "SELECT m.id,m.nome,COUNT(b.id) AS n_bilhetes FROM mural m 
  LEFT JOIN bilhete b ON b.fk_mural=m.id
  WHERE $cond_pesq_murais
  GROUP BY m.id
  ORDER BY COUNT(b.id) DESC"
);

$abas=[
  'Panelinhas'=>DIV(json_encode($panelinhas))->tag("pre"),
  'Murais'=>DIV(json_encode($murais))->tag("pre"),
  'Perfis'=>DIV(json_encode($perfis))->tag("pre")
];

$tabs=UL()->class("nav nav-tabs")->role("tablist");
foreach($abas as $t=>$c) {
  $tabs->add(
    LI()->nav_item()->role("presentation")->add(
      A($t)->nav_link()->url("#".$t)->attr("data-toggle","tab")
    )
  );
}
$tabs_content=DIV()->id("AbasPesquisa")->class("tab-content");

foreach($abas as $a=>$c) {
  $tabs_content->add(
    DIV($c)->class("tab-pane fade")->id($a)->role("tabpanel")
  );
}

$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER([
      $navbar_comunidade,
      ROW()->items_stretch()->add([
        COL(@$leftbar)->xs(12)->lg(3),
        COL([ 
          $tabs,
          $tabs_content 
        ])->xs(12)->lg(6)->class("px-lg-1"),
        COL(@$rightbar)->xs(12)->lg(3) 
      ])->style('min-height','600px')
    ]) 
  ]) ,
  $footer,
])->send();
