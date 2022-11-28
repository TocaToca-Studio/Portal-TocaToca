<?php

require_once __DIR__.'/base.php';

require_once __DIR__.'/mimetypes.php';


function hexcolor($red,$green,$blue,$alpha=false) {
    if ($alpha) {
        return sprintf("#%02x%02x%02x%02x", $red, $green, $blue,$alpha);
    } else {
        return sprintf("#%02x%02x%02x", $red, $green, $blue);
    }
}

function get_mime_type($filename) {
    global $mime_types;
    $idx = explode( '.', $filename );
    $idx = strtolower($idx[count($idx)-1]);
    if (isset($mime_types[$idx])) {
     return $mime_types[$idx];
    } else {
     return false;
    }
 }
 
class MediaSource Extends LinkedSource {
    public function __construct() {
        parent::__construct("source");
    }
}
class HtmlMedia extends Div {
    public function __construct($tag,$show_controls = true,$autoplay=false,$loop=false) {
        parent::__construct();
        $this->tag($tag);
        if($show_controls) { $this->controls(); }
        if($autoplay) { $this->autoplay();}
        if($loop) { $this->loop();}
    }
    
    public function autoplay() {
         $this->attr("autoplay", "emptyfield");
         return $this;
    }
    public function controls() {
         $this->attr("controls", "emptyfield");
         return $this;
    }
    public function loop() {
         $this->attr("controls", "emptyfield");
         return $this;
    }
    public function src($url) {
        if(is_array($url)) {
            foreach ($url as $value) {
                $this->add((new MediaSource)->src($value));
            }
        } else {
            $this->add((new MediaSource)->src($url));
        }
        return $this;
    }
}

class Video extends HtmlMedia {
    function width($value=false) {
        return $this->attr("width", $value);
    }
    function height($value=false) {
        return $this->attr("height", $value);
    }
    function resize($width,$height) {
     return $this->size($width,$height);
    }
    function size($width,$height) {
        if($width) { $this->width($width);}
        if($height) { $this->height($height);}
        return $this;
    }
    function iframe($remove_border=false) {
        if($remove_border) {
            $this->attr("frameborder","0");
        }
        $this->tag="iframe";
        return $this;
    }
    function poster($url) {
        $this->attr("poster",$url);
        return $this;
    }
    function __construct($url,$show_controls = true,$autoplay=false,$loop=false) {
       parent::__construct("video",$show_controls,$autoplay);
       $this->src($url);
       if($loop) {$this->loop();}
   } 
}
function VIDEO($url,$width=false,$height=false,$controls = true,$autoplay=false,$loop=false) {
    return (new Video($url,$controls,$autoplay,$loop))
                    ->resize($width,$height);
}

class Audio extends HtmlMedia {
    function __construct($url,$controls = true,$autoplay=false,$loop=false) {
       parent::__construct("audio",$controls,$autoplay,$loop);
               $this->src($url);
   } 
}
function AUDIO($url,$controls=true,$autoplay=false) {
    return (new Audio($url,$controls,$autoplay));
}

class FlashObjectParam extends Control {
     public function __construct($name=false,$value=false) {
        $this->param($name,$value);
        $this->set_as_void_control();
        parent::__construct("param");
    }
    public function param($name,$value=false) {
        if($name) {$this->attr("name",$name);}
        if($value) {$this->attr("value",$value);}
        else { return $this->attr("value");}
        return $this;
    }
}
class FlashObject extends Control {
    function form($form_id) {
        return $this->attr('form', $form_id);
    }
    function usemap($value) {
        return $this->attr('usemap', $value);
    }
    function type($value) {
        return $this->attr('type', $value);
    }
    function typemustmatch($value=true) {
        return $this->attr("typemustmatch", $value);
    }
    function data($value=true) {
        return $this->attr("typemustmatch", $value);
    }
    function width($value=false) {
        return $this->attr("width", $value);
    }
    function height($value=false) {
        return $this->attr("height", $value);
    }
     public function param($name,$value=false) {
        $this->add(new FlashObjectParam($name,$value));
        return $this;
    }
}