$(document).ready(function(){
    
    var modules = new Array();
    
    $("button, input:submit, input:button").button();  
    $('form.jqform').jqTransform();
    
    $('.color').colorbox({
        innerWidth:"1025px",
        height:"600px", 
        iframe:true,
        scrolling: false,
        fastIframe: false
    });
    $('td[title], a[title]').qtip({
        style:{
            classes: 'ui-state-highlight ui-corner-all tooltip'
        },
        position:{
            my: 'left-top', 
            at: 'bottom-center'
        }
    });
    
    $( "#sort1, #sort2" ).sortable({
        connectWith: ".sortable"
    }).disableSelection();
 
    
    $('#sort1').bind("sortstop sortremove sortreceive", function(event,ui){
        modules = null;
        modules = new Array();
            
        modules = $('#sort1').sortable('toArray');
            
        document.getElementById("modules_hidden").value = modules;
    });
    
    $("#sort1, #sort2").bind("sortstart sortremove sortreceive sort", function(event, ui){
        $("td[title], a[title]").qtip('disable');  
    });
    
    $("#sort1, #sort2").bind("sortstop", function(event, ui){
        $("td[title], a[title]").qtip('enable');  
    });
    
});

function de()
{
    var lang = "index.php?action=setlang&lang=de"
    window.location.href = lang;
}

function en()
{
    var lang = "index.php?action=setlang&lang=en"
    window.location.href = lang;
}

function setconfig(file)
{
    var href = "index.php?action=set_config&configname=" + file;
    window.location.href = href;
}

function showViewer(config)
{
    var href="../index.php?config=" + config
    window.location.href= href;
}

function flushCache(config)
{
    var href="?action=fc&config="+config
    window.location.href=href;
}
function redirect()
{
    var href = "index.php?action=return";
    window.location.href = href;
}

