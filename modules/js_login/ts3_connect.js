/* Connects to a TeamSpeak Server */
function ts3_connect(host,port,server_pass_needed, serverpass, prompt_username, server_pass_i18n, nickname_i18n) 
{  
    var username = "Homepage-User";
    var pass;
    
    uri = 'ts3server://'+ host + '?port=' + port;
    
    var dialogOptions = {
        "title": "devMX Webviewer",
        "modal": true,
        "resizable": false,
        "draggable": false,
        "width": "400px",
        "show": "fade",
        "hide": "fade"    
    };
    
    var dialogOptionsUser = {
        "buttons": [{
            text: "OK", 
            click: function() { 
                username = jQuery("#jsUserVal2").val();
                jQuery("#jsUser").remove();
                openLink(false, username, uri, null);
                jQuery(this).dialog("close"); 
            }
        }]
    }
    
    var dialogOptionsUserPass = {
        "buttons": [{
            text: "OK",
            click : function () {
                username = jQuery("#jsUserVal1").val();
                pass = jQuery("#jsPassVal1").val();
                jQuery("#jsUserPass").remove();
                openLink(true, username, uri, pass);
                jQuery(this).dialog("close");
            }
        }]
    }
    
    var dialogOptionsPass = {
        "buttons": [{
            text: "OK",
            click: function(){  
                
                if(pass == "" || pass == null || pass == undefined) 
                {
                    pass = "";
                    jQuery("#jsPass").remove();
                }
                else
                {
                    pass = jQuery("#jsPassVal3").val();
                    jQuery("#jsPass").remove();
                }
                openLink(true, null, uri, pass);           
                jQuery(this).dialog("close");
            }
        }]
    }
   
   
    // Checks If the password should be automatically added
    if(serverpass != "" && serverpass != null && serverpass != undefined && serverpass != "0")
    {
        uri = uri + '&password=' + serverpass;
    }
    
    // Ask for password and username
    if(server_pass_needed == true && prompt_username == true) 
    {
        //var pass = prompt(server_pass_i18n);  
    
        if(jQuery("#jsPassUser").length == 0)
        {
            jQuery("body").append('<div id="jsPassUser" class="ui-widget"><p>' + server_pass_i18n + '</p><p><input class="ui-widget ui-corner-all ui-widget-content" style="padding: 5px;" type="text" id="jsPassVal1" /></p><br><p>' + nickname_i18n + '</p><p><input type="text" id="jsUserVal1" class="ui-widget ui-corner-all ui-widget-content" style="padding: 5px;" /></p></div>');
        }
        
        jQuery("#jsPassUser").dialog(dialogOptions, dialogOptionsUserPass);
    }
    // Ask for username only
    else if(prompt_username == true) 
    {   
        if(jQuery("#jsUser").length == 0)
        {
            jQuery("body").append('<div id="jsUser" class="ui-widget"><p>' + nickname_i18n + '</p><p><input class="ui-widget ui-corner-all ui-widget-content" style="padding: 5px;" type="text" id="jsUserVal2" /></p></div>');
        }
        
        jQuery("#jsUser").dialog(dialogOptions, dialogOptionsUser);
    }
    // Ask for password only
    else if (server_pass_needed == true)
    {
        if(jQuery("#jsPass").length == 0)
        {
            jQuery("body").append('<div id="jsPass" class="ui-widget"><p>' + server_pass_i18n + '</p><p><input class="ui-widget ui-corner-all ui-widget-content" style="padding: 5px;" type="text" id="jsPassVal3" /></p></div>');    
        }
        
        jQuery("#jsPass").dialog(dialogOptions, dialogOptionsPass);
    }
    // Ask for nothing
    else
    {
        openLink(false, null, uri, null);
    }
}

function openLink(password, username, uri, pass)
{         
    if(username == "" || username == null || username == undefined) 
    {
        username = "Homepage-User";
    }
    
    if(password == false || password == null)
    {
        uri = uri + '&nickname=' + username;
        var popup = window.open(uri);
        popup.close();     
    }
    else
    {
        uri = uri + '&nickname=' + username + '&password=' + pass;
        var popup = window.open(uri);
        popup.close(); 
    }
}

