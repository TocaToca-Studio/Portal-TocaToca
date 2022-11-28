<?php

require_once __DIR__.'/base.php';
require_once __DIR__.'/text.php';
require_once __DIR__.'/forms.php';
require_once __DIR__.'/media.php';
require_once __DIR__.'/table.php';

//botstrap specific elements
class Container extends Div {
    public function __construct($content = false,$fluid=false) {
        parent::__construct($content);
        if($fluid) {
            $this->class("container-fluid"); 
        } else {
             $this->class("container"); 
        }
    }
}

function CONTAINER($content=false,$fluid=false) {
   return (new Container($content,$fluid));
}
function CONTAINERFLUID($content=false) {
    return CONTAINER($content, true);
}

class Row extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("row");
    }
}
class Col extends Div {
    public $display="block";
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("col");
    }
    public function grid($xs=false,$sm=false,$md=false,$lg=false,$xl=false) {
        if($sm===false){$sm=$xs;}
        if($md===false){$md=$sm;}
        if($lg===false){$lg=$md;}
        if($xl===false){$xl=$lg;}
        
        return $this->xs($xs)->sm($sm)->md($md)->lg($lg)->xl($xl);
    }
    function display($d) {
        $this->display=$d;
        return $this;
    }
    function d_flex() {
       return $this->display("flex");
    }
    function d_block() {
       return $this->display("block");
    }
    function d_inline() {
       return $this->display("inline");
    }
    function device($device,$val) {
        if($device=="xs") {
            if($val===0) {
                $this->class("d-none");
            } else {
                $this->class("col-$val")->class("d-".$this->display);
            }
        } else {
            if($val===0) {
                $this->class("d-$device-none");
            } else {
                $this->class("col-$device-$val")->class("d-$device-".$this->display);
            }
        }
        return $this;
    }
    public function xs($val) {
        return $this->device("xs",$val);
    }
    public function sm($val) {
        return $this->device("sm",$val);
    }
    public function md($val) {
        return $this->device("md",$val);
    }
    public function lg($val) {
        return $this->device("lg",$val);
    }
    public function xl($val) {
        return $this->device("xl",$val);
    }
}

function ROW($content=false) {
    return (new Row($content));
}
function COL($content=false) {
    return (new Col($content));
}



class Alert extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("alert")->role("alert");
    }
    public function primary() {
        return $this->class("alert-primary");
    }
    public function secondary() {
        return $this->class("alert-secondary");
    }
    public function success() {
        return $this->class("alert-success");
    }
    public function danger() {
        return $this->class("alert-danger");
    }
    public function warning() {
        return $this->class("alert-warning");
    }
    public function info() {
        return $this->class("alert-info");
    }
    public function light() {
        return $this->class("alert-light");
    }
    public function dark() {
        return $this->class("alert-dark");
    }
    public function dismissible() {
        $this->add(CLOSEBUTTON()->attr("data-dismiss","alert"));
        return $this->class("alert-dismissible");
    }
}

function ALERT($content=false,$fade=true) {
    $alert=(new Alert($content));
    if($fade) {
        $alert->fade()->show();
    }
    return $alert;
}

class Badge extends Div {
        public function __construct($content = false) {
    parent::__construct($content);
        $this->class("badge");
    }
    public function primary() {
        return $this->class("badge-primary");
    }

    public function secondary() {
        return $this->class("badge-secondary");
    }
    public function success() {
        return $this->class("badge-success");
    }
    public function danger() {
        return $this->class("badge-danger");
    }
    public function warning() {
        return $this->class("badge-warning");
    }
    public function info() {
        return $this->class("badge-info");
    }
    public function light() {
        return $this->class("badge-light");
    }
    public function dark() {
        return $this->class("badge-dark");
    }
    public function pill() {
        return $this->class("badge-pill");
    }
}

function Badge($content=false) {
    return (new Badge($content));
}



class BreadcrumbItem extends ListRow {
    public function __construct() {
        parent::__construct();
        $this->tag("li")->class("breadcrumb-item");
    }
}

class Breadcrumb extends HtmlList  {
    function __construct($rows=false) {
       parent::__construct($rows);

       $this->class("breadcrumb");
   }
   public function add_row($row) {
    if($row instanceof BreadcrumbItem) {
        $this->rows[]=$row;
    } else {
       $this->rows[]=(new BreadcrumbItem)->add($row);
    }
       return $this;
   }
} 
function BREADCUMB($items) {
    return (new Breadcrumb($items));
}


class CardHeader extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("card-header");
    }
}
function  CARDHEADER($content) {
    return (new CardHeader($content));
}

