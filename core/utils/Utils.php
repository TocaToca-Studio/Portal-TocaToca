<?php
/** aqui será inserido todas as funções genéricas que são usadas repetitivamente no código */
class Utils {
    /**
     * envia um pedido redirecionamento e fecha a conexão.
     */
    public static function redirect($location) {
        // se nenhum destino for indicado então vai pra raiz do site
        if(!$location) $location="/";
        // past date to encourage expiring immediately
        // because some browsers uses redirect caching    
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); 
        Header("Location: $location", true, 302);
        exit; 
    } 
        
    /**
     * gera uma string pronunciavel com silabas aleatorias
     *
     * @param integer $length tamanho da string
     * @return string
     */ 
    public static function random_string($length = 8) {    
        $chars = 'bcdfghjklmnprstvwxzaeiou';
         $result="";
        for ($p = 0; $p < $length; $p++) {
            $result .= ($p%2) ? $chars[mt_rand(19, 23)] : $chars[mt_rand(0, 18)];
        }
    
        return $result;
    }
    /** gera os parametros get da url do link e mantem com os parametros atuais  */
    public static function build_query($params) {
        $link_params=$_GET;
        foreach($params as $key=>$val) $link_params[$key]=$val;
        return http_build_query($link_params);
    } 
    public static function self_url() {
        return trim(strtok($_SERVER['REQUEST_URI'],'?'),"#");
    }
    public static function build_complete_query($params=[]) {
        return Utils::self_url().'?'.Utils::build_query($params);
    }
    /**
     * gera um texto pronunciavel com silabas aleatorias 
     * e  palavras de tamanhos diferentes
     *
     * @param integer $length tamanho da string
     * @return string
     */ 
    public static function random_text($total_words,$min_word_length=2,$max_word_length=7) {
        $result="";
        for ($c=0;$c<$total_words;$c++) {
            $result.= Utils::random_string(random_int($min_word_length, $max_word_length))." ";
        }
        return trim($result);
    }

    /** remove qualquer coisa da string que não seja um caracter */
    public static function only_numeric($str) {
        return preg_replace('/[^0-9]/', '', $str);
    }
    /** obtem array amigavel, com as informações dos arquivos enviados por POST */
    public static function get_uploaded_files($input_fieldname){
        if(!isset($_FILES[$input_fieldname]))
        return false;
        $file_post=$_FILES[$input_fieldname];
        $isMulti    = is_array($file_post['name']);
        $file_count    = $isMulti?count($file_post['name']):1;
        $file_keys    = array_keys($file_post);
    
        $file_ary    = []; 
        for($i=0; $i<$file_count; $i++)
            foreach($file_keys as $key)
                if($isMulti)
                    $file_ary[$i][$key] = $file_post[$key][$i];
                else
                    $file_ary[$i][$key]    = $file_post[$key];
        
        $filtered=[];
        foreach($file_ary as $f) if($f['name']) $filtered[]=$f;
        return $filtered;
    }
    /** converte uma string para float, seja ela com ponto ou virgula */
    public static function parse_any_float($str) {
        $str=strval($str);
        if(strstr($str, ",")) {
            $str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs
            $str = str_replace(",", ".", $str); // replace ',' with '.'
        }
        
        if(preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.'
            return floatval($match[0]);
        } else {
            return floatval($str); // take some last chances with floatval
        }
    }
    public static function remove_accents($str) {
        $table = array(
            "\r"=>"", "\n"=>"", "\r\n"=>"",
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
        );
        return strtr($str, $table);
    }
    /** 
     * funcao que gera uma url amigavel a partir do nome e do id do item
     * se o id for especificado adiciona ao final da string com um traço
     */
    public static function generate_seo($title,$id=false) {
        $seo=Utils::remove_accents(
            strtolower(html_entity_decode($title))
        );
        $seo=
            trim(
                preg_replace('/ +/', ' ', 
                    preg_replace('/[^a-zA-Z0-9\s]/', ' ', 
                        $seo
                    )
                )
            );
        $seo=str_replace(' ', '-', $seo);
        if($id) {$seo.='-'.$id;}
        return $seo;
    }
    /** obtem uma informacao do cache e não verifica se ela está expirada ou não */
    public static function get_cache_info($name) {
        $cache_folder=$_SERVER['DOCUMENT_ROOT'].'/cache/json';
        $cache_file=$cache_folder.'/'.Utils::generate_seo($name).'.json';
        if(!file_exists($cache_file)) return null;
        return json_decode(file_get_contents($cache_file),JSON_OBJECT_AS_ARRAY);
    }
    /** salva uma informacao no disco, em um json com um tempo de expiração limite */
    public static function store_cache_data($name,$data,$time_to_expire_in_seconds=86400) {
        $cache_folder=$_SERVER['DOCUMENT_ROOT'].'/cache/json';
        $cache_file=$cache_folder.'/'.Utils::generate_seo($name).'.json';
        if(!file_exists($cache_folder)) mkdir($cache_folder,0777,true);
        file_put_contents($cache_file,
            json_encode([
                'data'=>$data,
                'cached_in'=>time(),
                'expiration'=>time().$time_to_expire_in_seconds
            ],JSON_OBJECT_AS_ARRAY | JSON_PRETTY_PRINT)
        );
    }
    /** obtem uma informacao do cache se ela ainda não estiver expirada */
    public static function get_cache_data($name) {
        $cache_folder=$_SERVER['DOCUMENT_ROOT'].'/cache/json';
        $cache_file=$cache_folder.'/'.Utils::generate_seo($name).'.json';
        if(!file_exists($cache_file)) return null;
        $json_data=json_decode(file_get_contents($cache_file),JSON_OBJECT_AS_ARRAY);
        /** se for um json inválido ou tiver expirado */
        if(isset($json_data['data']) || !isset($json_data['expiration']) || $json_data['expiration']>time()) {
            unlink($cache_file);// apaga o arquivo
            return null;
        }
        return $json_data['data'];
    }
    public static function price_format($number) {
        return number_format(Utils::parse_any_float($number),2,",",".");
    } 
    public static function limit_text($text,$limit=50) {
        return strlen($text) > $limit ? substr($text,0,$limit)."..." : $text;
    }
    public static function time_elapsed_string($timestamp, $full = false) {
        $now = new DateTime();
        $ago = new DateTime();
        if(is_string($timestamp));
        $timestamp=strtotime($timestamp);
        $ago->setTimestamp(intval($timestamp));
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string_single = array(
            'y' => 'ano',
            'm' => 'mes',
            'w' => 'semana',
            'd' => 'dia',
            'h' => 'hora',
            'i' => 'minuto',
            's' => 'segundo',
        );  
        $string_plural = array(
            'y' => 'anos',
            'm' => 'meses',
            'w' => 'semanas',
            'd' => 'dias',
            'h' => 'horas',
            'i' => 'minutos',
            's' => 'segundos',
        );
        foreach($string_single as $k=>$s) $string_single[$k]=__($s);
        foreach($string_plural as $k=>$s) $string_plural[$k]=__($s);

        $parts=[];
        foreach ($string_single as $k => $v) {
            if ($diff->$k) {
                $parts[]= $diff->$k . ' ' . ($diff->$k > 1 ? $string_plural[$k] : $string_single[$k]);
            } 
        }
    
        if (!$full) $parts = array_slice($parts, 0, 1);
        return $parts ? implode(', ', $parts) . ' '.__('atrás') : __('agora mesmo');
    }
    
    public static function validate_recaptcha($recaptcha_response,$secret) {
        $resposta = @json_decode(@file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?".http_build_query([
                "secret"=>$secret,
                "response"=>$recaptcha_response,
                "remoteip"=>$_SERVER['REMOTE_ADDR']
            ])
        ),true);
        return @$resposta['success'] ? true : false; 
    }
    
}