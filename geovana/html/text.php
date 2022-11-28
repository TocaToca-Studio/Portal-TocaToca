<?php

require_once __DIR__.'/base.php';



class Br extends Div {
   function __construct() {
       $this->set_as_void_control();
       parent::__construct("br");
   }
}

class BreakLine extends Br {}
const BR = "<br>";  
function BR() {
    return (new Br);
}
class Hr extends Div {
   function __construct() {
       parent::__construct();
       $this->set_as_void_control()->tag("hr");
       
   }
}
const HR = "<hr/>";
function HR() {
    return (new Hr);
}
class LineSeparator extends Hr {}


const SPACE="&nbsp";
function T($text=false) {
    return text($text);
}
function text($text=false) {
    
    if(is_array($text)) {
      $new_text=(new Text);
      foreach ($text as $control) {
          $new_text->add($control);
      }
      return $new_text;
    } else {
        if(is_numeric($text)) {
            $text=to_html($text);
        }
        if($text instanceof String) {
            return (new Text)->add(to_html($text));
        } else {
            return (new Text)->add($text);
        }
    }
}

class Text extends Span {
    public $is_strong=false;
    public $is_subscript=false;
    public $is_superscript=false;
    public $is_emphasized=false;
    public $is_marked=false;
    public $is_deleted=false;
    public $is_inserted=false;
    
    
    function card_title() {return $this->class("card-title");}
    function card_text() {return $this->class("card-text");}
    function card_subtitle() {return $this->class("card-subtitle"); }
    
    function paragraph()    {return $this->tag("p");}
    function subscript()    {$this->is_subscript=true;return $this;}
    function superscript()  {$this->is_superscript=true;return $this;}
    function emphasized()   {$this->is_emphasized=true;return $this;}
    function marked()       {$this->is_marked=true;return $this;}
    function deleted()      {$this->is_deleted=true;return $this;}
    function underline() { return $this->style("text-decoration","underline");}
    function send($inner="") {
        if ($this->is_subscript)    { echo "<sub>";}
        if ($this->is_superscript)  { echo "<sup>";}
        if ($this->is_emphasized)   { echo "<em>";}
        if ($this->is_marked)       { echo "<mark>";}
        if ($this->is_deleted)      { echo "<del>";}
        
        parent::send($inner);
        
        if ($this->is_deleted)      { echo "</del>";}
        if ($this->is_marked)       { echo "</mark>";}
        if ($this->is_emphasized)   { echo "</em>";}
        if ($this->is_superscript)  { echo "</sup>";}
        if ($this->is_subscript)    { echo "</sub>";}
    }


    function html($inner="") {
        $html= parent::html($inner);
        if ($this->is_marked)
            { $html=inside_tag($html,"mark");}
        if ($this->is_emphasized)
            { $html=inside_tag($html,"em");}
        if ($this->is_superscript)
            { $html=inside_tag($html,"sup");}
        if ($this->is_subscript)
            { $html=inside_tag($html,"sub");}
       return $html;
    }
}

class ListRow extends Text {
    public function __construct($content=false) {
        parent::__construct();
        if($content) { $this->add($content);}
        $this->tag("li");
    }
    public function active() {
        return $this->class("active");
    }
    function list_group_item() {
        return $this->class("list-group-item");
    }
    function nav_item() {
        return $this->class("nav-item");
    }
}
function LI($content,$list_group=false) {
    $li=(new ListRow($content));
    if($list_group) { $li->list_group_item();}
    return $li;
}
function NAVLI($content,$active=false) {
    $li=(new ListRow($content))->nav_item();
    if($active) { $li->active();}
    return $li;
}
class HtmlList extends RowContainer {
    function  unstyled() {
        return $this->class("list-unstyled");
    }
    function inline() {
        return $this->class("list-inline");
    }
    function list_group() {
        return $this->class("list-group");
    }
    function __construct($rows=false) {
       if($rows) {
         $this->add_rows($rows);
       }
       parent::__construct();
       $this->tag("ul");
   }
   function set_ordered() {
       $this->tag("ol");
       return $this;
   }
   function set_unordered() {
       $this->tag("ul");
       return $this;
   }
   public function add_row($row) {
       if($row instanceof ListRow) {
           $this->rows[]=$row;
       } else {
           $this->rows[]=(new ListRow)->add($row);
       }
       return $this;
   }
} 

function UL($rows=false) {
    return (new HtmlList($rows))->set_unordered();
}
function OL($rows=false) {
    return (new HtmlList($rows))->set_ordered();
}

function UnorderedList($rows) { return UL($rows);}
function OrderedList($rows) { return OL($rows);}





class NavList extends HtmlList {
    public function __construct($rows = false) {
        parent::__construct($rows);
        $this->class("nav");
    }
  public function add_row($row) {
       if($row instanceof ListRow) {
           $this->rows[]=$row->class("nav-item");
       } else {
           $this->rows[]=(new ListRow)->add($row)->class("nav-item");
       }
       return $this;
   }
   function nav_tabs() {
       return $this->class("nav-tabs");
   }
} 



function LinkList($array,$list_group=false) {
    $list=(new HtmlList);
  if(is_array($array)) {
        foreach ($array as $item) {
            if(is_array($item)) {
                if(count($item)===1) {
                    $list->add_row(
                        LI(to_control($item[0]),$list_group)
                    );
                }else if(count($item)===2) {
                    $list->add_row(
                        LI(A($item[0],$item[1]),$list_group)
                    );
                } 
            } else {
            $list->add_row(to_control($item));
            }
        } 
    }
    return $list;
}




