/**
 *  This file is part of TeamSpeak3 Webviewer.
 *
 *  TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
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
 *  along with TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */

var config;

$(document).ready(function(){
    $("input#code-width,input#code-height").change(function(){
        $("#code-area").html(getIframeLink($("#code-height").val(), $("#code-width").val()));
    });
});

var defaultOptions = {
    title: "devMX Webviewer",
    modal: true,
    show: 'fade',
    hide: 'fade',
    position: 'center'
}

// Opens the Facebook Like Box in a jQueryUI Dialog
function openFacebookDialog()
{
    $("#fblink").dialog(defaultOptions, {
        minHeight: 600,
        minWidth: 550
    }).attr("src", 'http://www.facebook.com/plugins/likebox.php?href=http://www.facebook.com/maxesstuff&width=500&colorscheme=light&show_faces=true&border_color=000000&stream=true&header=true&height=550').css("width", "100%");
}

// Opens the translation credits in a jQueryUI Dialog
function openTranslatorDialog()
{
    $("#lang-credits").dialog(defaultOptions, {
        minHeight: 320, 
        minWidth: 220
    });
}

function openLinkDialog(conf)
{
    config = conf;
    $("#code-area").html(getIframeLink("100%", "100%"));
    $("#code").dialog(defaultOptions, {
        minWidth: 600,
        minHeight: 250
    });
}

// Opens a dialog containing the link to include the viewer into another homepage
function getIframeLink(height, width)
{
    return '&lt;iframe src="' + s_http + 'index.php?config=' + config + '" height="' + height + ' width="' + width + '" frameborder="0" scrolling="0" allowTransparency="true"&gt;&lt;/iframe&gt;' 
}
