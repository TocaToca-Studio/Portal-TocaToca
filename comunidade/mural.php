<?php
require_once __DIR__ . '/includes/base.php';

// BACKEND
$id=intval(_get('id'));
if(!$id) $id=0;

/*
$bilhetes=db()->fetch_all(
  "SELECT b.id,b.texto,b.criado,u.nome,u.nick,u.id as id_usuario
  FROM bilhete b
  INNER JOIN  usuario u ON b.criador=u.id
  WHERE fk_mural=$id OR $id=0
  ORDER BY criado DESC"
); 
*/

/// CODIGO DA PAGINA
$sample_post=[
  "date"=>time()-(3600*24), 
  "content"=>"Simple infinite lore ipsum sit amet i dont care this is a testing only",
  "author"=>"@alicezolinger",
  "likes"=>5,
  "saves"=>2
];
function drawpost($post) {
  return DIV()->list_group_item()->add([
    FLEXROW()->items_center()->add([
      IMG(site_url('assets/img/user.png'),70,70)->img_cover(),
      DIV([ 
        FLEXROW()->fill()->content_between()->add([ 
          A($post['author'])->decoration_none()->url("#")->h5(),
          T([
            Utils::time_elapsed_string($post['date']),
            I("thumbtack")->px(1)
          ])->muted()
        ]),
        DIV($post['content'])->w_100()->pb(1),
        FLEXROW([ 
          A([I("heart"),SPACE,__($post['likes'])])->url("#")->muted()->fs(0.8)->decoration_none(),
          A([I("bookmark"),SPACE,__($post['saves'])])->url("#")->muted()->fs(0.8)->decoration_none(),
        ])->gap(10)->content_end()
      ])->pl(1)
    ])
  ]);
}
$posts=range(0,10);
if(count($posts)) {
  $postwall=DIV()->list_group()->my(2);
  foreach($posts as $i) {
    $postwall->add(drawpost($sample_post));
  }
} else {
  $postwall=ALERT(__("Ninguém pendurou um bilhetinho aqui ainda :( seja o primeiro!"))->warning()->my(2);
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
          PAGINATION('strval',10000,10,4)
        ])->xs(12)->lg(6)->class("px-lg-1"),
        COL(@$rightbar)->xs(12)->lg(3) 
      ])->style('min-height','600px')
    ]) 
  ]) ,
  $footer
])->send();
