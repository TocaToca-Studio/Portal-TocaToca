$(document).ready(function() {
    $('.banner-home').slick({
        infinite:true,
        autoplay:true,
        speed:500,
        autoplayspeed:1500
    });

    $("#form-newsletter").submit(function(e) {
        //alert('----');
          $.ajax({
            url: "/ajax/newsletter",
            type: "POST",
            dataType: "html",
            accepts: {
                text: "application/json"
            },
            data:{
                email:$("#form-newsletter input[name=email]").val()
            }
          }).done(function(data) {
            $("#nl-response").html(JSON.parse(data).message);
            $("#modal-nl").modal('show');
          });  
        e.preventDefault();
        this.reset();
        return false;
    }); 

    $('form.blockcaptcha').submit(function(e) {
      let rec=$(this).find("textarea[name=g-recaptcha-response]");
      if(rec && !rec.val().length>0) {
        alert('Por favor complete o desafio do google antes de continuar!');
        e.preventDefault();
        return false;
      }
    });

    $('#botao-cookies').click(function() {
      $.get('/ajax/aceita-cookies').done(function() {
        $("#alerta-cookies").hide();
      });
    });
});

$(".latin_letters").on("keypress", function(event) {
  let englishAlphabetAndWhiteSpace = /^[-@./#&+\w\s]*$/;
      let key = String.fromCharCode(event.which);
      if (event.keyCode == 8 || event.keyCode == 231 || event.keyCode == 39 || englishAlphabetAndWhiteSpace.test(key)) {
        return true;
      }
      return false;
  });
$('.latin_letters').on("paste", function(e) {
    e.preventDefault();
});


function open_sidemenu() {
  $(".sidemenu-overlay").addClass("show");
}
function close_sidemenu() {
  $(".sidemenu-overlay").removeClass("show");
}