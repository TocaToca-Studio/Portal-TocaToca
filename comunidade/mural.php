<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';

require_once __DIR__ . '/includes/leftbar.php';
require_once __DIR__ . '/includes/rightbar.php';


$sample_post=[
  "date"=>time()-(3600*24),
  "title"=>"Sample title",
  "content"=>"Simple infinite lore ipsum sit amet i dont care this is a testing only",
  "author"=>"@alicezolinger"
];
function drawpost($post) {
  return A()->list_group_item()->add([
    FLEXROW()->content_between()->add([ 
      T($post['title'])->h5(),
      T(Utils::time_elapsed_string($post['date']))->muted()
    ]), 
    DIV($post['content'])->w_100()->pb(1),
    DIV($post['author'])->w_100()
  ]);
}
$postwall=DIV()->list_group()->my(2);
foreach(range(0,10) as $i) {
  $postwall->add(drawpost($sample_post));
}

$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER([
      NAVBAR("Comunidade")->add([
        FORM()->add([
          INPUTGROUP(false,SEARCHINPUT("Pesquisar"),BUTTON(I("search"))->primary())
        ])
      ]),
      ROW()->items_stretch()->add([
        COL(@$leftbar)->xs(12)->lg(3),
        COL([
          $postwall
        ])->xs(12)->lg(6),
        COL(@$rightbar)->xs(12)->lg(3) 
      ])->style('min-height','600px')
    ]) 
  ]) ,
  $footer
])->send();
