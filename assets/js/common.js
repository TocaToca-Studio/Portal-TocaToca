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
});


function open_sidemenu() {
  $(".sidemenu-overlay").addClass("show");
}
function close_sidemenu() {
  $(".sidemenu-overlay").removeClass("show");
}