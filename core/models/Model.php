<?php 
require_once __DIR__.'/../config.inc.php';

/**
 * Não estamos usando O Padrão MVC. É apenas PHP puro porém organizado. 
 * essa é uma classe genérica para representar uma entidade do banco de dados.
 * por favor, os dados precisam ficar no banco de dados! e não representados aqui..
 * NÃO insira variaveis da tabela dentro da classe isso gera muito código desnecessário
 * em vez disso acesse diretamente os dados atraves de uma query. SEMPRE.
 * você está manipulando informações do banco, e não dados da memória. 
 * VOCÊ NÃO ESTÁ FAZENDO UM APLICATIVO DESKTOP!
 * por isso aqui deve ficar apenas a REFERENCIA. Apenas a variável id é mais do que suficiente.
 */
abstract class Model {
    /** A primary key da tabela fica aqui */
    public $id="0";
    /** o nome da tabela */
    public $table_name;
    /** o nome da coluna PRIMARY KEY */
    public $id_column_name;

    function __construct($id=0)  {
        $this->id=$id;
    }   
    /** obtem a coluna da tabela pelo nome da coluna no db */
    public function get_info($field) {
        if(!$this->id) return false;
        $field=db()->escape($field);
        return db()->fetch_value("SELECT `$field` FROM `".db()->escape($this->table_name)."` WHERE `".db()->escape($this->id_column_name)."`='".db()->escape($this->id)."' ");
    }
    /** obtem várias colunas da tabela pelo nome das colunas no db retornando um array */
    public function get_infos($fields) {
        if(!$this->id) return [];
        if(!is_array($fields)) $fields=[$fields];
        foreach($fields as $k=>$v) $fields[$k]=db()->escape($v);
        $sql_fields='`'.join('`,`',$fields).'`';
        if(count($fields)==1&& $fields[0]=='*') $sql_fields='*';
        return db()->fetch_row("SELECT $sql_fields FROM `".$this->table_name."` WHERE `".db()->escape($this->id_column_name)."`='".db()->escape($this->id)."'");
    }
    /** atualiza um dado da tabela pelo nome da coluna no db */
    public function update_info($field,$value,$strip_tags=true) {
        $field=db()->escape($field);
        if($strip_tags) $value=strip_tags($value);
        $value=db()->escape($value);
        $query="UPDATE `".$this->table_name."` SET `$field`='$value' WHERE `".db()->escape($this->id_column_name)."`='".db()->escape($this->id)."'";
        //echo $query;
        return db()->query($query);
    }
    /** atualiza várias informações do usuario através de array associativo */
    public function update_infos($infos) {
        foreach($infos as $key=>$value) {
            $update_fields[]=" `".db()->escape($key)."`='".strip_tags(db()->escape($value))."' ";
        }
        $sql_command= "UPDATE `".db()->escape($this->table_name)."` SET ".join(',',$update_fields).' WHERE `'.db()->escape($this->id_column_name)."`='".db()->escape($this->id)."'";
        // var_dump($sql_command);
        return db()->query($sql_command);
    }
    /** deleta si mesmo do banco de dados! */
    public function delete() {
        return db()->query("DELETE FROM `".db()->escape($this->table_name)."` WHERE `". db()->escape($this->id_column_name)."`='".db()->escape($this->id)."'");
    }
    public function insert($data) { 
        $keys=[]; $values=[];
        foreach($data as $k=>$v) {
            $keys[]='`'.db()->escape($k).'`';
            $values[]="'".db()->escape($v)."'";
        }
        $new_id=db()->fetch_insert("INSERT IGNORE INTO `".db()->escape($this->table_name)."` ( ".implode(',',$keys).") VALUES ( ".implode(',',$values).');');
        $this->id=$new_id;
        return $new_id;
    }
    public function exists() {
        return db()->fetch_value(
            "SELECT `".db()->escape($this->id_column_name)."` FROM `".db()->escape($this->table_name)."`
             WHERE `".db()->escape($this->id_column_name)."`='".db()->escape($this->id)."'"
        );
    }
}