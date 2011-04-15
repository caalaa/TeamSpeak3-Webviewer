/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
});