function NavLinkList($array) {
    $list=(new NavList);
    if(is_array($array)) {
        foreach ($array as $item) {
            if(is_array($item)) {
                if(count($item)===1) {
                    $list->add_row(
                        NAVLI(to_control($item[0])->class("nav-link"))
                    );
                }else if(count($item)===2) {
                    $list->add_row(
                        NAVLI(A($item[0],$item[1])->class("nav-link"))
                    );
                } else if(count($item)===3) {
                    $list->add_row(
                        NAVLI(A($item[0],$item[1])->class("nav-link"),true)
                    );
                }
            } else {
                 $list->add_row(
                        to_control($item)->class("nav-link")
                    );
            }
        }
    }
    return $list;
}

class Pagination extends HtmlList {
    public function __construct($rows = false) {
        parent::__construct($rows);
        $this->class("pagination");
    }
    public function add_row($row) {
       if($row instanceof ListRow) {
           $this->rows[]=$row;
       } else {
           if($row instanceof HiperLink) {
               $row->class("page-link");
           }
           $this->rows[]=(new ListRow)->add($row)->class("page-item");
       }
       return $this;
    }
}
function PAGINATION_LINKITEM($name,$href,$active=false) {
    $item=(new ListRow(A(strval($name),$href)->class("page-link")))->class("page-item");
    if($active){$item->class("active");}
    return $item;
}

function PAGINATION($funcao_link,$total_itens,$itens_por_pagina,$pagina_atual,$n_paginas_para_exibir=8) {
        if(!is_callable($funcao_link)) {
            return false;
        }
        $total_de_paginas=intval($total_itens/$itens_por_pagina);
        if(($total_itens%$itens_por_pagina)>0) { $total_de_paginas++;}

        if($total_de_paginas<2) {
            return false; 
        }
        $mais_paginas_na_frente=false;
        $mais_paginas_atras=false;
        $inicio=$pagina_atual-intval($n_paginas_para_exibir/2);
        $fim=$pagina_atual+intval($n_paginas_para_exibir/2);
        if($inicio<1) {
            $inicio=1;
            $fim=$inicio+($n_paginas_para_exibir+1);
        } else {
            $mais_paginas_atras=true;
        }
        if($fim>=$total_de_paginas) {
            $fim=$total_de_paginas;
            $inicio=$fim-($n_paginas_para_exibir);
        } else {
            $mais_paginas_na_frente=true;
        }
        if($inicio<1) $inicio=1;
        if($fim>$total_de_paginas) $fim=$total_de_paginas;
        if($mais_paginas_atras) {
           $paginas[]= PAGINATION_LINKITEM("&laquo;",call_user_func($funcao_link,($pagina_atual-1))); 
        } 
        
        for($i=$inicio;$i<=$fim;$i++) {
            $paginas[]= PAGINATION_LINKITEM($i, call_user_func($funcao_link,$i),$i==$pagina_atual);
        }
        if($mais_paginas_na_frente) {
           $paginas[]= PAGINATION_LINKITEM("&raquo;", call_user_func($funcao_link,($pagina_atual+1))); 
        }
    return (new Pagination($paginas))->center()->my(3);
}
function PAGINATION_BUTTONITEM($name,$page,$active=false,$fn_name='change_page',$response="div_postback") {
    $item=(
    new ListRow([
            BUTTON(strval($name))->class("page-link")
            ->call($fn_name)
            ->params(["page"=>$page])
            ->response($response)
        ])
    )->class("page-item");
    if($active){$item->class("active");}
    return $item;
}
function PAGINATIONBUTTON($fn_name,$total_itens,$itens_por_pagina,$pagina_atual,$n_paginas_para_exibir=8,$response="div_postback") {
       
        $pagina_atual=intval($pagina_atual);
        $itens_por_pagina=intval($itens_por_pagina);
        $total_de_paginas=intval($total_itens/$itens_por_pagina);
        $n_paginas_para_exibir=intval($n_paginas_para_exibir);
        
        if(($total_itens%$itens_por_pagina)>0) { $total_de_paginas++;}

        if($total_de_paginas<2) {
            return false; 
        }
        $mais_paginas_na_frente=false;
        $mais_paginas_atras=false;
        $inicio=$pagina_atual-intval($n_paginas_para_exibir/2);
        $fim=$pagina_atual+intval($n_paginas_para_exibir/2);
        if($inicio<1) {
            $inicio=1;
            $fim=$inicio+($n_paginas_para_exibir+1);
        } else {
            $mais_paginas_atras=true;
        }
        if($fim>=$total_de_paginas) {
            $fim=$total_de_paginas;
            $inicio=$fim-($n_paginas_para_exibir);
        } else {
            $mais_paginas_na_frente=true;
        }
        if($inicio<1) $inicio=1;
        if($fim>$total_de_paginas) $fim=$total_de_paginas;
        if($mais_paginas_atras) {
           $paginas[]= PAGINATION_BUTTONITEM("&laquo;",($pagina_atual-1),false,$fn_name,$response); 
        } 
        
        for($i=$inicio;$i<=$fim;$i++) {
            $paginas[]= PAGINATION_BUTTONITEM($i,$i,$i==$pagina_atual,$fn_name,$response);
        }
        if($mais_paginas_na_frente) {
           $paginas[]= PAGINATION_BUTTONITEM("&raquo;", $i,false,$fn_name,$response); 
        }
    return (new Pagination($paginas))->center()->my(3);
}