class CardBody extends Div {
    public function __construct($content = false) {
        parent::__construct();
        $this->class("card-body")->add($content);
    }
}

function  CARDBODY($content=false) {
    return (new CardBody($content));
}

class CardFooter extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("card-footer");
    }
}
function  CARDFOOTER($content) {
    return (new CardFooter($content));
}

class Card extends Div {
    public $header,$body,$footer,$img_top,$img_bottom;
    public function __construct($header=false,$body=false,$footer=false,$img_top=false,$img_bottom=false,$extra_content = false) {
        $this->class("card");
        $this->header($header);
        
        $this->img_top($img_top);
        $this->body($body);
        $this->footer($footer);
        
        $this->img_bottom($img_bottom);
        
        parent::__construct($extra_content);
    }
    public function img_bottom($img=false) {
        if($img) {
            if($img instanceof Img) { $this->img_bottom=$img;}
            else if(is_string($img)) { $this->img_bottom=IMAGE($img);}
            else {return $this;}
            $this->img_bottom->class("card-img-bottom");
            $this->add($this->img_bottom);
            return $this;
        } else {return $this->img_bottom;}
    }
    public function img_top($img=false) {
       if($img) {
            if($img instanceof Img) { $this->img_top=$img;}
            else if(is_string($img)) { $this->img_top=IMAGE($img);}
            else {return $this;}
            $this->img_top->class("card-img-top");
            $this->add($this->img_top);
            return $this;
        } else {return $this->img_top;}
    }
    public function body($content=false) {
        if(!$content) { return $this->body;}
        if($this->body instanceof CardBody) {
            $this->body->add($content);
        } else {
            if($content instanceof CardBody) {
                $this->body=$content;
            } else {
                $this->body=CARDBODY($content);
            }
            $this->add($this->body);
        }
        return $this;
    }
    public function header($content=false) {
        if(!$content) { return $this->header;}
        if($this->header instanceof CardHeader) {
            $this->header->add($content);
        } else {
            if($content instanceof CardHeader) {
                $this->header=$content;
            } else {
                $this->header=CARDHEADER($content);
            }
            $this->add($this->header);
        }
        return $this;
    }
    public function footer($content=false) {
        if(!$content) { return $this->footer;}
        if($this->footer instanceof CardFooter) {
            $this->footer->add($content);
        } else {
            if($content instanceof CardFooter) {
                $this->footer=$content;
            } else {
                $this->footer=CARDFOOTER($content);
            }
            $this->add($this->footer);
        }
        return $this;
    }
    public function p_body($val) {
        $this->body->p($val);return $this;
    }
    public function m_body($val) {
        $this->body->m($val);return $this;
    }
    public function p_header($val) {
        $this->header->p($val);return $this;
    }
    public function m_header($val) {
        $this->header->m($val);return $this;
    }
    public function p_footer($val) {
        $this->footer->p($val);return $this;
    }
    public function m_footer($val) {
        $this->footer->m($val);return $this;
    }
}

function CARD($header=false,$body=false,$footer=false,$img_top=false,$img_bottom=false,$extra_content = false) {
   return (new Card($header,$body,$footer,$img_top,$img_bottom,$extra_content));
}
function WELL($content=false,$padding=2) {
    if(!($content instanceof CardBody)) {
        $content= CARDBODY($content)->p($padding);
    }
    return CARD(false,$content);
}

function ITEMCARD($img,$content) {
    return CARD(false,false,$content,$img);
}

function IMAGECARD($img=false,$content=false,$extra_content = false) {
    return (new ImageCard($img,$content,$extra_content));
}
function PANELCARD($header=false,$body=false,$footer=false) {
    return CARD($header, $body, $footer);
}
class ImageCard extends Div {
        public $image,$div_image_overlay;
        public function __construct($img=false,$content=false,$extra_content = false) {
            if($img) {
                $this->img($img);
                if($content) {
                    $this->div_image_overlay=DIV($content)->class("card-img-overlay");
                    $this->add_once($this->div_image_overlay);
                }
            }
            parent::__construct($extra_content);
            $this->class("card");
            
        }
        public function img($img=false) {
        if($img) {
            if($img instanceof Img) { $this->image=$img;}
            else if(is_string($img)) { $this->image=IMAGE($img);}
            else {return $this;}
            $this->image->class("card-img");
            $this->add_once($this->image);
            return $this;
        } else {return $this->image;}
    }
}


class CardColumn extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->card_columns();
    }
}
function CARDCOLUMN($content) {
    return (new CardColumn($content));
}


function CLOSEBUTTON() {
    return BUTTON(SPAN("&times;")->attr("aria-hidden", "true"))
                 ->class("close")->attr("aria-label", "Close");
}



