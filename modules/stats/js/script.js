/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


// Jqplot
$(document).ready(function(){
    
    plot = $.jqplot('stats', [line1], {
        title: plotoptions.title,
        axesDefaults:{
            tickSpacing: 20
        },
        axes:{
            xaxis:{
                autoscale: true,
                renderer:$.jqplot.DateAxisRenderer,
                tickOptions:{
                    formatString: plotoptions.x_formatString
                }
            },
            yaxis:{
                tickInterval: 1,
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
        $( "#mstabs" ).bind( "tabsshow", function(event, ui) {
            plot.replot();
        });
    }
});
