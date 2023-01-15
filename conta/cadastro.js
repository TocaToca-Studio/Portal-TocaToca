let customizou_nick=false;
$(document).ready(function() {
    $("#form-cadastro input[name=nick]").on("keypress", function(event) {
        customizou_nick=true;
        let key = String.fromCharCode(event.which); 
           return 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ._-'
                    .indexOf(String.fromCharCode(key)) !=-1 ;
    }).on("paste", function(e) {
        e.preventDefault();
    });
    $("#form-cadastro input[name=nome]").keyup(function() {
        if(!customizou_nick) {
            $.get("/ajax/gerar-nickname?nome="+encodeURI($(this).val()))
            .done(function (e) {
                $("#form-cadastro input[name=nick]").val(e);
            });
        }
    });
});