class Navbar extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("navbar");
    }
    public function expand($device) {
        return $this->class("navbar-expand-$device");
    }
    public function brand($content) {
        $this->add(to_control($content)->class("navbar-brand"));
        return $this;
    }
    public function expand_sm(){
        return $this->expand("sm");
    }
    public function expand_md(){
        return $this->expand("md");
    }
    public function expand_lg(){
        return $this->expand("lg");
    }
    function light() {
        return $this->class("navbar-light");
    }
    function dark() {
        return $this->class("navbar-dark");
    }
}

function NAVBAR($brand=false) {
    $navbar=(new Navbar);
    if($brand) {
        $navbar->brand($brand);
    }
    return $navbar;
}


class Carousel extends Div {
    public $inner;
    public function __construct($id,$imgs=false,$controls=true) {
        parent::__construct();
        
        $this->inner=DIV()->class("carousel-inner");
        $this->class("carousel")->attr("data-ride","carousel")->id($id);
        if(is_array($imgs)) {
            foreach ($imgs as $value) {
                if(is_string($value)) {
                    $this->add_image($value);
                } else {
                    if(is_array($value)) {
                        if(count($value)===2) {
                            $this->add_image($value[0],$value[1]);
                        }
                    } else {
                        $this->add_item($value);
                    }
                }
            }
        }
        
        $this->add($this->inner);
        if($controls) {$this->controls();}
    }
    function slide() {
        return $this->class("slide");
    }
    function select_first() {
        if($this->inner) {
            if(count($this->inner->subcontrols)) {
                $this->inner->subcontrols[0]->class("active");
            }
        }
        return $this;
    }
    function add_item($content,$active=false) {
         $div=DIV(DIV($content)->class("d-block")->class("w-100"))->class("carousel-item");
        if($active) {$div->class("active");}
        $this->inner->add($div);
        return $this;
    }
    function add_image($url,$active=false) {
        $div=DIV(IMAGE($url)->class("d-block")->class("w-100"))->class("carousel-item");
        if($active) {$div->class("active");}
        $this->inner->add($div);
        return $this;
    }
    function add_image_link($url,$url_link,$active=false) {
        $div=DIV(
            A(
                IMAGE($url)->class("d-block")->class("w-100")
            )->url($url_link)
        )->class("carousel-item");
        if($active) {$div->class("active");}
        $this->inner->add($div);
        return $this;
    }
    public function controls() {
        $this->add([
                A()->rolebutton()
                    ->class("carousel-control-prev")
                    ->attr("data-slide", "prev")->add(
                        DIV(
                            DIV()->class("carousel-control-prev-icon")
                            ->attr("aria-hidden", "true")
                            ->d_block()
                        )->bg_color("#66666677")->p(2)->circle()
                    )->href("#".$this->id()),
                A()->rolebutton()->class("carousel-control-next")
                    ->attr("data-slide", "next")->add(
                        DIV(
                            DIV()->class("carousel-control-next-icon")
                            ->attr("aria-hidden", "true")
                            ->d_block()
                        )->bg_color("#66666677")->p(2)->circle()
                    )->href("#".$this->id())
                ]);
        return $this;
    }
    
}


/// funcao de classe card-header-tabs na classe NAV


function CAROUSEL($id,$imgs=false,$controls=true) {
    return (new Carousel($id, $imgs, $controls));
}

class Icon extends Span {
    public function __construct($name=false,$brand=false) {
        parent::__construct();
        if($name) {
            if($brand) {
                $this->class("fab");
            } else {
                $this->class("fa");
            }
            $this->class("fa-$name")
                    ->attr("aria-hidden", "true");
        }
        $this->tag("i");
    }
    function xs() {
        return $this->class("fa-xs");
    }
    function sm() {
        return $this->class("fa-sm");
    }
    function lg() {
        return $this->class("fa-lg");
    }
    function scale($x=2) {
        return $this->class("fa-".$x."x");
    }
    function brand() {
        $this->classes = array_diff($this->classes , ["fa"]);
        return $this->class("fab");
    }
}
function ICON($name=false,$brand=false) {
    return (new Icon($name,$brand));
}
function I($name=false,$brand=false) {
    return ICON($name,$brand);
}



class ModalHeader extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("modal-header");
    }
}
function  MODALHEADER($content) {
    return (new ModalHeader($content));
}

class ModalBody extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("modal-body");
    }
}

function  MODALBODY($content) {
    return (new ModalBody($content));
}

