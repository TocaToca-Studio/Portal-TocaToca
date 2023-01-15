<?php
require_once __DIR__.'/base.php';

class Noty extends Control {
    public $noty_obj= [];

    
    /**
     * top, topLeft, topCenter, topRight,
     *  center, centerLeft, centerRight,
     *  bottom, bottomLeft, bottomCenter, bottomRight
     */
    public function layout($value) {
        $this->noty_obj['layout']=$value;
        return $this->prepare();
    }
    
    function __construct($text=false) {
       parent::__construct();
       $this->tag("SCRIPT");
       if($text) {
            return $this->text($text);
       } else {
           return $this;
       }
    }
    /** show on top*/
    public function top() {return $this->layout('top');}
    /** show on topLeft */
    public function top_left() {return $this->layout('topLeft');}
    /** show on topCenter */
    public function top_center() {return $this->layout('topCenter');}
    /** show on topRight */
    public function top_right() {return $this->layout('topRight');}

    /** show on center*/
    public function center() {return $this->layout('center');}
    /** show on centerLeft*/
    public function center_left() {return $this->layout('centerLeft');}
    /** show on centerRight*/
    public function center_right() {return $this->layout('centerRight');}

    /** show on bottom */
    public function bottom() {return $this->layout('bottom');}
    /** show on bottomLeft */
    public function bottom_left() {return $this->layout('bottomLeft');}
    /** show on bottomCenter */
    public function bottom_center() {return $this->layout('bottomCenter');}
    /** show on top */
    public function bottom_right() {return $this->layout('bottomRight');}

    public function theme($th) {
        $this->noty_obj['theme']=$th;
        return $this->prepare();
    }

    public function theme_light() {
        return $this->theme('light');
    }
    public function theme_relax() {
        return $this->theme('relax');
    }
    public function theme_bootstrap() {
        return $this->theme('bootstrapTheme');
    }
    public function theme_metroui() {
        return $this->theme('metroui');
    }
    public function theme_mint() {
        return $this->theme('mint');
    }
    public function theme_nest() {
        return $this->theme('nest');
    }
    public function theme_semanticui() {
        return $this->theme('semanticui');
    }
    public function theme_sunset() {
        return $this->theme('sunset');
    }
    public function type($value) {
        $this->noty_obj['type']=$value;
        return $this->prepare();
    }
    public function success() {
        return $this->type('success');
    }
    public function error() {
        return $this->type('error');
    }
    public function warning() {
        return $this->type('warning');
    }
    public function information() {
        return $this->type('information');
    }
    public function notification() {
        return $this->type('notification');
    }
    /** [boolean] - displays a progress bar */
    public function progressbar($value) {
        $this->noty_obj['progressBar']=$value;
        return $this->prepare();
    }

    public function text($content) {
        $this->noty_obj['text']=$content;
        return $this->prepare();
    }

    /** [boolean] If you want to use queue feature set this true */
    public function dismiss_queue($content) {
        $this->noty_obj['text']=$content;
        return $this->prepare();
    }

     /** [boolean] adds notification to the beginning of queue when set to true */
     public function force($value) {
        $this->noty_obj['force']=$value;
        return $this->prepare();
    }
      /** [integer|boolean] delay for closing event in milliseconds. Set false for sticky notifications */
      public function timeout($value) {
        $this->noty_obj['timeout']=$value;
        return $this->prepare();
    }

    public function speed($millis) {
        $this->noty_obj['animation']=['speed'=> $millis];
        return $this->prepare();
    }

    /** // 'click', 'button', 'hover', 'backdrop' // backdrop click will close all notifications */
    public function close_with($event) {
        if(!isset($this->noty_obj['closeWith'])) {
            $this->noty_obj['closeWith']=[];
        }
        $this->noty_obj['closeWith'][]=$event;
        return $this->prepare();
    }

    public function close_on_hover() {
        return $this->close_with('hover');
    }
    
    public function close_on_backdrop() {
        return $this->close_with('backdrop');
    }
    
    /** [boolean] if true adds an overlay */
    public function modal($value=true) {
        $this->noty_obj['modal']=$value;
        return $this->prepare();
    }
    /** [boolean] if true closes all notifications and shows itself */
    public function killer($value=true) {
        $this->noty_obj['modal']=$value;
        return $this->prepare();
    }
    public function prepare() {
        $this->subcontrols=[];
        $this->subtags=[];
        $this->add('new Noty('.json_encode($this->noty_obj,JSON_OBJECT_AS_ARRAY).').show();');
        return $this;
    }
}


function NOTY($text=false) { return (new Noty($text));}
function NOTIFICATION($text=false) { return (new Noty($text));}