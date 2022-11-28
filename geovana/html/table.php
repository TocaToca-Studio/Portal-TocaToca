<?php

require_once __DIR__.'/base.php';

class TableRowContainer extends RowContainer {
    public $in_heading=false;
    public function __construct($tag = false,$in_head=false) {
        $this->in_heading=$in_head;
        parent::tag($tag);
    }
    public function add_row($row) {
        if($this->tag=="tbody" ||  $this->tag=="thead" || $this->tag=="tbody") {
            parent::add_row((new TableRowContainer("tr",$this->tag=="thead"))->add_rows($row));

        } else {
            if($this->in_heading) {
                parent::add_row((new TableRowContainer("th"))->add($row));
            } else {
                 parent::add_row((new TableRowContainer("td"))->add($row));
            }
        }
        return $this;
    }
    
}

class HtmlTable extends RowContainer {
    public $head,$body,$foot;
    public function __construct($values=false) {
        parent::__construct();
        $this->tag("table")->class("table");
        $this->head=(new TableRowContainer("thead"));
        $this->body=(new TableRowContainer("tbody"));
        $this->foot=(new TableRowContainer("tfoot"));
        if($values) {$this->put_array($values);}
    }    
    public function stripped() {
        return $this->class("table-striped");
    }    
    public function bordered() {
        return $this->class("table-bordered");
    }     
    public function responsive() {
        return $this->class("table-responsive");
    }  
    public function hover() {
        return $this->class("table-hover");
    }    
    public function dark() {
        return $this->class("table-dark");
    } 
    public function borderless() {
        return $this->class("table-borderless");
    }
    public function  put_array($arr) {
        if(is_array($arr)) {
            $count=count($arr);
            if($count>0) {
                $this->put_head($arr[0]);
            }                
            if($count>1) {
                $this->put_body($arr[1]);
            }
            if($count>2) {
                $this->put_foot($arr[2]);
            }
        }
        return $this;
    }

    public function put_head($arr) {
        $this->head->add_rows($arr);
        return $this;
    }
    public function add_head_row($arr) {
        $this->head->add_rows([$arr]);
        return $this;
    }
    public function put_body($arr) {
        $this->body->add_rows($arr);
        return $this;
    }
    public function add_body_row($arr) {
        $this->body->add_rows([$arr]);
        return $this;
    }
     public function put_foot($arr) {
        $this->foot->add_rows($arr);
        return $this;
    }
    public function add_foot_row($arr) {
        $this->head->add_rows([$arr]);
        return $this;
    }
    public function content_html($inner='') {
        return $this->head.
               $this->body.
               $this->foot.
               parent::content_html($inner);
    }
    public function send_content($inner='') {
        send_item($this->head);
        send_item($this->body);
        send_item($this->foot);
        return 
        parent::send_content($inner);
    }
    

}
function TABLE($arr=false) {return (new HtmlTable($arr));}