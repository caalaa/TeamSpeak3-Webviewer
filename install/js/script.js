$(document).ready(function(){
    $("button, input:submit, input:button").button();  
    $('form.jqform').jqTransform();
    $('.color').colorbox({innerWidth:"1025px",
                        height:"600px", 
                        iframe:true,
                        scrolling: false,
                        fastIframe: false});
    $('td[title]').qtip({
        style:{classes: 'ui-state-highlight ui-corner-all'},
        position:{my: 'left-top', at: 'bottom-center'}
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

function redirect()
{
    var href = "index.php?action=return";
    window.location.href = href;
}

