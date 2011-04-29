
function ts3_connect(host,port,server_pass_needed, serverpass, prompt_username, server_pass_i18n, nickname_i18n) {
    uri = 'ts3server://'+ host + '?port=' + port;
    
    // Checks If the password should be automatically added
    if(serverpass != "" && serverpass != null && serverpass != undefined)
    {
        uri = uri + '&pass=' + serverpass;
    }
    
    // Checks If password should be prompted
    if(server_pass_needed == true) {
        var pass = prompt(server_pass_i18n);
        if(pass == "" || pass == null || pass == undefined) {
            return;
        }
        uri = uri + '&pass=' + pass ;
    }

    // checks If username should be prompted
    if(prompt_username == true) {
        var name = prompt(nickname_i18n);
        if(name == "" || name == null || name == undefined) {
            return;
        }
        uri = uri + '&nickname=' + name;
    }

    var popup = window.open(uri);
    popup.close();


}

