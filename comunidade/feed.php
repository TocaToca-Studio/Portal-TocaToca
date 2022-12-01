<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/footer.php';

require_once __DIR__ . '/includes/sidebar.php';
 

$page->add([
  $header,
  PAGE_MAIN([
    CONTAINER(
      ROW()->items_stretch()->add([
        COL(@$sidebar)->xs(false)->lg(3)->class("border-right"),
        COL([
            
        ])->xs(12)->lg(9)
      ])->style('min-height','600px')
    )
  ]) ,
  $footer
])->send();
