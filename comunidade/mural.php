<?php
require_once __DIR__ . '/includes/base.php';

// BACKEND
$id=@intval(_get('id'));
$pagina=@intval(_get('pagina'));
if(!$id) $id=0; 
if(!$id) $pagina=1;

$itens_por_pagina=20;

$sql="SELECT b.id,b.texto,b.criado,u.nome as autor,u.nick,u.id as id_usuario
        FROM bilhete b
        INNER JOIN  usuario u ON b.criador=u.id
        WHERE fk_mural=$id OR $id=0
        ORDER BY criado DESC";

$results=paginar_query($sql,$pagina,$itens_por_pagina);
$bilhetes=$results['resultados']; 

/// CODIGO DA PAGINA

function desenhar_bilhete($bilhete) {
  $likes=total_curtidas(TIPO_PUB['bilhete'],$bilhete['id']);
  return DIV()->list_group_item()->add([
    FLEXROW()->items_center()->add([
      IMG(site_url('assets/img/user.png'),70,70)->img_cover(),
      DIV()->pl(1)->fill()->add([ 
        FLEXROW()->fill()->content_between()->add([ 
          A($bilhete['autor'])->decoration_none()->url("#")->h5(),
          T([
            Utils::time_elapsed_string($bilhete['criado']),
            I("thumbtack")->px(1)
          ])->muted()
        ]),
        DIV($bilhete['texto'])->w_100()->pb(1),
        FLEXROW([ 
          A([I("heart"),SPACE,number_shorten($likes)])->url("#")->muted()->fs(0.8)->decoration_none(),
          A([I("bookmark"),SPACE,__(@$bilhete['saves'])])->url("#")->muted()->fs(0.8)->decoration_none(),
        ])->gap(10)->content_end()
      ])
    ])
  ]);
} 
if(count($bilhetes)) {
  $postwall=DIV()->list_group()->my(2);
  foreach($bilhetes as $b) {
    $postwall->add(desenhar_bilhete($b));
  }
} else {
  $postwall=ALERT(__("Ninguém pendurou um bilhetinho aqui ainda :( seja o primeiro!"))->warning()->my(2);
}

function paginacao_mural($page) {
  global $id;
  return site_url('comunidade/mural?'.http_build_query(['id'=>$id,'pagina'=>$page]));
}

$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER([
      $navbar_comunidade,
      ROW()->items_stretch()->add([
        COL(@$leftbar)->xs(12)->lg(3),
        COL([
          DIV()->add([
            FORM()->add([
              TEXTAREA(__("no que está pensando?")),
              BUTTON([__("Pendurar bilhetinho!"),SPACE,I("sticky-note")])->primary()->block()
            ])
          ]),
          $postwall,
          PAGINATION('paginacao_mural',$results['total'],$itens_por_pagina,$pagina)
        ])->xs(12)->lg(6)->class("px-lg-1"),
        COL(@$rightbar)->xs(12)->lg(3) 
      ])->style('min-height','600px')
    ]) 
  ]) ,
  $footer
])->send();
