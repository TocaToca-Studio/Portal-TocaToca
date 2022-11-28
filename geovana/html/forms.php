<?php

require_once __DIR__.'/base.php';

//require_once __DIR__.'/mimetypes.php';

class Form extends Div {
   function __construct($action=false,$post=false) {
       if($action) {$this->action($action);}
       if($post) {$this->post();}
       else {$this->method("get");}
       parent::__construct();
       $this->tag("form");
   }
   function sendserver($fn_name,$control_id="div_postback") {
       if($control_id) { $this->formresponse($control_id);}
       return $this->formfunction($fn_name);
   }
   function formfunction($fn_name=false) {
       return $this->attr("data-formfunction",$fn_name);
   }
   function formresponse($control_id="div_postback") {
       return $this->attr("data-formresponse",$control_id);
   }
   function response($control_id="div_postback") {
       return $this->formresponse($control_id);
   }
   function url($value=false) {
       return $this->action($value);
   }
   function action($value=false) {
       return $this->attr('action', $value);
   }
   function target($value=false) {
       return $this->attr('target', $value);
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
   function enctype($value=false) {
       return $this->attr('enctype', $value);
   }
   function uploadfile() {
       return $this->form_data();
   }
   function form_data() {
       $this->post();
       return $this->enctype("multipart/form-data");
   }
   function form_inline() {
       return $this->class("form-inline");
   }
   function text_plain() {
       return $this->enctype("text/plain");
   }
   function method($value=false) {
       return $this->attr('method', $value);
   }
   function post() {
       return $this->method("post");
   }
   function get() {
       return $this->method("get");
   }
   function accept_charset($value=false) {
       return $this->attr('accept_charset', $value);
   }
   function novalidate() {
       return $this->attr('novalidate', "emptyfield");
   }
}
class Fieldset extends Div {
    public function __construct($content = false) {
        parent::__construct($content);
        $this->tag("fieldset");
    }
}
function FIELDSET($items) {
    return (new Fieldset($items));
}


class Legend extends Control {
      function __construct() {
       parent::__construct("legend");
   }
}
class Label extends Div {
     function __construct($text=false,$dest=false) {
        if($dest) {

            $this->for_id($dest);
            
        }
       parent::__construct($text);
       $this->tag("label");
   }
   
   public function for_id($value=false) {
       return $this->attr('for', $value);
   }
}

class FormInput extends Div {
    function __construct($type=false) {
        if($type) {$this->type($type);}
        parent::__construct();
        $this->tag("input");
    }
    function form_control() {
        return $this->class("form-control");
    }
    function type($value=false) {
        return $this->attr('type', $value);
    }
    function autopostback() {
        return $this->class("autopostback");
    }
    function disabled($value=true) {
        if($value) {
            $this->attr('disabled', 'emptyfield');
        }
        return $this;
    }
    function autofocus() {
        return $this->attr('autofocus', 'emptyfield');
    }

