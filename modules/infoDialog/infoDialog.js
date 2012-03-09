/**
 *  This file is part of devMX TeamSpeak3 Webviewer.
 *  Copyright (C) 2011 - 2012 Max Rath and Maximilian Narr
 *
 *  devMX TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with devMX TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */

// Showing dialogs
jQuery(document).on('ready', function() {
    var ms_dialogs = new Array();
        
    jQuery('.devmx-webviewer').append('<div id=\"dialog\" style=\"overflow:hidden;\"><\/div>');

    jQuery('.client').hover(function() {
        var ms_title;
        var ms_id;
        ms_client = this;
        ms_id = jQuery(this).attr('id');
						
        ms_title = tswv.infoDialog.l10n.load;
        pos = getDialogPosition(this);
        
        // Configuration
        var dialogConfig =  {
            autoOpen: false,
            title: ms_title,
            resizeable: false,
            show: {
                effect: 'fadeIn', 
                duration: 200
            },
            hide: {
                effect: 'fadeOut', 
                duration: 200
            },
            height: tswv.infoDialog.height,
            width: tswv.infoDialog.width,
            position: [pos.x+20,pos.y+20]
        }
        
        ms_dialogs[ms_id] = jQuery('#dialog').html('<img  style=\" margin-left: 50%; margin-right:50%; margin-top: 25px;\" src=\"" . s_http . "modules/infoDialog/img/ajax-loader.gif\" alt=\"\"><\/img>').dialog(dialogConfig);
                                                                                
        ms_dialogs[ms_id].dialog('open');

        jQuery.ajax({
            url: tswv.s_http + 'modules/infoDialog/getHTML.php?type=client&id=' + ms_id + '&title=true&config=' + tswv.infoDialog.configfile,
            crossDomain: true,
            dataType: 'jsonp',
            success: function(data) 
            {
                ms_title = data.country + data.name;
            }

        });  
                                                                        
        jQuery.ajax({
            url: tswv.s_http + 'modules/infoDialog/getHTML.php?type=client&id=' + ms_id + '&config=' + tswv.infoDialog.configfile,
            crossDomain: true,
            dataType: 'jsonp',
            success: function(data) 
            {
                ms_dialogs[ms_id].dialog('option', 'title', ms_title);
                jQuery('#dialog').html(data.html);
            }

        });					
    },
    function(){
        if(tswv.infoDialog.closeOnMouseOut)
        {
            ms_dialogs[jQuery(this).attr('id')].dialog('close');
        }
    });								
});

// Returns the current position on the viewport of an object
function getDialogPosition(obj)
{
    var pos = {
        x: 0,
        y: 0
    };
    
    pos.x = jQuery(obj).offset().left - jQuery("body").scrollLeft();
    pos.y = jQuery(obj).offset().top - jQuery("body").scrollTop();
    
    return pos;
}
