<?php

function startsWith($haystack, $needle){
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function escape_string($inp) {
    if(is_array($inp))
        return array_map(__METHOD__, $inp);
    if(!empty($inp) && is_string($inp)) {
        return str_replace(['\\', "\0", "\n", "\r", "'", '"', "\x1a"], ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'], $inp);
    }
    return $inp;
}
function unescape_string($inp) {
    if(is_array($inp))
        return array_map(__METHOD__, $inp);
    if(!empty($inp) && is_string($inp)) {
        return str_replace(
                ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'],
                ['\\', "\0", "\n", "\r", "'", '"', "\x1a"],
                $inp
            );
    }
    return $inp;
}

function str_escape($inp) {
    return escape_string($inp);
}
function str_unescape($inp) {
    return escape_string($inp);
}


$_GEOVANA_VARS=[];
$_GEOVANA_ASSIGNED_CONTROLS=[];
$GEOVANA_CANCEL_POSTBACK=false;

$PARAMS=[];
$STATEVARS=[];
function cancel_postback() {
    global $GEOVANA_CANCEL_POSTBACK;
    $GEOVANA_CANCEL_POSTBACK=true;
}
function gfilter($value) {
    if(is_string($value)) {
        return htmlspecialchars($value,ENT_QUOTES);
    } elseif(is_array($value)) { 
        return array_map('gfilter', $value);
    } else {return $value;}
}
if(filter_has_var(INPUT_POST, 'params')) {
    $PARAMS=gfilter(json_decode(filter_input(INPUT_POST, 'params'),JSON_OBJECT_AS_ARRAY));
}
if(filter_has_var(INPUT_POST, 'statevars')) {
    $STATEVARS=gfilter(json_decode(filter_input(INPUT_POST, 'statevars'),JSON_OBJECT_AS_ARRAY));
}
if(is_rendering_posback()) {
    // experimental
    //block_page_caching();
}

function param($name,$value=false) {
    global $PARAMS;
    if($value===false) {
        if(isset($PARAMS[$name]) ) {
            if($PARAMS[$name]==="GEOVANA_UNSET") {
              return false;
            }
            return $PARAMS[$name];
        } else {
            return false;
        }  
    } else {
        $PARAMS[$name]=$value;
        return $value;
    }

    
    
}
function form_reset() {
    /// limpa paramentros para nao retroalimentar
    // os inputs
    global $PARAMS;
    $PARAMS=[];
}
function svar($name,$value=false) {
    global $STATEVARS;
    if($value===false) {
        if(isset($STATEVARS[$name]) ) {
            if($STATEVARS[$name]==="GEOVANA_UNSET") {
              return false;
            }
            return $STATEVARS[$name];
        } else {
            return false;
        }  
    } else {
        $STATEVARS[$name]=$value;
        return $value;
    }
}
function unset_svar($name) {
    global $STATEVARS;
    if(is_array($name)) {
        foreach ($name as $n) {
            unset_svar($n);
        }
    } else {
        $STATEVARS[$name]="GEOVANA_UNSET";
    }
    
}


function cookie($name,$value=null,$time=3600) {
    if($value===null) {
        if(isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        } else {
            return false;
        }  
    } else {
        $time=time()+$time;
        setcookie($name,$value,$time);
        $_COOKIE[$name]=$value;
        return $value;
    }
}
function unset_cookie($name) {
    if(is_array($name)) {
        foreach ($name as $n) {
            unset_cookie($n);
        }
    } else {
        unset($_COOKIE[$name]);
    }
    
}

function param_to_svar($param_name) {
    if(is_array($param_name)) {
        foreach ($param_name as $p) {
            param_to_svar($p);
        }
    } else {
        if(param($param_name)) {
             svar($param_name, param($param_name));
        }
    }
    
}
function called($fn_name=false) {
    if(filter_has_var(INPUT_POST, 'fn_name')) {
        if($fn_name) {
            return $fn_name===filter_input(INPUT_POST, 'fn_name');
        } else {
            return true;
        }
    } else {return false;}
}
function received_form($form_name=false) {    
    $received_one_form=filter_has_var(INPUT_POST, 'form_name');
    if(!$received_one_form)  {return false;}
    if($form_name) {
        return ($form_name===filter_input(INPUT_POST, 'form_name',FILTER_SANITIZE_STRING));
    } else {
        return $received_one_form;
    }
}
// iif igual existe no vb.
function iif($condition,$true_part=false,$false_part=false) {
    if($condition) {
        if($true_part) {
            return $true_part;
        } else {
           return $condition; 
        }
    } else {
        return $false_part;
    }
}
function ifset($var) {
    if(isset($var)) {
        return $var;
    } else {
        return false;
    }
}
function ifval($value) {
    if($value) {
        return $value;
    } else {
        return false;
    }
}


function js_redirect($url,$delay=false,$newtab=false){
    if($delay) {
        if($newtab) {
            return SCRIPT()->add("setTimeout(function(){ window.open('$url'); }, $delay);");
        } else {
            return SCRIPT()->add("setTimeout(function(){ window.location.href='$url'; }, $delay);");
        }
    } else {
        if($newtab) {
            return SCRIPT()->add("window.open('$url');");
        } else {
            return SCRIPT()->add("window.location.href='$url';");
        }
    }
}
function unfilter_chars($html) {
    if(is_array($html)) {
        return array_map('unfilter_chars',$html);
    } else {
        return htmlspecialchars_decode($html,ENT_QUOTES);
    }
}
function render_statevars($to_string=false){
    global $STATEVARS;
    $string= "<!--$". base64_encode(json_encode(unfilter_chars($STATEVARS),JSON_OBJECT_AS_ARRAY))."$-->";
    if(!$to_string) {
        echo $string;
    } else {
        return $string;
    }
}
function render_postback($continue=true) {
    global $_GEOVANA_ASSIGNED_CONTROLS,$GEOVANA_CANCEL_POSTBACK;
    $geo_control_id=filter_input(INPUT_POST, "geo_control_id",FILTER_SANITIZE_STRING);
    
    if(called() && !$geo_control_id) {exit;}
    if($geo_control_id) {
        if($GEOVANA_CANCEL_POSTBACK) {
            echo 'NO_POSTBACK';
            exit;
        }
        if(isset($_GEOVANA_ASSIGNED_CONTROLS[$geo_control_id])){
             $control=$_GEOVANA_ASSIGNED_CONTROLS[$geo_control_id];
            if($control instanceof Control) {
                $control->send_content();
                render_statevars();
                exit;
            } else {
                if(!$continue) {
                    send_item("control '$geo_control_id' not found");   
                    render_statevars();
                    exit;
                }
            }
               
        } else {
            if(!$continue) {
                send_item("control '$geo_control_id' not found");   
                render_statevars();
                exit;
            }
        } 
    }
}
function is_rendering_posback() {
    return filter_has_var(INPUT_POST, 'geo_control_id');
}

function text_to_html($text) {
    return nl2br(htmlentities($text));
}
function to_control($anything) {
    if($anything instanceof Control ) {
        return $anything;
    } else {
        return T($anything);
    }
}

function to_html($anything) {
    if(is_array($anything)) {
        $html="";
        foreach($anything as $item) {
            $html.=to_html($item);
        }
        return $html;
    }
    if(is_numeric($anything)) {
        $anything=strval($anything);
    }
    return $anything;
}
function send_item($item) {
     if (is_array($item)) {
        foreach($item as $control) {
            send_item($control);
        }
     }
     elseif($item instanceof Control) {
         $item->send();
     } else if(is_string($item)) {
         echo $item;
     }
 } 
function inside_tag($html,$tag) {
     return "<$tag>$html</$tag>";
 }
function comment($text) {
    return "<!--".$text."-->";
}
function echo_comment($text) {
    send_item("<!--".$text."-->");
}

class Control {
    public $classes=[];
    public $subtags=[];
    public $style=[];
    public $subcontrols=[];
    public $is_void_control=false;
    public $tag="span";
    public $attributes=[];
    public $renderizable=true;
    public function assign($id) {
        global $_GEOVANA_ASSIGNED_CONTROLS;
        $_GEOVANA_ASSIGNED_CONTROLS[$id]=$this;
        return $this;
    }
    public function set_as_void_control() {
        $this->is_void_control=true;
        return $this;
    }
    function add_once($control) {
        if(!$this->contains($control)) {
            $this->add($control);
        }
        return $this;
    }
    function contains($control) {
        return in_array($control,$this->subcontrols);
    }
    public function set_as_normal_control() {
        $this->is_void_control=false;
        return $this;
    }
    public function tag($value=false) {
        if($value) { 
            $this->tag=$value;
            return $this;
        }
        return $this->tag;
    }
    public function id($value=false) {
        if($value){$this->assign($value);}
        return $this->attr('id',$value);
    }
    public function updatetime($time=10000) {
        return $this->attr("data-updatetime",$time);
    }
    public function alt($value=false) {
        return $this->attr('alt',$value);
    }
    public function onload($value=false) {
        return $this->attr('onload',$value);
    }
    public function style($key,$value) {
        if(!$value && !($value==="0")) {
            return $this->style[$key];
        } else {
            $this->style[$key]=$value;
            return $this;
        }
    }
    public function role($value=false) {
        return $this->attr('role',$value);
    }
    public function hidden() {
        return $this->attr('hidden','emptyfield');
    }
    public function attr($key,$value=false) {
        
        if($value===false && !($value==="") ) {
            if(is_numeric($value)) {
                $value=strval($value);
            }
            if(isset($this->attributes[$key])) {
                return $this->attributes[$key];
            } else {
                // Error 
                return false;
            }
        } else {
            $this->attributes[$key]=$value;
            return $this;
        }
    }
    public function renderizable($value) {
        $this->renderizable=$value;
        return $this;
    }
    public function serializeStyle() {
        $css="";
        foreach ($this->style as $key => $value) {
            $css.="$key:$value;";
        }
        return $css;
    }
    public function serializeAttributes() {
        $html="";
        foreach ($this->attributes as $key => $value) {
            if($value!==false) {
              if($value==='emptyfield') {
                  $html.=" $key"; 
              } else { 
                  $html.=" $key=\"$value\"";
              }
            }
        }
        if(count($this->style)>0) {
            $html.=" style=\"".$this->serializeStyle()."\"";
        }
        return $html;
    }
    
    function __construct($tag=false) {
        if($tag) { $this->tag($tag);}
        return $this;
    }
    function __toString() {
        return $this->html();
    }

    public function add($control=false) {
        if($control || $control===0 || $control==="0") {
            if (is_array($control)) {
                foreach($control as $_control) {
                    $this->add($_control);
                }
            } else {
                $this->subcontrols[]=$control;
            }
        }
        return $this;
    }
     public function subtag($key,$value=false) {
        if(is_array($key) && !$value) {
            foreach ($key as $subtag) {
                if(count($subtag)==2) {
                    $this->subtag($subtag[0],$subtag[1]);
                }
            }
            return $this;
        }
        if($value) { 
            $this->subtags[$key]=$value;
            return $this;
        }
        return $this->subtags[$key];
    }
    
    public function class($value) {

        if(!in_array($value, $this->classes)) { 
            $this->classes[]=$value;
        }
        return $this;
    }
    
    public function debug_subcontrols() { 
        echo "<pre>";
        echo print_r($this->subcontrols);
        echo "</pre>";
        return $this;
    }
    public function debug_subtags() {
        echo print_r($this->subtags); echo "<pre>";
        echo print_r($this);
        echo "</pre>";
        return $this;
    }
    public function debug_attributes() {
        echo "<pre>";
        echo print_r($this->attributes);
        echo "</pre>";
        return $this;
    }
    public function debug() {
        echo "<pre>";
        echo print_r($this);
        echo "</pre>";
        return $this;
    }
    public function serializeSubtags() {
        $html="";
        foreach ($this->subtags as $key => $value) {
            $html.=inside_tag($value,$key);
        }           
        return $html;
    }
    
    public function serializeClasses() {
        $html="";
        if(count($this->classes)>0) {
            $html.= " class=\"".implode(' ',  $this->classes)."\"";
        }
        return $html;
    }
    public function send_content($inner="") {
        send_item($this->serializeSubtags());
        send_item($this->subcontrols);
        send_item($inner);
    }
    public function send($inner="") {
        if(!$this->renderizable) { return $this;}
        send_item("<$this->tag".$this->serializeAttributes().$this->serializeClasses().">");
        if(!$this->is_void_control) {
            $this->send_content($inner);
            send_item("</$this->tag>");
        }
        return $this;
    }
    public function  content_html($inner="") {
        return $this->serializeSubtags() .
                to_html($this->subcontrols).
                to_html($inner);
    }

    public function html($inner="") {
        if(!$this->renderizable) { return "";}
        if($this->is_void_control) {
            return "<$this->tag".$this->serializeAttributes().$this->serializeClasses().">";
        } else {
            return "<$this->tag".$this->serializeAttributes().$this->serializeClasses().">".
                    $this->content_html($inner).
                    "</$this->tag>";
        }
    }
    function response($control_id="div_postback") {
        return $this->attr("data-response",$control_id);
    }
    function params($params) {
        return $this->attr("data-params",base64_encode(json_encode($params)));
    }
    function dismiss($value) {
        return $this->attr('data-dismiss', $value);
    }
    function onclick($code) {
        return $this->attr('onclick', $code);
    }
    function when_click($fn_name,$reponse=false,$params=false) {
       if($reponse) { $this->response($reponse);}
       if($params) { $this->params($params);}
       return $this->attr("data-onclick",$fn_name);
    }
    
    function when_change($fn_name,$reponse=false,$params=false) {
       if($reponse) { $this->response($reponse);}
       if($params) { $this->params($params);}
       return $this->attr("data-onchange",$fn_name);
    }


    function call($fn_name,$reponse="div_postback",$params=false) {
        if($reponse) { $this->response($reponse);}
        if($params) { $this->params($params);}
         return $this->attr("data-onclick",$fn_name);
     }
 
     function dismiss_alert() {
        return $this->dismiss("alert"); 
     }
     function dismiss_modal() {
        return $this->dismiss("modal"); 
     }
    
}

class Div extends Control {
    function thumbnail() {
        return $this->class("img-thumbnail");
    }
    function border($size=false) {
        if($size) {
            $this->class("border-$size");
        } else {
            $this->class("border");
        }
        return $this;
    }
    function active() {
        return $this->class("active");
    }
    function col($value) {
        return $this->class("col-$value");
    }
    function col_sm($value) {
        return $this->class("col-sm-$value");
    }
    function col_md($value) {
        return $this->class("col-md-$value");
    }
    function col_lg($value) {
        return $this->class("col-lg-$value");
    }
    function col_xl($value) {
        return $this->class("col-xl-$value");
    }
    function fs($s) {
        if(is_numeric($s)) {$s.="px";}
        if($s) {$this->style("font-size",$s);}
        return $this;
    }
    function collapsable($multiple=false) {
        $this->class("collapse");
        if($multiple) {$this->class("multi-collapse");}
        return $this;
    }
    function width($value) {
        if(is_numeric($value)) {
            $value=$value.'px';
        }
        return $this->style('width', $value);
    }
    function height($value) {
        if(is_numeric($value)) {
            $value=$value.'px';
        }
        return $this->style('height', $value);
    }
    function expand_collapse() {
        return $this->attr('aria-expanded','true');
    }
    function collapse_targets($target_ids,$expand=false) {
        $this->attr('data-toggle','collapse');
        if($expand) {$this->expand_collapse();}
        if(is_array($target_ids)) {
                $this->attr('data-target',".multi-collapse")
                     ->attr("aria-controls", join(" ",$target_ids));                      
        } else {
            if(is_string($target_ids)) {
                if($target_ids[0]!='#' && $target_ids[0]!=".") {
                    $target_ids = "#$target_ids";
                }
                $this->attr('data-target',$target_ids);
            } 
        }
        return $this;
    }
    function rounded() {
        return $this->class("rounded");
    }
    function circle() {
        return $this->class("rounded-circle");
    }
    function s($w=false,$h=false) {
        if(is_numeric($w)) {$w.="px";}
        if(is_numeric($h)) {$h.="px";}
        if($w) {$this->style("width",$w);}
        if($h) {$this->style("height",$h);}
        return $this;
    }
    function bg_image($url,$position="center",$size="cover",$repeat="no-repeat",$fallback="/geovana/res/noimage.png") {
        $img="url('".$url."')";
        if($fallback) {
            $img.=",url('$fallback')";
        }
        $this->style("background-image", $img);
        $this->style("background-position", $position);
        $this->style("background-size", $size);
        $this->style("background-repeat", $repeat);

        return $this;
    }
    function blur($blur) {
        if(is_numeric($blur)) {$blur=$blur."px";}
          $this->style("filter","blur($blur)")
                ->style("-webkit-filter","blur($blur)");
        return $this;
    }
    function hidden_mobile($display="block") {
        return $this->class("d-none")->class("d-md-$display");
    }
    function hidden_desktop($display="block") {
        return $this->class("d-$display")->class("d-md-none");
    }
    function pos($top=false,$left=false,$bottom=false,$right=false) {
        
        if(is_numeric($top)) {$top.="px";}
        if(is_numeric($left)) {$left.="px";}
        if(is_numeric($bottom)) {$bottom.="px";}
        if(is_numeric($right)) {$right.="px";}
        if($top) {$this->style("top",$top);}
        if($left) {$this->style("left",$left);}
        if($bottom) {$this->style("bottom",$bottom);}
        if($right) {$this->style("right",$right);}
        return $this;
    }
    function bg($suffix) {
        return $this->class("bg-$suffix");
    }
    function bg_color($color=false) {
        return $this->style("background-color",$color." !important");
    }
    function hover_shadow() {
        return $this->class("hover-shadow");
    }
    function hover_shadow_sm() {
        return $this->class("hover-shadow-sm");
    }
    function hover_shadow_md() {
        return $this->class("hover-shadow-md");
    }
    function static($top=false,$left=false,$bottom=false,$right=false) { 
        $this->pos($top,$left,$bottom,$right);
        return $this->class("position-static");
    }
    function relative($top=false,$left=false,$bottom=false,$right=false) {
        $this->pos($top,$left,$bottom,$right);
        return $this->class("position-relative");
    }
    function absolute($top=false,$left=false,$bottom=false,$right=false) {
        $this->pos($top,$left,$bottom,$right); 
        return $this->class("position-absolute");
    }
    function fixed($top=false,$left=false,$bottom=false,$right=false) {
        $this->pos($top,$left,$bottom,$right);
        return $this->class("position-fixed");
    }
    function z_index($value=false) {
        return $this->style("z-index",$value);
    }
    function z_index_top() {
        return $this->style("z-index", "10000");
    }
    function clearfix() {
        return $this->class("clearfix");
    }
    function border_primary($size=false) {
        $this->border($size);
        return $this->border("primary");
    }
    function border_secondary($size=false) {
        $this->border($size);
        return $this->border("secondary");
    }
    function border_success($size=false) {
        $this->border($size);
        return $this->border("success");
    }
    function border_danger($size=false) {
        $this->border($size);
        return $this->border("danger");
    }
    function border_info($size=false) {
        $this->border($size);
        return $this->border("info");
    }
    function border_light($size=false) {
        $this->border($size);
        return $this->border("light");
    }
    function border_dark($size=false) {
        $this->border($size);
        return $this->border("dark");
    }
    function border_white($size=false) {
        $this->border($size);
        return $this->border("white");
    }
    function float_left()         {return $this->class("float-left");}
    function float_right()         {return $this->class("float-right");}
    function pull_left()         {return $this->class("pull-left");}
    function pull_right()         {return $this->class("pull-right");}
    function scroll() { return $this->style("overflow", "scroll");}
    function shadow() {
        return $this->class("shadow");
    }
    function shadow_sm() {
        return $this->class("shadow-sm");
    }
    function shadow_lg() {
        return $this->class("shadow-lg");
    }
    function scroll_x($max_width) { 
        if($max_width) { 
            $this->style("max-width",$max_width."px");
        }
        return $this->style("overflow-x", "scroll");
        
    }
    function scroll_y($max_height=false) {
        if($max_height) { 
            $this->style("max-height",$max_height."px");
        }
        return $this->style("overflow-y", "scroll");
    }
    function autoscroll_down() {
        return $this->class("autoscroll-down");
    }
    function bold()         {return $this->class("font-weight-bold");}
    function bolder()         {return $this->class("font-weight-bolder");}
    function light()         {return $this->class("font-weight-light");}
    function lighter()         {return $this->class("font-weight-lighter");}
    function normal()         {return $this->class("font-weight-normal");}
    function small()         {return $this->class("small");}
    function left()          {return $this->class("text-left");}
    function right()          {return $this->class("text-right");}
    function break()          {return $this->class("text-break");}
    function lead()           {return $this->class("lead");}
    function decoration_none(){return $this->class("text-decoration-none");}
    function muted()          {return $this->class("text-muted");}
    function primary()          {return $this->class("text-primary");}
    function success()          {return $this->class("text-success");}
    function info()          {return $this->class("text-info");}
    function warning()          {return $this->class("text-warning");}
    function danger()          {return $this->class("text-danger");}
    function secondary()          {return $this->class("text-secondary");}
    function white()          {return $this->class("text-white");}
    function dark()          {return $this->class("text-dark");}
    function w_100(){
        return $this->class("w-100");
    }  
    function h_100(){
        return $this->class("h-100");
    }
    function text_center() {return $this->class("text-center");}
    function text_right() {return $this->class("text-right");}
    function text_left() {return $this->class("text-left");}
    function justify_content_center() {return $this->class("justify-content-center");}
    function center() {
        return $this->text_center()->class("align-items-center justify-content-center");
    }
    //function text_light()          {return $this->class("text-light");}
    function bg_primary()          {return $this->class("bg-primary");}
    function bg_success()          {return $this->class("bg-success");}
    function bg_info()          {return $this->class("bg-info");}
    function bg_warning()          {return $this->class("bg-warning");}
    function bg_danger()          {return $this->class("bg-danger");}
    function bg_secondary()          {return $this->class("bg-secondary");}
    function bg_dark()          {return $this->class("bg-dark")->class("text-light");}
    function bg_light()          {return $this->class("bg-light");}
    
    function bg_white()          {return $this->class("bg-white");}
    function d_block() {
        return $this->class("d-block");
    } 
    function d_flex() {
        return $this->class("d-flex");
    }
    function d_inline() {
        return $this->class("d-inline");
    } 
    function d_inline_block() {
        return $this->class("d-inline-block");
    } 
    function d_none() {
        return $this->class("d-none");
    }
    function fade()          {return $this->class("fade");}
    function show()          {return $this->class("show");}
    function italic()         {return $this->class("font-italic");}
    function invisible()         {return $this->class("invisible");}
    function hidden()         {return $this->class("hidden");}
    function v($device,$visible=true) {
        if($visible) {
            $this->class("d-$device-none");
        } else {
            $this->class("d-$device-block");
        }
        return $this;
    }
    function vis($xs=true,$sm=true,$md=true,$lg=true,$xl=true) {
        return $this->v("",$xs)->v("sm", $sm)
                    ->v("md",$md)->v("lg",$lg)->v("xl",$xl);
    } 
    function badge()          {return $this->class("badge")->class("badge-secondary");}

    function justify(){return $this->class("text-justify");}
    function monospace(){return $this->class("text-monospace");}
    function nowrap(){return $this->class("text-nowrap");}
    function lowercase(){return $this->class("text-lowercase");}
    function uppercase(){return $this->class("text-uppercase");}
    function reset(){return $this->class("text-reset");}
    function capitalize(){return $this->class("text-capitalize");}
    function initialism(){return $this->class("initialism");}
    function  card_columns() {
        return $this->class("card-columns");
    }
    
    function fixed_top() {
        return $this->class("fixed-top");
    }
    function fixed_bottom() {
        return $this->class("fixed-bottom");
    }
    function sticky() {
        return $this->sticky_top();
    }
    function sticky_top() {
        return $this->class("sticky-top");
    }
    function sticky_bottom() {
        return $this->class("sticky-bottom");
    }
    function __construct($content=false) {
       parent::__construct("div")->add($content);
    }
    function color($color) {
        return $this->style('color',$color);
    }
    function tooltip($text,$position=false) {
        $this->attr("data-togle", "tooltip")
                ->attr("title",$text);
        if($position) {
            $this->attr("data-placement", $position);
        }
        return $this;
    }
   
    public function pt($num) {
        return $this->class("pt-$num");
    }
    public function  opacity($v) {
        return $this->style('opacity', $v);
    }

    public function pb($num="auto") {
        return $this->class("pb-$num");
    }
    public function pl($num="auto") {
        return $this->class("pl-$num");
    }
    public function pr($num="auto") {
        return $this->class("pr-$num");
    }
    public function px($num="auto") {
        return $this->class("px-$num");
    }
    public function py($num="auto") {
        return $this->class("py-$num");
    }
    public function mt($num="auto") {
        return $this->class("mt-$num");
    }
    public function mb($num="auto") {
        return $this->class("mb-$num");
    }
    public function ml($num="auto") {
        return $this->class("ml-$num");
    }
    public function mr($num="auto") {
        return $this->class("mr-$num");
    }
    public function mx($num="auto") {
        return $this->class("mx-$num");
    }
    public function my($num="auto") {
        return $this->class("my-$num");
    }
    public function m($num="auto") {
        return $this->class("m-$num");
    }
    public function p($num="auto") {
        return $this->class("p-$num");
    }
}

class Html extends Control {
    function __construct() {
       parent::__construct("html");
       
    }
    public function doctype() {
            return "<!DOCTYPE html>";
    }
    public function send($inner="") {
        send_item($this->doctype());
        parent::send($inner);
    }
    public  function pt_br() {
        return $this->lang("pt-br");
    }
    public  function en_us() {
        return $this->lang("pt-br");
    }
    public function lang($value=false) {
       return $this->attr("lang",$value);
   }
   public function html($inner="") {
       return $this->doctype()
       .parent::html($inner);
   }
   
}

class Body extends Div {
   function __construct() {
       parent::__construct();
       $this->tag("body");
   }
}

class Head extends Control {
   function title($value=false) {
       return $this->subtag('title', $value);
   }
   function __construct() {
       parent::__construct("head");
   }
}

class LinkedSource extends Div {
    public function __construct($tag = false){
        $this->set_as_void_control();
        parent::__construct();
        $this->tag($tag);
    }
    public function src($value=false) {
       if($value) {
           $mime=get_mime_type($value);
           if($mime) {$this->type($mime); }
       }
       return $this->attr('src', $value);
   }
   public function url($value=false) {
       return $this->src($value);
   }
   public function type($value=false) {
       return $this->attr("type",$value);
   }
}

class Script extends LinkedSource {
   function __construct($url=false) {
       parent::__construct("script");
       if($url) {$this->src($url);}
       $this->type("application/javascript");
       $this->set_as_normal_control();
   }
}

function SCRIPT($url=false) {return (new Script($url));}

class Link extends LinkedSource {
   function css() {
       return $this->attr("rel","stylesheet");
   }
   function icon() {
        return $this->attr("rel","icon");
   }
   function license() {
      return $this->attr("rel","license");
   }
   function crossorigin($value=false) {
       return $this->attr("crossorigin", $value);
   }
   function url($value=false) {
       return $this->attr("href", $value);
   }
   function anonymous() {
       return $this->crossorigin("anonymous");
   }
   function href($value=false) {
       return $this->attr("href", $value);
   }
   function hreflang($value=false) {
       return $this->attr("hreflang", $value);
   }
   function media($value=false) {
       return $this->attr("media", $value);
   }
   function __construct($url=false) {
       if($url) { $this->url($url);}
       parent::__construct("link");
   }
}
function HREFLINK($url) {
    return (new Link($url));
}
function CSS($url) {return HREFLINK($url)->css();}
class Meta extends Control {
    public function __construct($name=false,$content=false) {
        $this->meta($name,$content);
        $this->set_as_void_control();
        parent::__construct("meta");
    }
    public function property($value) {
        if($value) {$this->attr("property",$value);}
        return $this;
    }
    public function content($value) {
        if($value) {$this->attr("content",$value);}
        return $this;
    }
    public function name($value) {
        if($value) {$this->attr("name",$value);}
        return $this;
    }
    public function meta($name=false,$content=false) {
        if($name) {$this->name($name);}
        if($content) {$this->content($content);}
        return $this;
    }
}
function META() {
    return (new Meta);
}
class Page extends Html {
    public $head;
    public $body;
    public $allow_postback=true;
    public $scripts=[];
    public $using_overflow_x_hidden=false;
    public $div_overflow_viewport;
    public function meta($name,$content) {
        $this->head->add(new Meta($name,$content));
        return $this;
    }
    public function charset($charset_name) {
        $this->head->add((new Meta)->attr("charset",$charset_name));
        return $this;
    }
    public function author($content) {
        return $this->meta("author",$content);
    }
    public function description($content) {
        return $this->meta("description",$content);
    }
    public function keywords($content) {
        return $this->meta("keywords",$content);
    }
    public function icon($url) {
        $this->head->add(HREFLINK($url)->icon());
        return $this;
    }
    public function default_icon() {
        // experimental function. do not use
        $this->icon("/favicon.png");
        return $this;
    }
    public function viewport($content) {
        return $this->meta("viewport",$content);
    }
    public function viewport_zoom($zoom) {
        return $this->viewport("width=device-width, initial-scale=$zoom, shrink-to-fit=no");
    }
    public function default_viewport() {
        return $this->viewport("width=device-width, initial-scale=1, shrink-to-fit=no");
    }
    public function generator($content) {
        return $this->meta("generator",$content);
    }
    public function copyright($content) {
        return $this->meta("copyright",$content);
    }
    public function robots($content) {
        return $this->meta("robots",$content);
    }
    public function googlebot($content) {
        return $this->meta("googlebot",$content);
    }
    public function rating($content) {
        return $this->meta("rating",$content);
    }
    public function utf8() {
        return $this->charset("UTF-8");
    }
    public function css($url) {
        $this->head->add(CSS($url)->anonymous());
        return $this;
    }
    public function bootstrap_css() {
        global $BOOTSTRAP_CSS_URL,$BOOTSTRAP_THEME;
        if($BOOTSTRAP_THEME) {
            $this->css("https://bootswatch.com/4/".$BOOTSTRAP_THEME."/bootstrap.css");
        } else {
            $this->css($BOOTSTRAP_CSS_URL);
        }
        
        return $this;
    }
    public function script($url) {
        if($url instanceof Control) {
            $this->scripts[]=$url;
        } else {
            $this->scripts[]=SCRIPT($url);
        }
        return $this;
    }
    public function bootstrap() {
        return $this->bootstrap_css()
                    ->font_awesome()
                    ->bootstrap_js()
                    ->default_viewport();
    }
    public function configure() {
        $this->body->add(DIV()->id("div_postback"));
        return $this->bootstrap()
                ->lib_toggle()
                ->lib_noty()
                ->geovana_js()
                ->generator("GEOVANA PHP LIBRARY");
    }
    public function lib_toggle() {
        global $BT_TOGGLE_CSS_URL,$BT_TOGGLE_JS_URL;
        return $this->css($BT_TOGGLE_CSS_URL)->script($BT_TOGGLE_JS_URL);
    }
    public function geovana_js() {
        global $BASE64_JS_URL,$GEOVANA_JS_URL;
        return $this->script($BASE64_JS_URL)->script($GEOVANA_JS_URL);
    }
    public function font_awesome() {
        global $FONT_AWESOME_URL;
        return $this->css($FONT_AWESOME_URL);
    }
    public function lib_noty() {
        global $NOTY_CSS_URL,$NOTY_JS_URL,$NOTY_THEME_CSS_URL;
        return $this->css($NOTY_CSS_URL)->script($NOTY_JS_URL)
                ->css($NOTY_THEME_CSS_URL);
    }
    public function jquery() {
        global $JQUERY_URL;
        return $this->script($JQUERY_URL);
    }
    public function bootstrap_js() {
        global $BOOTSTRAP_JS_URL,$POPPER_URL,$JQUERY_MASK_URL;
        return $this->jquery()
                    ->script($JQUERY_MASK_URL)
                    ->script($POPPER_URL)
                    ->script($BOOTSTRAP_JS_URL);
    }
    public function  overflow_x_hidden() {
        $this->body->style('overflow-x','hidden');
        $this->using_overflow_x_hidden=true;
        $this->div_overflow_viewport->style('overflow-x','hidden');
        return $this;
    }
    public function title($value=false) {
        if($value) {
          $this->head->title($value);
          return $this;
        }
        return $this->head->title();
    }
    public function subtitle($subtitle) {
        return $this->title($this->title()." - ".$subtitle);
    }
    public function add($value=false) {
        if($this->using_overflow_x_hidden) {
            $this->div_overflow_viewport->add($value);
        } else {
            $this->body->add($value);
        }
        return $this;
    }
    public function add_head($value) {
        $this->head->add($value);
        return $this;
    }
    public function __construct() {
        parent::__construct();
        $this->head=new Head();
        $this->body=new Body();
        $this->div_overflow_viewport=DIV();
        $this->title("Geovana Page");
        $this->body->add($this->div_overflow_viewport);
        parent::add($this->head);
        parent::add($this->body);
    }
    public function send($inner = "",$postback=true) {
            foreach ($this->scripts as $script) {
                $this->body->add_once($script);
            }
            $this->body->add(render_statevars(true));
            if(!is_rendering_posback()) {
                parent::send($inner);
            } else {
                render_postback(false);
            }
    }
    
}

function PAGE($title=false,$utf8=true,$pt_br=true) {
    $page=(new Page)->title($title)
            ->generator("GEOVANA FRAMEWORK");
    if($utf8) { $page->utf8();}
    if($pt_br) { $page->pt_br();}
    return $page;
}

class Span extends Div {
   function __construct($content=false) {
       parent::__construct($content);
       $this->tag("span");
   }  
    function head($num="1") {$this->class("h".$num."");return $this;}
    function display($num="1") {$this->class("display-$num");return $this;}
    
    function h1() {return $this->head("1");}
    function h2() {return $this->head("2");}
    function h3() {return $this->head("3");}
    function h4() {return $this->head("4");}
    function h5() {return $this->head("5");}
    function h6() {return $this->head("6");}
    function h7() {return $this->head("7");}
    
    function d1() {return $this->display("1");}
    function d2() {return $this->display("2");}
    function d3() {return $this->display("3");}
    function d4() {return $this->display("4");}
    
}
function SPAN($items=false) {
    return (new Span($items));
}
class HiperLink extends Div {
   function __construct($url="#",$target="_self") {
       
       if($url) {$this->url($url);}
       if($target) {$this->target($target);}
       
       parent::__construct();
       $this->tag("a");
    }
   function url($value=false) {
       return $this->href($value);
   }
   function btn() {
       return $this->class("btn");
   }
   
   function lg() {
       return $this->class("btn-lg");
   }
   function sm() {
       return $this->class("btn-sm");
   }
   function alert_link() {
       return $this->class("alert-link");
   }
   function block() {
       return $this->class("btn btn-block");
   }
   function default() {
       return $this->class("btn btn-default");
   }
   function primary() {
       return $this->class("btn btn-primary");
   }
   function secondary() {
       return $this->class("btn btn-secondary");
   }
   function success() {
       return $this->class("btn btn-success");
   }
   function danger() {
       return $this->class("btn btn-danger");
   }
   function warning() {
       return $this->class("btn btn-warning");
   }
   function info() {
       return $this->class("btn btn-info");
   }

   function active() {
       return $this->class("active");
   }
   function disabled() {
       return $this->class("disabled");
   }
   function rolebutton() {
       return $this->attr("role","button");
   }
   function href($value=false) {
       return $this->attr('href', $value);
   }
   function target($value=false) {
       return $this->attr('target', $value);
   }
   function download() {
       return $this->attr('download', 'emptyfield');
   }
   function newtab() {
       return $this->_blank();
   }
   function _blank() {
       return $this->target("_blank");
   }  
   function _parent() {
       return $this->target("_parent");
   }  
   function _top() {
       return $this->target("_top");
   }
   function _self() {
       return $this->target("_self");
   }
   function card_link() {
       return $this->class("card-link");
   }
}
function A($content=false,$url=false) {
    return (new HiperLink($url))->add($content);
}
function HIPERLINK($url,$content=false) {
    return A($url,$content);
}
class RowContainer extends Div {
    public $rows=[];
    public function add_row($row) {
        $this->rows[]=$row;
        return $this;   
    }
   public function add_rows($rows) {
       if(is_array($rows)) {
           foreach ($rows as $arr_row) {
               $this->add_row($arr_row); 
           }
       } else {
           $this->add_row($rows);
       }
       return $this;
   }
   public function serialize_rows() {
        $html="";
        foreach ($this->rows as $row) {
            $html.= $row;
        }
        return $html;
   }
   public function clear() {
       $this->rows=[];
       return $this;
   }
   public function html($inner = "") {
       return parent::html($this->serialize_rows() .$inner);
   }
   public function send($inner = "") {
       return parent::send([$this->rows,$inner]);
   }
}


class Img extends LinkedSource {
    function width($value=false) {
        return $this->attr("width", $value);
    }
    function height($value=false) {
        return $this->attr("height", $value);
    }
    function resize($width,$height) {
     return $this->size($width,$height);
    }
    function fluid() {
        return $this->class("img-fluid");
    }

    function responsive() {
        return $this->class("img-responsive");
    }
    function remove_all_borders() {
        $this->style('border-style','none');
        $this->style('outline','none');
        return $this;
    }
    function in_background($width=false,$height=false,$fallback="/geovana/res/noimage.png") {
        $img="url('".$this->url()."')";
        if($fallback) {
            $img.=",url('$fallback')";
        }
        $this->style("background-image", $img);
        $this->style("background-position", "center");
        $this->style("background-repeat", "no-repeat");
        $this->style("background-size", "cover");
        $this->src("data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAI");
        if($width) {
            if(is_integer($width)) $width=$width."px";
            $this->style("width", $width);
        }
        if($height) {
            if(is_integer($height)) $height=$height."px";
            $this->style("height", $height);
        }
       unset($this->attributes["src"]);
        unset($this->attributes["type"]);
        return $this;
    }
    function size($width,$height) {
        if($width) { $this->width($width);}
        if($height) { $this->height($height);}
        return $this;
    }
    function __construct($url) {
        $this->src($url);
        $this->attr('onerror',"this.setAttribute('data-url',this.src);this.src='/geovana/res/noimage.png';");
       parent::__construct("img");
   } 
}
function IMAGE($url,$width=false,$height=false) {
    return (new Img($url))
                ->resize($width,$height);
}
function IMG($url,$width=false,$height=false) {
    return (new Img($url))
                ->resize($width,$height);
}
class ProgressBar extends Div {
    public $meter,$min,$max,$value;
    function __construct($min=0,$max=100,$value=100)  {
        parent::__construct();
        $this->meter=DIV()->class("progress-bar");
        $this->add($this->meter);
        $this->min($min)->max($max)->value($value)->class("progress");
    }
    function value($value=false) {
           $this->value=$value;
           $percent=((floatval($value)-$this->min)/(($this->max-$this->min)/100.0));
           $this->meter->style("width","$percent%");
        $this->meter->attr("aria-valuenow", $value);
        return $this;
    }
    function max($value=false) {
        $this->max=floatval($value);
        $this->meter->attr("aria-valuemax", $value);
        return $this;
    }
    function min($value=false) {
        $this->min=floatval($value);
        $this->meter->attr("aria-valuemin", $value);
        return $this;
    }
    function stripped() {
        $this->meter->class("progress-bar-striped");
        return $this;
    }
    function animated() {
        $this->stripped();
        $this->meter->class("progress-bar-animated");
        return $this;
    }
    function loading() {
        return $this->stripped()->animated();
    }
}
function PROGRESSBAR($min=0,$max=100,$value=100) {
   return (new ProgressBar($min,$max,$value));
}


class Nav extends Control {
   function __construct() {
       parent::__construct("nav");
   }
}
function DIV($content=false) {
  return (new Div($content));
}

class Footer extends Div {
    function __construct() {
       parent::__construct("footer");
   }
}
class Header extends Div {
    function __construct() {
       parent::__construct("header");
   }
}
class Main extends Div {
    function __construct() {
       parent::__construct("main");
   }
}
function PAGE_HEADER($content) {
  return (new Header($content));
}
function PAGE_MAIN($content) {
  return (new Main($content));
}
function PAGE_FOOTER($content) {
  return (new Footer($content));
}

