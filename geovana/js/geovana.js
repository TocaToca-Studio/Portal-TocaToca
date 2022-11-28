debug_mode=false;
superdebug=false;
running_ajax=false;

// B64 is base64 encoder from base64.js

function encode_params(params) {
    return B64.encode(JSON.stringify(params));
}
function decode_params(text) {
    return JSON.parse(B64.decode(text));
}
function galert(text) {
  if(superdebug) {alert(text);}
}
var statevars=[];
function get_form_data(form) {
    return $(form).serializeArray().reduce(function(obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});
}

function process_postback(result,control_id) {

     if(result !== null && result !== undefined && result!=='NO_POSTBACK') {

         galert('retornou '+result);
         update_statevars(result);
         jQuery.each ($('#'+control_id), function (index,item) {
            galert('chegou o valor "'+ result +'" do '+control_id);
            if(control_id==='div_postback') {
                galert('inseriu no '+result+' div_postback');
                 $('#div_postback').append($("<div></div>").html(result));

                fix_load_events();
                $(document).trigger( "postback");
            } else {
                if($(item).html()!==result) {
                    $(item).html(result);
                    if(superdebug) {alert('atualizou '+control_id);}
                    fix_load_events();
                    $(document).trigger( "postback");
                } 
            }
        });
        
      } else {

         galert('retornou nada');
          if(debug_mode) {
            $('#'+control_id).html("invalid server return");
          }
      }

    galert('processou postback');
}
function object_to_formdata(obj,form) {

    var form_data;
    if(form) {
        form_data=new FormData(form);
    } else {
        form_data=new FormData();
    }

    for ( var key in obj ) {
        form_data.append(key, obj[key]);
    }
    return form_data;
}
function update_control(control_id) {
  galert('tentando atualizar '+control_id);
  if(running_ajax) {
    galert('abortou  '+control_id+' porque outra requisiçao ja estava em andamento');
    return false;
  }
   $.ajax({
    type: 'POST',
    data:object_to_formdata({
        geo_control_id:control_id,
        statevars:JSON.stringify(statevars),
    }),
    async: true,
    contentType: false,
    processData: false
  }).done(function (result){
     process_postback(result,control_id);
  }).fail(function() {
     if(debug_mode) {
            $('#'+control_id).html("error on server request");
          }
  }).always(function () {
  }); 
}
function call_page(fn_name,params,control_id){

    disable_all_form_submit();
    if(params===undefined) {
        params=[];
    }
    if(control_id===undefined) {
        control_id='div_postback';
    }
 galert('chamou a funcao '+fn_name);
 if(params) {
     galert('parametros '+JSON.stringify(params));
 }
 console.log(object_to_formdata({
    fn_name:fn_name,
    params:JSON.stringify(params),
    statevars:JSON.stringify(statevars),
    geo_control_id:control_id
}));
 return $.ajax({
    type: 'POST',
    data: object_to_formdata({
        fn_name:fn_name,
        params:JSON.stringify(params),
        statevars:JSON.stringify(statevars),
        geo_control_id:control_id
    }),
    async: true,
    contentType: false,
    processData: false,
    cache: false
  }).done(function (result){
    galert('ok ajax');
     process_postback(result,control_id);
  }).fail(function() {
    galert('erro ajax');
     if(debug_mode) {
            galert("error on server on function "+fn_name);
      }
  }).always(function () {
    enable_all_form_submit();
    }); 
}


function update_statevars(result) {
    if(result.includes("<!--$")) {
        statevars=JSON.parse(B64.decode(result.slice(result.lastIndexOf("<!--$")+5,result.lastIndexOf("-->"))));
        for(var v in statevars) {
            if(statevars[v]==="GEOVANA_UNSET") {
                delete statevars[v];
            }
        }
    }
}

var blockeventsfn = function(event){
     event.stopPropagation();
};
function disable_all_form_submit() {
    $('form').on('submit', blockeventsfn);
    $("[type=submit]").prop('disabled', true);
    $("select").prop('disabled', true);
    //$("input").prop('disabled', true);
}

