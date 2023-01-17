let customizou_nick=false;
$(document).ready(function() {
    $("#form-cadastro input[name=nick]").on("keypress", function(e) {
        customizou_nick=true; 
        let ret='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ._-'.indexOf(String.fromCharCode(e.which)) !=-1 ;
        //alert(e.which);
        $(this).val($(this).val().normalize("NFD").replace(/[\u0300-\u036f]/g, ""));
        return ret;
    }).on("keyup",function () {
        $(this).val($(this).val().normalize("NFD").replace(/[\u0300-\u036f]/g, ""));
    }).on("change",function () {
        $(this).val($(this).val().normalize("NFD").replace(/[\u0300-\u036f]/g, ""));
    })
    .on("paste", function(e) {
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