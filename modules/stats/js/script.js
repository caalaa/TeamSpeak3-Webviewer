/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

plot = $.jqplot('stats', [line1], {
    title:'User History',
    axes:{
        xaxis:{
            renderer:$.jqplot.DateAxisRenderer,
            tickOptions:{
                formatString: '%#H:%M'
            }
        }
    },
    series:[{
        lineWidth:4, 
        markerOptions:{
            style:'square'
        }
    }]
});


