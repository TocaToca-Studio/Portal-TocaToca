<?php
require_once __DIR__.'/JWT.php';
/* nao é utilizado php sessions para login.
 e também para confundir os hackers e eles pensarem que o site usa php sessions
 porem o site usa um cookie com um token JWT para segurança
*/
session_start();

/**
 * essa classe inclui as funções para a validação e login do usuario
 * ele deixa o login armazenado em um cookie do navegador 
 */
class LoginTool {
    
    /** Chave secreta usada criptografar tokens JWT */
    public const SECRET_KEY='tr1web10@';
    
    /** coloquei esse nome genério no cookie de login justamente para ninguém saber que esse é o cookie de login */
    public const COOKIE_SESSION_NAME="javascript_html_data";
    /**
     *  checa se o usuario está logado caso esteja retorna o id que está no token
     *  primeiro ele valida se o token é realmente válido usando o validador JWT
     */
    public static function get_logged_user_id() {
        if(filter_has_var(INPUT_COOKIE,LoginTool::COOKIE_SESSION_NAME)) {
            return JWT::validate_token(filter_input(INPUT_COOKIE,LoginTool::COOKIE_SESSION_NAME),LoginTool::SECRET_KEY);
        } else {
            return false;
        }
    }
    /** checa se o usuario esta logado com a funcao get_logged_user_id() */
    public static function is_logged() {
        return LoginTool::get_logged_user_id();
    }
    public static function generate_access_token($user_id) {
        return JWT::generate_user_token($user_id,LoginTool::SECRET_KEY);
    }
    /** faz login armazenando o token jwt no navegador do usuario */
    public static function login($user_id) {
        setcookie(
            LoginTool::COOKIE_SESSION_NAME,
            LoginTool::generate_access_token($user_id),
            time()+3600*24*360,
        "/");
    }
    /** faz logout apagando o cookie do navegador */
    public static function logout() {
        unset($_COOKIE[LoginTool::COOKIE_SESSION_NAME]); 
        setcookie(LoginTool::COOKIE_SESSION_NAME, null, -1, '/'); 
    }

    /** obtem uma identificação única do dispositivo gerado aleatoriamente e salvo em cookie */
    public static function device_id() {
        // se encontrar o token
        if(filter_has_var(INPUT_COOKIE,'device_id')) {
            // valida e decodifica o token jwt e retorna o id do dispositivo
            return JWT::validate_token(filter_input(INPUT_COOKIE,'device_id'),LoginTool::SECRET_KEY);
        } else {
            // gera um id aleatório e coloca em um token dentro de um cookie
            $new_random_id=Utils::random_string(40);
            setcookie('device_id',JWT::generate_user_token($new_random_id,LoginTool::SECRET_KEY),time()+3600*24*360,"/");
            return $new_random_id;
        }
    }
    /** 
     * redireciona para a raiz do site se estiver logado, 
     * se for especificado um url então será direcionado para lá
     * se existir um parametro get na página chamado redirect então redireciona para ele
     * ao invés de mandar para a home direto.
     * isso é util em alguns casos, como por exemplo quando o usuário precisa fazer login 
     * antes de fazer determinada ação, então manda para /account/login?redirect=/pagina-desejada
     * depois que o usuário fizer login 
     */
    public static function deny_logged_users($destination="/") {
        if(LoginTool::is_logged()) {
            if(filter_has_var(INPUT_GET,'redirect')){
                Utils::redirect(filter_input(INPUT_GET,'redirect'));
            } else Utils::redirect($destination);
        }
    }

    /** 
     * redireciona para a raiz do site se estiver deslogado, 
     * se for especificado um url então será direcionado para lá
     * se existir um parametro get na página chamado redirect então redireciona para ele
     * ao invés de mandar para a home direto.
     * isso é util em alguns casos, para sugerir que o usuario precisa fazer login antes de ir para a página 
     * antes de fazer determinada ação, então manda para /account/login?redirect=/pagina-desejada
     * depois que o usuário fizer login 
     */
    public static function deny_unlogged_users($login_page="/") {
        if(!LoginTool::is_logged()) {
            if(filter_has_var(INPUT_GET,'redirect')){
                Utils::redirect(filter_input(INPUT_GET,'redirect'));
            } else Utils::redirect($login_page);
        }
    }

}





