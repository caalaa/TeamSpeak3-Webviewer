/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Jqplot
$(document).ready(function(){
    
    plot = $.jqplot('stats', [line1], {
        title:'User History',
        axesDefaults:{
            tickSpacing: 20
        },
        axes:{
            xaxis:{
                autoscale: true,
                renderer:$.jqplot.DateAxisRenderer,
                tickOptions:{
                    formatString: '%#H:%M'
                }
            },
            yaxis:{
                tickInterval: 1,
                autoscale: true,
                tickOptions:{
                    formatString: '%d'
                }
                
            }
        
        },
        series:[{
            lineWidth:4, 
            markerOptions:{
                style:'filledCircle'
            }
        }]
    }); 
    
    // JQuery UI Tabs workaround
    $( "#mstabs" ).bind( "tabsshow", function(event, ui) {
        plot.replot();
    });
});
