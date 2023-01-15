<?php


/**
 * esse arquivo serve para codificar e descodificar os tokens jwt
 * útil para autenticacao e validação para usuarios e api
 */
class JWT {
    private $secret_key;

    public const supported_algorithms=[
        'ES256' => ['openssl', 'SHA256'],
        'HS256' => ['hash_hmac', 'SHA256'],
        'HS384' => ['hash_hmac', 'SHA384'],
        'HS512' => ['hash_hmac', 'SHA512'],
        'RS256' => ['openssl', 'SHA256'],
        'RS384' => ['openssl', 'SHA384'],
        'RS512' => ['openssl', 'SHA512'],
    ];
    /** a funcao de codificao base64 adequada para a geracao de token jwt */
    private static function base64url_encode($str) {
        return str_replace('=', '', strtr(base64_encode($str), '+/', '-_'));
    }
    /** a funcao de decodificao base64 adequada para a geracao de token jwt */
    private static function base64url_decode($str) { 
        $remainder = strlen($str) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $str .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($str, '-_', '+/'));
    }
 
    /** funcao responsavel por gerar uma assinatura valida para o token
     *  a partir do header, baseado na chave secreta */ 
    public static function generate_sign($header,$payload,$secret_key,$algorithm='HS256') {

        $algorithm=strtoupper($algorithm);

        /** 
         * procura o  algorimo especificado na lista de algoritmos e codifica a assinatura 
         * com o algoritmo correto
         */
        if(isset(JWT::supported_algorithms[$algorithm])) {
            $alg=JWT::supported_algorithms[$algorithm];
            if($alg[0]=="hash_hmac") {

            if(!$secret_key) $secret_key=$secret_key;
                $sign=hash_hmac($alg[1], $header . "." . $payload, $secret_key, true);
            } else {
                if(!$secret_key) $secret_key='';
                // entao é 'openssl'
                $sign="";
                openssl_sign($header . "." . $payload,$sign,$secret_key, $alg[1]);
                return $sign;
            }
        } else {
            // codifica com o mais comum mesmo 
            $sign=hash_hmac('HS256', $header . "." . $payload, $secret_key, true);
        }
        return JWT::base64url_encode($sign);
    }
    

    /** gera um token jwt válido com assinatura */
    public static function generate_token($header,$payload,$secret_key) {
        //Header Token
        $algorithm=$header['alg'];
        //JSON
        $header = json_encode($header);
        $payload = json_encode($payload);

        //Base 64
        $header = JWT::base64url_encode($header);
        $payload = JWT::base64url_encode($payload);

        //Sign
        $sign=JWT::generate_sign($header,$payload,$secret_key,$algorithm);

        //Token
        $token = $header . '.' . $payload . '.' . $sign;
        return $token;
    }
    /** gera um token de autenticacao para o usuario com o  id especificado */
    public static function generate_user_token($user_id,$secret_key){
        $header=[
            'typ' => 'JWT',
            'alg' => "HS256"
        ];
        $payload=[
                'exp' =>  time() + (3600 * 24 * 15), // token de login expira em 15 dias
                'uid' => strval($user_id)
        ];
        return JWT::generate_token($header,$payload,$secret_key);
    }
    /** 
    *    decodifica o token JWT e transforma em um array associativo original do token.
    *    mas essa funcao nao valida o token. só descodifica
    */
    public static function decode_token($token) {
        $token_pieces=explode('.',$token);
        if(count($token_pieces)!=3) {
            return false;
        }
        return [
            "header"=>json_decode(
                JWT::base64url_decode($token_pieces[0]),
                JSON_OBJECT_AS_ARRAY
            ),
            "payload"=>json_decode(
                JWT::base64url_decode($token_pieces[1]),
                JSON_OBJECT_AS_ARRAY
            )
        ];
    }

    /**
     * pega um token, decodifica e compara com a assinatura gerada a partir da chave secreta
     * se for um token válido, ele rotorna o id do usuário do token e true se nao houver id no token
     * se for inválido retorna false
     */
    public static function validate_token($token,$secret_key=false) {
        if(!$token) { return false;}

        $token_pieces=explode('.',$token);
        if(count($token_pieces)!=3) { return false;}

        $token_data=jwt::decode_token($token);

        if(!$token_data) {return false; }

        
          if(time()>intval($token_data["payload"]["exp"])) {
            // token expirado
            return false;
          }

        $header=$token_pieces[0];
        $payload=$token_pieces[1];
        $sign=$token_pieces[2];

        if(!isset($token_data['header']['alg'])){
            return false;
        }
        $algorithm=$token_data['header']['alg'];
        $alg=JWT::supported_algorithms[$algorithm];
        
        if($alg[0]=="hash_hmac") {
            $generated_sign=JWT::generate_sign($header,$payload,$secret_key,$algorithm);
            
            if($sign!=$generated_sign || !isset($token_data["payload"]))return false;
            if(!isset($token_data["payload"]["uid"])) return true;
            
            return $token_data["payload"]["uid"];
        } else {
            //entao é openssl
            return openssl_verify("$header.$payload",JWT::base64url_decode($sign),$secret_key, $alg[1]);
        }
    }

}


