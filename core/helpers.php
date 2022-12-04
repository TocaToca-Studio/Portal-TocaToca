<?php 


function _post($field) {
    $p=isset($_POST[$field]) ? $_POST[$field] : null; 
    if($p=="true" || $p=="false") $p=$p=="false" ? false : true;
    return $p;
}
function _get($field) {
    $g=isset($_GET[$field]) ? $_GET[$field] : null; 
    if($g=="true" || $g=="false") $g=$g=="false" ? false : true;
    return $g;
}


$error=false;
$errors=[];
$success=false;
 
function form_errors() {
    global $errors;
    return $errors;
}
function form_error($text) {
    global $errors; 
    $errors[]=$text;
} 
function has_form_errors() {
    global $errors; 
    return count($errors);
}
function draw_form_errors() {   
    global $errors; 
    $alerts=[];

    foreach(form_errors() as $e) {
        $alerts[]=ALERT($e)->warning();
    }
    return $alerts;
}
 
function thumb($url,$width=120,$height=false) {
    $q=["src"=>$url];
    if($width) $q['width']=$width;
    if($height) $q['height']=$height; 
    return site_url("thumb/thumb.php?".http_build_query($q));
}
function formatar_data($mysql_date) {
    ($dt=new Datetime())->setTimestamp(strtotime($mysql_date));
    return $dt->format("Y-m-d H:i");
}
function send_invalid_request() {
    http_response_code(400);
    die('INVALID REQUEST');
} 

/** FUNCTIONS */
/** calcula uma hash para uma informação arbitrária */
function calc_checksum($data) { return crc32(json_encode($data));}

/** indexa o array pela chave */
function index_array($arr,$key) { 
    if(!is_array($arr)) die('ERROR IMPOSSIBLE TO INDEX A NON ARRAY VALUE!');
    $indexed_array=[];
    foreach($arr as $v) {
        if(isset($v[$key])) $indexed_array[$v[$key]]=$v;
    }
    return $indexed_array;
}


function insert_query_sql($table,$data) { 
    $keys='`'.join('`,`',array_keys($data)).'`';
    foreach($data as $k=>$v) $data[$k]=db()->escape($data[$k]);
    $values="'".join("','",$data)."'"; 
    return "INSERT IGNORE INTO $table($keys) VALUES ($values);\r\n";
} 
function update_query_sql($table,$data,$primary_key) { 
    foreach($data as $k=>$v)  $data[$k]='`'.$k.'`= \''.db()->escape($v).'\'';
    return "UPDATE $table SET ".implode(',',$data)." WHERE ".$data[$primary_key].";\r\n";
}


function protect_page($redir='') {
    if($redir) {$redir="?redir=".base64_encode($redir);}
    if(!LoginTool::is_logged()) Utils::redirect(site_url("conta/login".$redir));
}

 
// For earlier versions of PHP, we polyfill the str_contains function using the following code.
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        if(!is_string($haystack) || !is_string($needle)) return false;
        
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}