function enable_all_form_submit() {
    $('form').off('submit', blockeventsfn);
    $("[type=submit]").prop('disabled', false);
    $("select").prop('disabled', false);
   // $("input").prop('disabled', false);
}

function send_form(fn_name,form,control_id){
    galert('enviando formulario '+JSON.stringify(get_form_data(form)));
    var form_data=object_to_formdata({
        form_name:fn_name,
        params:JSON.stringify(get_form_data(form)),
        statevars:JSON.stringify(statevars),
        geo_control_id:control_id
    });
    form.find("input[type=file]").each(function() {
        for(var c=0;c<this.files.length;c++) {
            form_data.append($(this).attr("name")+'[]',this.files[c]);
        }
    });

 $.ajax({
    type: 'POST',
    data:form_data,
    async: true,
    contentType: false,
    processData: false,
  }).done(function (result){
      // add reset-on-submit class
      //$(form).trigger("reset");
        galert('recebeu o valor '+result+' do formulario '+fn_name);
     process_postback(result,control_id);
  }).fail(function() {
     if(debug_mode) {
            alert("error on server on form "+fn_name);
      }
  }).always(function () {
      enable_all_form_submit();
  }); 

  disable_all_form_submit();
}
function auto_update(item,refresh_interval){
  return setInterval(function(){
    update_control(item);
  }, refresh_interval);
}


