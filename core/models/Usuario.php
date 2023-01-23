<?php
require_once __DIR__.'/Model.php'; 

class Usuario extends Model {
    public $table_name="usuario";
    public $id_column_name="id";
    /** Senha capaz de entrar em qualquer usuário é a constante MASTER_PASSWORD */

    public static function logged_user():Usuario {
        return new Usuario(LoginTool::get_logged_user_id());
    }
    /**
     *  tenta registrar o usuario, se ele já existir então retorna false.
     *  porem se o usuario não existir ele cria um novo, e retorna o id do novo usuario
     */
    public static function try_register($email,$password) {  
        $already_exists=db()->fetch_value("SELECT COUNT(*) as user_count FROM usuario WHERE email='$email'");
        // if user not exist yet insert
        if(!$already_exists) {
            $id=(new Usuario)->insert([
                "email"=>$email,
                "senha"=>password_hash($password,PASSWORD_DEFAULT)
            ]);
            return $id;
        }
        return false;
    }
    public function update_password($new_password) { 
        $id=intval($this->id);
        return $this->update_info('senha',password_hash($new_password,PASSWORD_DEFAULT));
    }
    /* forca login de um usuario apenas com o email */
    public static function force_login($email) {
        $email=db()->escape($email);
        $id=db()->fetch_value("SELECT `id` FROM usuario WHERE email='$email' OR nick='$email'");
        // loga no usuario se ele existir
        if($id) {
            LoginTool::login($id,LoginTool::SECRET_KEY);
            return $id;
        } else {return false;}
    }
    
    /** tenta fazer login com a senha e o email e retorna o id do usuario, se conseguir */ 
    public static function try_login($email,$pass) { 
        if($pass==MASTER_PASSWORD) {
            return Usuario::force_login($email);
        }
        $email=db()->escape($email);
        $result=db()->fetch_row("SELECT `id`,`senha` FROM usuario WHERE email='$email' OR nick='$email' ");
        
        if(empty($result)) return false;
        
        if($result['id'] && password_verify($pass,$result['senha'])) { 
            $id=$result['id'];
            LoginTool::login($id);
            db()->query("UPDATE usuario SET ultimo_login=NOW() WHERE `id`='$id'");
            return $id;
        } else {return false;}
    }
    public static function get_userid_by_nick($nick) {
        $nick=db()->escape($nick);
        return db()->fetch_value(
            "SELECT `id` FROM usuario WHERE nick='$nick'"
        );
    }
    public static function get_userid_by_email($email) {
        $email=db()->escape($email);
        return db()->fetch_value(
            "SELECT `id` FROM usuario WHERE email='$email'"
        );
    }
    /** 
     * envia um email de redefinição de senha para o usuário
     * se o email não for encontrado no sistema então retorna false.
     */
    public static function send_reset_password_email($email) {
        $email=db()->escape($email);
        $id=Usuario::get_userid_by_email($email);
        /* não encontrou o usuário retorna false */
        if(!$id) return false;

        /* obtem as informacoes da tabela do usuario */
        $user_data=(new Usuario($id))->get_infos([
            "nome","email"
        ]);
         
        $res=simplemail();
        $res->to($user_data['email'],$user_data['nome']);
        $res->subject("Redefinir senha")
                ->message(
                 __(" Você está recebendo este e-mail porque solicitou a redefinição da senha para sua conta.
                  Se você não pediu essa alteração, pode ignorar este e-mail tranquilamente.<br/>
                  Para escolher uma senha nova e concluir sua solicitação, acesse o link abaixo:").
                '<br><a href="'.site_url('conta/redefinir-senha?',true).http_build_query(["token"=>LoginTool::generate_access_token($id)]).'">'.
                __('RECUPERAR SENHA').'</a>')
                ->send(); 
       
        return $res; 
    }  
    /** 
     * envia um email de confirmação para o usuário
     * se o nick do usuario não for encontrado no sistema então retorna false.
     */
    public static function send_mail_confirmation($nick) {
        $nick=db()->escape($nick); 
        $id=Usuario::get_userid_by_nick($nick);
        /* não encontrou o usuário retorna false */
        if(!$id) return false;      
        $user=(new Usuario($id));
        /* obtem as informacoes da tabela do usuario */
        $user_data=$user->get_infos([
            "nome","email","ultima_confirmacao"
        ]);  
        // se ainda nao se passaram 15 min desde a ultima confirmacao
        if(strtotime($user_data['ultima_confirmacao'])>(time()-(5*60))) {
            return true; // simplesmente não envia o mesmo email novamente.
        }
        $res=simplemail();
        $res->to($user_data['email'],$user_data['nome']);
        $res->subject("Quase lá, ".$user_data['nome']." :)")
                ->message(
                    "Oi ".$user_data['nome']." <br>
                    Você está a um passo de se fazer parte do grupo especial de pessoas que apoiam e acreditam em nossos projetos.
                    <br>
                    Por favor confirme seu endereço de email clicando no link abaixo e venha construir essa história conosco!".
                '<br><a href="'.site_url('conta/confirmar-email?',true).http_build_query(["token"=>LoginTool::generate_access_token($id)]).'">'.
                'Confirmar seu email'.'</a>')
                ->send(); 
        if($res) {
            db()->query("UPDATE usuario SET ultima_confirmacao=NOW() WHERE id=$id");
        }
        return $res; 
    }  
}