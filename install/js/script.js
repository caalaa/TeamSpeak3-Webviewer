$(document).ready(function(){
    
    var modules = new Array();
    
    $("button, input:submit, input:button, .button").button();  
    $('form.jqform').jqTransform();
    
    $('.color').colorbox({
        innerWidth:"1025px",
        height:"650px", 
        iframe:true,
        scrolling: false,
        fastIframe: false
    });
    $('td[title], a[title], span[title], p[title]').qtip({
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
    
    modules = $('#sort1').sortable('toArray');
    document.getElementById("modules_hidden").value = modules;
 
    
    $('#sort1').bind("sortstop sortremove sortreceive", function(event,ui){
        modules = null;
        modules = new Array();
            
        modules = $('#sort1').sortable('toArray');
            
        document.getElementById("modules_hidden").value = modules;
    });  
    
    $(".warning, .info, .alert").delay(500).fadeIn(500);
    
    // Hide username and password field if login needed = false
    $("#login-needed-false").change(function(){
        if($("#login-needed-false").attr("checked") == "checked")
        {
            $("#config-username, #config-password").fadeOut(500);
        }  
    });
    
    if($("#login-needed-false").attr("checked") == "checked")
    {
        $("#config-username, #config-password").fadeOut(500);
    } 
    
    // Hide username and password field if login needed = false
    $("#login-needed-true").change(function(){
        if($("#login-needed-true").attr("checked") == "checked")
        {
            $("#config-username, #config-password").fadeIn(500);
        }  
    });
    

    // Hide imgaepack if servericons get automatically downloaded
    $("#servericons-true").change(function(){
        if($("#servericons-true").attr("checked") == "checked")
        {
            $("#imagepack-config").fadeOut(500);
        }
    });
    
    if($("#servericons-true").attr("checked") == "checked")
    {
        $("#imagepack-config").fadeOut(500);
    }
    
    // Hide imgaepack if servericons get automatically downloaded
    $("#servericons-false").change(function(){
        if($("#servericons-false").attr("checked") == "checked")
        {
            $("#imagepack-config").fadeIn(500);
        }
    });
});

// Sets the requested language
function setLang(language)
{
    var lang = "index.php?action=setlang&lang=" + language;
    window.location.href = lang;
}

// Sets the requested configfile to edit
function setconfig(file)
{
    var href = "index.php?action=set_config&configname=" + file;
    window.location.href = href;
}

// Shows the viewer of the requested configfile
function showViewer(config)
{
    var href="../index.php?config=" + config;
    window.location.href = href;
}

// Flushs the cache of the requested configfile
function flushCache(config)
{
    var href="?action=fc&config="+config;
    window.location.href = href;
}

// Deletes the requested configfile
function deleteConfig(config)
{
    var href="?action=delete&config="+config
    window.location.href = href;
}

