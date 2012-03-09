// Jqplot
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
jQuery(document).on("ready", function(){
    jQuery.jqplot.config.enablePlugins = true;
    plot = jQuery.jqplot('stats', [line1], {
        title: plotoptions.title,
        axesDefaults:{
            tickSpacing: 20
        },
        axes:{
            xaxis:{
                autoscale: true,
                renderer:jQuery.jqplot.DateAxisRenderer,
                tickOptions:{
                    formatString: plotoptions.x_formatString
                }
            },
            yaxis:{
                tickInterval: 1,
                min: plotoptions.min,
                autoscale: true,
                tickOptions:{
                    formatString: plotoptions.y_formatString
                }
                
            }
        
        },
        series:[{
            lineWidth:plotoptions.lineWidth,
            markerOptions:{
                style:plotoptions.style
            }
        }]
    }); 
    
    // JQuery UI Tabs workaround
    if(plotoptions.tab == true)
    {
        jQuery( "#mstabs" ).bind( "tabsshow", function(event, ui) {
            plot.replot();
        });
    }
});