function fix_load_events() {
    jQuery.each ($("img"), function (index, item) {
        if(!$(item).hasClass("geovana-image-fallback-ok")) {
            $(item).addClass("geovana-image-fallback-ok");
            $(item).on("error", function () {
                var url_image_failed=$(item).attr("src");
                $(item).prop('src','/geovana/res/noimage.png');
               // this.src='/geovana/res/noimage.png';
                // salva o url da imagem bugada no atributo data-url da imagem
               // $(item).attr('data-url','deu ruim aqui');
            });
        }
    });
    
   
    jQuery.each ($('input[data-toggle="toggle"]'), function (index, item) {
        if(!$(item).hasClass("geovana-ok")) {
            $(item).addClass("geovana-ok");
            $(item).bootstrapToggle();
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="tooltip"]').removeAttr('data-toggle');

   jQuery.each ($("[data-mask]"), function (index, item) {
        if(!$(item).hasClass("geovana-ok")) {
            $(item).addClass("geovana-ok");

            $(item).mask($(item).attr ("data-mask"));
        }
    });
    
    jQuery.each ($("[onload]"), function (index, item) {
         if(!$(item).hasClass("geovana-ok")) {
            $(item).addClass("geovana-ok");

            $(item).prop ("onload").call (item);
            $(item).removeAttr('onload');
         }
    });
    jQuery.each ($("[data-updatetime]"), function (index, item) {
        if(!$(item).hasClass("geovana-ok")) {
             $(item).addClass("geovana-ok");
             auto_update($(item).prop('id'),parseInt($(item).attr("data-updatetime")));
          }
    });
    jQuery.each ($("[data-formfunction]"), function (index, item) {
        if(!$(item).hasClass("geovana-ok")) {
            $(item).addClass("geovana-ok");
            $(item).submit(function (event) {
                
               event.preventDefault();
               
               if($(item)[0].checkValidity) {
                    if($(item)[0].checkValidity()) {
                        if(running_ajax) {
                            alert('A página ainda está carregando sua solicitação, aguarde.');
                            return false;
                        }
                        send_form($(item).attr("data-formfunction"),$(item),$(item).attr("data-formresponse"));
                    }
                    $(item)[0].reportValidity();
                }
               return false;
            });
        
        }
    });
     jQuery.each ($("[data-onclick]"), function (index, item) {
        if(!$(item).hasClass("geovana-ok")) {
            $(item).addClass("geovana-ok");
            $(item).click(function () {
                if(running_ajax) {
                    alert('A página ainda está carregando sua solicitação, aguarde.');
                    return false;
                }
                params=false;
                encoded_params=$(item).attr("data-params");
                if(encoded_params) {
                     params=decode_params(encoded_params);
                }
                if(!params) { params={};}
                params["control_id"]=$(item).attr("id");
                params["value"]=$(item).val();
               
                control_id=$(item).attr("data-response");
                call_page($(item).attr("data-onclick"),params,control_id);
                
               return false;
            });
        }
    });
     jQuery.each ($("[data-onchange]"), function (index, item) {
        if(!$(item).hasClass("geovana-onchange-ok")) {
            $(item).addClass("geovana-onchange-ok");
            $(item).change(function () {
                if(running_ajax) {
                    alert('A página ainda está carregando sua solicitação, aguarde.');
                    return false;
                }
                params=false;
                encoded_params=$(item).attr("data-params");
                if(encoded_params) {
                     params=decode_params(encoded_params);
                }
                if(!params) { params={};}
                params['control_id']=$(item).attr("id");
                params['value']=$(item).val();
                // se for checkbox entao tem que passar a propiedade cheched
                if($(item).attr('type')==='checkbox') {
                    params['value']=$(item).prop('checked');
                }
                galert(JSON.stringify(params));
                control_id=$(item).attr("data-response");
                call_page($(item).attr("data-onchange"),params,control_id);
                $(item).addClass("geovana-ok");
               return false;
            });
        }
    });
    jQuery.each ($(".custom-file-input"), function (index, item) {
        if(!$(item).hasClass("geovana-ok")) {
            $(item).addClass("geovana-ok");
            $(item).change(function () {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        }
    });
    
    jQuery.each ($(".autopostback"), function (index, item) {
        if(!$(item).hasClass("geovana-ok")) {
            $(item).addClass("geovana-ok");
            $(item).change(function () {
                // nao implementado ainda
            });
        }
    });
     $(".btn").hover(
      function() {
        $(this).addClass('shadow'); 
      }, function() {
        $(this).removeClass('shadow');
      }
    );
    $(".hover-shadow").hover(
      function() {
        $(this).addClass('shadow-lg').css('cursor', 'pointer'); 
      }, function() {
        $(this).removeClass('shadow-lg');
      }
    );
     $(".hover-shadow-md").hover(
      function() {
        $(this).addClass('shadow').css('cursor', 'pointer'); 
      }, function() {
        $(this).removeClass('shadow');
      }
    );
    $(".hover-shadow-sm").hover(
      function() {
        $(this).addClass('shadow-sm').css('cursor', 'pointer'); 
      }, function() {
        $(this).removeClass('shadow-sm');
      }
    );    
    jQuery.each ($(".modal-show" ), function (index, item) {
        $(item).modal('show');
        $(item).removeClass('modal-show');
        $(item).addClass("geovana-ok");
    });
     jQuery.each ($(".autoscroll-down" ), function (index, item) {
        $(item).prop("scrollTop", $(item).prop("scrollHeight"));
        $(item).removeClass('autoscroll-down');
        $(item).addClass("geovana-ok");

    });
    

    
}
$(document).ready (function () {
    update_statevars($("html").html());
    fix_load_events();
});



function remove_spaces(str) {
    return str.replace(/\s/g,'');
}


/// configuração da bilioteca de notificação noty
Noty.overrideDefaults({
    theme: 'bootstrapTheme', // or relax
    type: 'notification', // success, error, warning, information, notification

    dismissQueue: true, // [boolean] If you want to use queue feature set this true
    force: false, // [boolean] adds notification to the beginning of queue when set to true
    maxVisible: 5, // [integer] you can set max visible notification count for dismissQueue true option,
  
    timeout: 1000, // [integer|boolean] delay for closing event in milliseconds. Set false for sticky notifications
    progressBar: true, // [boolean] - displays a progress bar

    closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
});