   function newtab() {
       return $this->_blank();
   }
   function _blank() {
       return $this->formtarget("_blank");
   }  
   function _parent() {
       return $this->formtarget("_parent");
   }  
   function _top() {
       return $this->formtarget("_top");
   }
   function _self() {
       return $this->formtarget("_self");
   }
    function formnovalidate() {
        return $this->attr('formnovalidate', 'emptyfield');
    }
    function formtarget($value=false) {
       return $this->attr('formtarget', $value);
   }
    function formaction($value=false) {
        return $this->attr('formaction', $value);
    }
    function formenctype($value=false) {
        return $this->attr('formenctype', $value);
    }
      function formmethod($value=false) {
       return $this->attr('formmethod', $value);
   }
    function name($value=false,$load_from_params=true) {
        if($load_from_params) {
            if(param($value)!==false) {
                $this->value(param($value));
            }
        }
        return $this->attr('name', $value);
    }
    function value($value) {
        $this->attributes['value']=$value;
        return $this;
    }
    function post() {
        return $this->formmethod("post");
    }
    function get() {
        return $this->formmethod("get");
    }
    function required() {
       return $this->attr('required', 'emptyfield');
    }
}

class TextInputBase extends FormInput {
    public function __construct($type = false) {
        parent::__construct($type);
        $this->form_control();
    }
    function mask($mask) {
        return $this->attr("data-mask",$mask);
    }
    function inputmode($value=false) {
       return $this->attr('inputmode', $value);
    }
    function maxlength($value=false) {
       return $this->attr('maxlength', $value);
    }
    function minlength($value=false) {
       return $this->attr('minlength', $value);
    }
    function size($value=false) {
       return $this->attr('size', $value);
    }
    function placeholder($value=false,$tooltip=true) {
       if($tooltip) {$this->tooltip($value);}
       return $this->attr('placeholder', $value);
    }
    function pattern($value=false) {
       return $this->attr('pattern', $value);
    }
    function readonly() {
       return $this->attr('readonly', 'emptyfield');
    }
    function spellcheck() {
       return $this->attr('spellcheck', 'true');
    }
}
class TextInput extends TextInputBase {
    function __construct() {
        parent::__construct("text");
    }
    function password() {
        return $this->type("password");
    }
    function email() {
        return $this->type("email");
    }
    function url() {
        return $this->type("url");
    } 
    function search() {
        return $this->type("search");
    } 
    function telephone() {
        return $this->type("tel");
    }
    function autosave($value=false) {
        if($value) {
            return $this->attr('autosave', $value);
        }
       return $this->attr('autosave', $this->attr('name'));
    }
}
function TEXTINPUT($placeholder=false) {
    $inp=(new TextInput);
    if($placeholder) {$inp->placeholder($placeholder);}
    return $inp;
}
function SEARCHINPUT($placeholder=false,$text=false) {
    $control= TEXTINPUT($placeholder)->search();

    if($text) { $control->value($text);}
    return $control;
}
class InputGroup extends Div {
    function __construct($prepend=false,$input=false,$append=false){
        parent::__construct();
        $this->class("input-group");
        if($prepend) {
          $this->prepend($prepend);
        }
        if($input) { $this->add($input);}
        if($append) {
          $this->append($append);
        }
        return $this;
    }
    function prepend($content) {
        $add=$content;
        if($content instanceof Icon) {
            
        }
        if(!($content instanceof FormInput)) {
            $add=SPAN($add)->class("input-group-text");
        } 
        $this->add(DIV($add)
             ->class("input-group-prepend"));
             return $this;
    }
    function input($input) {
        $this->add($input);
        return $this;
    }
    function append($content) {
        $add=$content;
        if(is_string($add)) {
            $add= SPAN($add)->class("input-group-text");
        }
        $this->add(DIV($add)
             ->class("input-group-append"));
             return $this;
    }
}
function INPUTGROUP($prepend=false,$input=false,$append=false) {
    $input_group=new InputGroup($prepend,$input,$append);
    return $input_group;
}

class NumberInput extends TextInputBase {
    function __construct() {
        parent::__construct("number");
    }
    function range($min=false,$max=false) {
        if($min) {$this->min($min);}
        if($max) {$this->max($max);}
        $this->tag("range");
        return $this;
    }
    function min($value=false) {
       return $this->attr('min', $value);
    } 
    function max($value=false) {
       return $this->attr('max', $value);
    }
    function step($value=false) {
       return $this->attr('step', $value);
    }
}
function NUMBERINPUT($placeholder=false) {
    $inp=(new NumberInput);
    if($placeholder) {$inp->placeholder($placeholder);}
    return $inp;
}
class CheckInputBase extends FormInput {
    function checked($value=true) {
       if($value) {$this->attr('checked', 'emptyfield');};
       return $this;
    }
    function name($name=false,$nothing=false) {
        return $this->attr('name', $name);
    }
    function value($value=false,$load_from_params=true) {
        $return_value=parent::value($value);
        if($load_from_params) {
            if(param($this->attr('name'))==$value) {
                $this->checked(true);
            }     
            if(param('value')==$value) {
                $this->checked(true);
            }         
        }
        return $return_value;
    }
}

class CheckBox extends CheckInputBase {
    public function __construct() {
        parent::__construct();
        $this->type('checkbox');
    }
}
function CHECKBOX() {
    return (new CheckBox());
}

class ToggleButton extends Checkbox {
    public function __construct() {
        parent::__construct();
        $this->on_color("primary")->off_color("secondary");
        $this->attr('data-toggle', 'toggle');
    }
    function content($on,$off) {
        $this->on_content($on)->off_content($off);        
        return $this;
    }
    function off_content($c) {
        $this->attr('data-off',str_replace('"',"'",$c));
        return $this;
    }
    function on_content($c) {
        $this->attr('data-on', str_replace('"',"'",$c));
        return $this;
    }
    function on_color($bootstrap_color) {
        return $this->attr('data-onstyle',$bootstrap_color);
    }
    function off_color($bootstrap_color) {
        return $this->attr('data-offstyle',$bootstrap_color);
    }
    function xs() {
        return $this->size("xs");
    }
    function sm() {
        return $this->size("sm");
    }
    function lg() {
        return $this->size("lg");
    }
    function size($v) {
        return $this->attr('data-size',$v);
    }
}
function TOGGLEBUTTON() {
    return (new ToggleButton());
}
class RadioButton extends CheckInputBase {
    function __construct() {
        parent::__construct("radio");
    }
}

function RADIOBUTTON($label=false) {
    $radio=(new RadioButton);
    if($label) {
        $radio->add($label);
    }
    return $radio;
}

class DateInput extends TextInput {
    public function __construct($type = false) {
        parent::__construct("datetime");
        $this->form_control();
    }
    function datetime() {
        return $this->type("datetime");
    }
    function datetime_local() {
        return $this->type("datetime-local");
    }
    function month() {
        return $this->type("month");
    }
    function time() {
        return $this->type("time");
    }
    function week() {
        return $this->type("week");
    }
    
}

function DATEINPUT($placeholder=false) {return (new DateInput)->placeholder($placeholder);}
class HiddenField extends FormInput {
    function __construct() {
        parent::__construct("hidden");
    }
}

class ColorInput extends FormInput {
    function __construct() {
        parent::__construct("color");
    }
}

class FileInput extends FormInput {
    function __construct() {
        $this->accept_image();
        parent::__construct("file");
        $this->form_control();
    }
    function accept_audio() {
       return $this->accept("audio/*");
    }
    function accept_video() {
       return $this->accept("video/*");
    }
    function accept_image() {
       return $this->accept("image/*");
    }
    function accept($value=false) {
       return $this->attr('accept', $value);
    }
    function multiple() {
       return $this->attr('multiple', 'emptyfield');
    }
}

function FILEINPUT($extensions=false) {
    if($extensions) {
        return (new FileInput)->accept($extensions);
    } else {
        return (new FileInput);
    }
}

function DIVFILE($fileinput,$label="Select File") {
    return DIV($fileinput->class("custom-file-input"))
            ->class("custom-file")->add(
                    LABEL($label, $fileinput->id())
                        ->class("custom-file-label")
                    );
}

class Button extends FormInput {
    public function __construct($type = false,$content=false) {
        if($type) {parent::__construct($type);}
        else {parent::__construct("button");}
        $this->set_as_normal_control()->tag("button");
        $this->class("btn")->add($content);
    }
    function type($value=false) {
        return $this->attr('type', $value);
    }
   function quad() {
       return $this->class("rounded-0");
   }
   function closemodal() {
       return $this->attr("data-dismiss","modal");
   }
   function default() {
       return $this->class("btn-default");
   }
   function primary() {
       return $this->class("btn-primary");
   }
   function secondary() {
       return $this->class("btn-secondary");
   }
   function success() {
       return $this->class("btn-success");
   }
   function danger() {
       return $this->class("btn-danger");
   }
   function warning() {
       return $this->class("btn-warning");
   }
   function large() {
       return $this->class("btn-lg");
   }
   function small() {
       return $this->class("btn-sm");
   } 
   function block() {
       return $this->class("btn-block");
   }
   function info() {
       return $this->class("btn-info");
   }
   function light() {
       return $this->class("btn-light");
   }
   function dark() {
       return $this->class("btn-dark");
   }
   function active() {
       return $this->class("active");
   }
   function disabled($value=true) {
       if($value) {
            return $this->class("disabled")->attr("disabled","emptyfield")->attr("aria-disabled","true");
       } else {
           return $this; 
       }
   }
    function autofocus() {
        return $this->attr('autofocus', 'emptyfield');
    }
    function submit() {
        return $this->type("submit");
    }
    function reset() {
        return $this->type("reset");
    }
    function form($form_id) {
        return $this->attr('form', $form_id);
    }
    function toggle($value) {
        return $this->attr('data-toggle', $value);
    }
    function target($value) {
        return $this->attr('data-target', $value);
    }


