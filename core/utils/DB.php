<?php
/**
 * Esse arquivo contem funcoes para simplificar e abstrair a conexão com o banco de dados
 * se no futuro for necessário a troca de mysqli para outro adaptador é possivel usando essa 
 * interface de abstração, trocando as funcoes mysqli por outro
 * requer a extensão MYSQLI esteja ativada
 */
class DB {
    private $mysqli;
    public $connected=false;
    private $host;
    private $user;
    private $password;
    private $database;
    private $port=null;
    
    function __construct($host,$user,$password,$database,$port=false) {
        $this->host=$host;
        $this->user=$user;
        $this->password=$password;
        $this->database=$database;
        $this->port=$port;
    }
    
    /**
    *  funcao para se conectar e reconectar com o banco de dados
    */
    public function connect($collation="latin1_swedish_ci",$charset="utf8",$timezone="-3:00") {
        if($this->mysqli) mysqli_close($this->mysqli);
        $this->mysqli=new mysqli($this->host,$this->user,$this->password,$this->database,$this->port);

        if(!$this->mysqli) {
            return false;
        }
        /**
         *  essas variaveis precisam ser especificadas na hora da conexão por vários motivos
         *  se não forem especificadas será usado um valor padrão e dependente do sistema.
         *  isso pode causar bugs imprevisiveis e também erros de caracteres por causa do charset e da collation
         *  o timezone precisa ser especificado de acordo com a linguagem e o ip para as datas serem relativas ao pais do usuário
         */
        $this->mysqli->set_charset($charset);
        $this->query("SET collation_connection = '$collation'");
        $this->query("SET time_zone = '$timezone'");
        $this->connected=true;
        return true;
    }
    
    public function query($sql_query) {
        $result=$this->mysqli->query($sql_query);
        if($result==false) {
            // comentar em produção
            echo 'QUERY FAILED: '.mysqli_error($this->mysqli);
        }
        return $result;
    }
    /**
     * Executa uma query no banco de dados e retorna o resultado da primeira linha
     * em um array associativo. util para buscar um item apenas em uma tabela
     * do banco de dados 
     *
     * @param string $sql_query a query a ser executada
     * @return array
     */
    public function fetch_row($sql_query) {
        return $this->query($sql_query)->fetch_assoc();
    }

    /** 
     *  retorna o primeiro valor da primeira linha.
    *   por exempo o trecho:
    *   $db->fetch_value("select name from users where id='5'");
    *   vai retornar uma string com o nome do usuario, 
    *   se for int vai retornar inteiro
    *   se nao houver resultados vai retornar false 
     */
    public function fetch_value($sql_query) {
        $row=$this->query($sql_query)->fetch_row();
        if(!$row) return false;
        else return $row[0];
    }

    /** executa uma query insert e retorna o id do ultimo item inserido */
    public function fetch_insert($sql_query) {
        $this->query($sql_query);
        return mysqli_insert_id($this->mysqli);
    }
    /**
     * retorna um array com todos os resultados contento um array associativo 
     * para cada linha da tabela retornada
     */
    public function fetch_all($sql_query) {
        $result=$this->query($sql_query);
        if($result){
            /** 
             * alguns servidores não suportam essa função porque é lenta e pesada e a extensão fica mal configurada.
             * por isso só usa quando é possivel
             */
            if(function_exists('mysqli_fetch_all')) {
                return mysqli_fetch_all($result,MYSQLI_ASSOC);
            } else {
                $rows=[];
                while($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
            }
            return $rows;
        }else{
            return false;
        }
    }
    /** Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection */
    public function escape($string) {
        return $this->mysqli->real_escape_string($string);
    }
    /** para executar queries multiplas separadas por ; */
    public function multiple_query($queries){
        return $this->mysqli->multi_query($queries);
    }
    
    /** 
     * essa função atualiza dados na tabela a partir de um array associativo
     * se o item não existir ainda então ele insere uma nova linha na tabela do banco
     * bom para ser usados em caso que é necessário atualizar ou inserir o registro, sem precisar deletar e inserir de novo.
     * 
     *  */
    public function update_into($table,$primary_key_name,$id,$data_assoc) {
        // se o não existe então ele cria um novo
        if(!$this->fetch_value("SELECT COUNT(*) FROM $table WHERE $primary_key_name='$id'"))
		    $this->fetch_insert("INSERT INTO $table ($primary_key_name) VALUES ('$id')");

        // junta todos os dados que serão atualizados no formato de uma query
        // update no banco de dados
        $update_values=[];
        foreach ($data_assoc as $key => $value) {
            $update_values[]="$key='$value'";
        }
        $update_values=join(',',$update_values);
        $this->query("UPDATE $table SET $update_values WHERE $primary_key_name='$id'");

    }
}


class SQLUtils {
    /**
     * essa funcao gera uma condicao de pesquisa de palavras 
     * serve para implementar funcoes de busca no site, como um campo de pesquisa de produto ou texto
     * 
     */
    public static function sql_search($fields,$words,$glue_words="and",$glue_fields="or") {
        if(!is_array($fields)) {$fields=[$fields];}
        if(!is_array($words)) {$words=[$words];}
        $words_conditions=[];
        foreach ($words as $word) {
            $fields_conditions=[];
            foreach($fields as $field) {
                $fields_conditions[]= "($field LIKE '%$word%')";
            }
            $wdc="(".join(" $glue_fields ",$fields_conditions).")";
            $words_conditions[]=$wdc;
        }
        return "(".join(" $glue_words ",$words_conditions).")";
    }
    /**
     * Essa função serve para gerar uma condicao de pesquisa com uma string
     * facilita muito na hora de pesquisar um texto no banco de dados
     * porque ela gera as condicoes LIKE para ti.
     * 
     *
     * @param [type] $fields
     * @param [type] $string
     * @param string $glue_words
     * @param string $glue_fields
     * @return void
     */
    public static function search_string($fields,$str, $glue_words="AND",$glue_fields="OR") {
        if(!$str) {return "1=1";}
        if(strlen(trim($str))==0) {return "1=1";}
        return SQLUtils::sql_search($fields, preg_split("/[\s,-]+/", $str), $glue_words,$glue_fields);
    }
    /**
     * essa funcao serve para gerar uma string de condicao de pesquisa em modo FULL TEXT SEARCH
     */
    public static function fts_search($fields,$string,$mode="NATURAL LANGUAGE") {
        if(!$string) {return "1=1";}
        if(!is_array($fields)) {$fields=[$fields];}
        if(strlen(trim($string))==0) {return "1=1";}
        return "MATCH(". join(",", $fields).") AGAINST('$string' IN $mode MODE)";
    }
    /** gera uma string contendo ma condicao de pesquisa FULL TEXT SEARCH no modo booleano */
    public static function bool_search($fields,$string) {
        return SQLUtils::fts_search($fields,$string,"BOOLEAN");
    }

    /**
     *  gera uma string sql contendo LIMIT E OFFSET  correto para usar na paginacao 
     *  Começa na pagina 1
     */
    public static function page($page,$page_size) {
        return "limit $page_size offset ".($page_size*($page-1));
    }
}