class ModalFooter extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("modal-footer");
    }
}
function  MODALFOOTER($content) {
    return (new ModalFooter($content));
}
function send_close_modals() {
    SCRIPT()->add('$(".modal").modal("hide");')->send();
}
function send_update_control($id) {
    SCRIPT()->add("update_control('$id');")->send();
}
class Modal extends Div {
    public $header,$body,$footer,$dialog,$content;
    public function __construct($header=false,$body=false,$footer=false,$extra_content = false) {
        $this->class("modal")->role("dialog")->attr("tabindex", "-1");
        $this->dialog=DIV()->class("modal-dialog")->role("document");
        $this->content=DIV()->class("modal-content");
        
        if($header) {$this->header($header);}
        if($body) {$this->body($body);}
        if($footer) {$this->footer($footer);}
        parent::__construct();
        $this->add_once($this->dialog->add_once($this->content))->add($extra_content);
    }
    public function aria_labelledby($label) {
        
        return $this->attr("aria-labelledby",$label);
    }
    public function aria_hidden($value) {
        return $this->attr("aria-hidden",$value);
    }
    function centered() {
        $this->dialog->class("modal-dialog-centered");
        return $this;
    }
    public function body($content=false) {
        if(!$content) { return $this->body;}
        if($this->body) {
            $this->body->add($content);   
        } else {
            if($content instanceof ModalBody) {
                $this->body=$content;
            } else {
                $this->body=MODALBODY($content);
            }
            $this->content->add_once($this->body);
        }
        return $this;
    }
    public function header($content=false) {
        if(!$content) { return $this->header;}
        if($this->header) {
            $this->header->add($content);   
        } else {
            if($content instanceof ModalHeader) {
                $this->header=$content;
            } else {
                $this->header=MODALHEADER($content);
            }
            $this->content->add_once($this->header);
        }
        return $this;
    }
    public function footer($content=false) {
        if(!$content) { return $this->footer;}
        if($this->footer) {
            $this->footer->add($content);   
        } else {
            if($content instanceof ModalFooter) {
                $this->footer=$content;
            } else {
                $this->footer=MODALFOOTER($content);
            }
            $this->content->add_once($this->footer);
        }
        return $this;
    }
    public function closebutton($title=false,$color="secondary") {
       $this->header(CLOSEBUTTON()->dismiss_modal()->target("#".$this->id())->float_right());
       if($title) {
             $this->footer(BUTTON($title)->dismiss_modal()->target("#".$this->id())->class("btn-$color"));
       }
       return $this;
    }
    
    public function modalshow() {
        return $this->class("modal-show");
    }
    public function loading_modal() {
        return $this->class("loading-modal");
    }
}

function MODAL($header=false,$body=false,$footer=false,$extra_content = false) {
    return (new Modal($header,$body,$footer,$extra_content))->aria_hidden("true")->fade()->closebutton();
}

function DROP_DIVIDER() {
   return DIV("")->class("dropdown-divider");
}

class DropmenuList extends HtmlList {
    public function __construct($rows = false) {
        parent::__construct($rows);
        $this->class("dropdown-menu")->tag("div");
    }
    public function add_row($row) {
        if($row) {
        if($row instanceof HiperLink) {
             $this->rows[]=$row->class("dropdown-item");
         } elseif($row instanceof Div) {
             $this->rows[]= $row;
         } else {          
             $this->rows[]= A($row)->add($row)->class("dropdown-item");
         }
         return $this;  
        }

     }
}
class DropMenu extends Div {
    public $menu;
    public function __construct($btn_or_link,$rows) {
        parent::__construct();
        $this->add(to_control($btn_or_link)->class("dropdown-toggle")->attr("data-toggle","dropdown"))->class("dropdown");
        $this->menu=(new DropmenuList($rows))->class("dropdown-menu");
        $this->add($this->menu);
    }
    public function right() {
        return $this->class("dropright");
    }
    public function left() {
        return $this->class("dropleft");
    }
    public function up() {
        return $this->class("dropup");
    }
    function menu_right($screen_device) {
        $this->menu->class("dropdown-menu-left");
        $this->menu->class("dropdown-menu-$screen_device-right");
        return $this;
    }
    function menu_left($screen_device) {
        $this->menu->class("dropdown-menu-right");
        $this->menu->class("dropdown-menu-$screen_device-left");
        return $this;
    }
    function fix_menu() {
        $this->menu->z_index_top();
        return $this;
    }
}
function DROPMENU($btn_or_link,$rows) {
    return (new DropMenu($btn_or_link, $rows));
}

class Spinner extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->class("spinner-border")->role("status");
        $this->add(
             DIV("Loading..")->class("sr-only")
        );
    }
    function sm() {
        $this->class("spinner-border-sm");
        return $this;
    }
    function lg() {
        $this->class("spinner-border-lg");
        return $this;
    }
}
function SPINNER() {
    return (new Spinner());
}