    function lg(){
       return $this->class("btn-lg");
    }
    function sm(){
       return $this->class("btn-sm");
    }
}

function BUTTON($items=false) { return (new Button("button",$items));}
function UPDATEBUTTON($items,$control_id) {
    return BUTTON($items)->onclick("update_control('$control_id')");
}
function SEARCHBUTTON() {
    return BUTTON(I("search"))->submit();
}
class ImageButton extends FormInput {
    public function __construct() {
        parent::__construct("image");
    }
    function alt($value=false) {
        return $this->attr('alt', $value);
    }
    function size($width,$height) {
        return $this->width($width)->height($height);
    }
    function width($value=false) {
        return $this->attr('width', $value);
    }
    function height($value=false) {
        return $this->attr('height', $value);
    }
    
}

class InputOption extends Control {
    public function __construct($text,$value=false,$selected=false) {
        parent::__construct();
        $this->tag("option")->add($text);
        if($value!==false) {
            $this->value($value);
        }
        if($selected) {$this->selected();}
    }
    function text($name) {
        return $this->add($name);
    }
    function value($value) {
        return $this->attr('value', $value);
    }
    function selected() {
        return $this->attr('selected', 'emptyfield');
    }
    function disabled() {
        return $this->attr('disabled', 'emptyfield');
    }
}
class SelectInput extends RowContainer {
    public function __construct($name=false,$value=false,$selected=false)  {
        if($name) {$this->add_options($name, $value, $selected);}
        parent::__construct();
        $this->tag("select")->class("form-control");
        return $this;
    }
    function disabled() {
        return $this->attr('disabled', 'emptyfield');
    }
    function autofocus() {
        return $this->attr('autofocus','emptyfield');
    }
    function multiple() {
        return $this->attr('multiple', 'emptyfield');
    }
    function form($form_id) {
        return $this->attr('form', $form_id);
    }
    function label($text) {
        return $this->attr('label', $text);
    }
    function required() {
       return $this->attr('required', 'emptyfield');
    }
    function name($value) {
       return $this->attr('name', $value);
    }
    function add_options($name,$value=false,$selected=false,$disabled=false) {
        
        if(is_array($name) && $value===false && $selected===false && $disabled===false) {
            foreach ($name as $option) {
                if(is_array($option)) {
                $n_params=count($option);
                    if($n_params>0) {
                        if($n_params==1) {
                            
                            $this->add_options($option[0]);
                        } else if($n_params==2) {
                            $this->add_options($option[0],$option[1]);
                        } else if($n_params==3) {
                            $this->add_options($option[0],$option[1],$option[2]);
                        } else {
                            $this->add_options($option[0],$option[1],$option[2],$option[3]);
                        }
                    }
                }
            }
            return $this;
        }
                
        $drop=new InputOption($name,$value,$selected);
        if($disabled) {
            $drop->disabled();
        }
        return $this->add($drop);
    }
    
}

class HTMLMeter extends Control {
    public function __construct() {
        parent::__construct("meter");
    }
    function value($value=false) {
       return $this->attr('value', $value);
    } 
    function min($value=false) {
       return $this->attr('min', $value);
    } 
    function max($value=false) {
       return $this->attr('max', $value);
    }
    function high($value=false) {
       return $this->attr('high', $value);
    } 
    function low($value=false) {
       return $this->attr('low', $value);
    } 
    function optimum($value=false) {
       return $this->attr('optimum', $value);
    }
    function form($form_id) {
        return $this->attr('form', $form_id);
    }
}

function METER($value,$min=false,$max=false,$low=false,$high=false,$optimum=false) {
    $new_meter=(new HTMLMeter)->value($value);
    if($min) {$new_meter->min($min);}
    if($max) {$new_meter->max($max);}
    if($low) {$new_meter->low($low);}
    if($high) {$new_meter->high($high);}
    if($optimum) {$new_meter->optimum($optimum);}
    return $new_meter;
}
function LABEL($content,$dest=false) {
    if(is_array($content)) {
      return (new Label)->add($content);
    } else {
        return (new Label($content,$dest));
    }
}

function LEGEND($content) {
    return LABEL($content)->tag("legend");
}

function DROPDOWN($name=false,$value=false,$selected=false) {
    return (new SelectInput($name,$value,$selected));
}
function FORM($url_or_items=false,$post=false) {
    if(is_array($url_or_items) || $url_or_items instanceof Control || !$url_or_items) {
      return (new form)->add($url_or_items);
    } else {
        return (new form($url_or_items,$post));
    }
}
function FORMINLINE($url_or_items,$post=false) {
    return FORM($url_or_items, $post)->form_inline();
}


class GroupButton extends RowContainer {
    function __construct($rows=false) {
       if(is_array($rows)) {
         $this->add_rows($rows);
       }
       $this->class("btn-group")->role("group");
       parent::__construct();
   }
   function large() { return $this->class("btn-group-large");}
   function small() { return $this->class("btn-group-sm");}
   function vertical() { return $this->class("btn-group-vertical");}
   public function add_row($row) {
       if($row instanceof ListRow) {
           $this->rows[]=$row;
       } else {
           $this->rows[]=BUTTON($row);
       }
       return $this;
   }
} 
function GROUPBUTTON($rows=false) {
    return (new GroupButton($rows));
}
class TextArea extends TextInput {
    public function __construct($placeholder=false) {
        parent::__construct();
        
        $this->tag("textarea")->set_as_normal_control()
                ->noresize();
        if($placeholder) {
            $this->placeholder($placeholder);
        }
    }
    
    function rows($value=false) {
       return $this->attr('rows', $value);
    }
     function name($value=false,$load_from_params=true) {
        if($load_from_params) {
            if(param($value)!==false) {
                $this->add(param($value));
            }
        }
        return $this->attr('name', $value);
    }
    function cols($value=false) {
       return $this->attr('cols', $value);
    }
    
    function wrap($value=false) {
       return $this->attr('wrap', $value);
    }
    function wrap_hard() {
       return $this->attr('wrap', 'hard');
    }
    function wrap_soft() {
       return $this->attr('wrap', 'soft');
    }
    function noresize() {
        return $this->style('resize','none');
    }
}

function TEXTAREA($placeholder=false) {
    return (new TextArea($placeholder));
}

function HIDDENFIELD($name,$value) {
    return (new HiddenField)->name($name)->value($value);
}
