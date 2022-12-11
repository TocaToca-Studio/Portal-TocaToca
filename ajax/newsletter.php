<?php 
require_once __DIR__.'/../core/config.inc.php';
$email=_post('email');
if($email && strval($email)>5) { 
    $email=db()->escape($email);

    $already_exists=db()->fetch_value("SELECT COUNT(*) FROM newsletter WHERE email='$email'");

    if($already_exists) {
        json_response([
            "message"=>__("Você já se cadastrou em nosso canal de notícias.")
        ]);
    } else { 
        db()->query(
            insert_query_sql('newsletter',["email"=>$email])
        );
        json_response([
            "message"=>__("Obrigado por se cadastrar em nosso canal de notícias!")
        ]);
    }
}
json_response([
    'message'=>__('Ocorreu um erro, tente novamente mais tarde.')
]);