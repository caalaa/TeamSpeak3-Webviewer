function ms_getPosition(obj)
{
    var pos = {
        x:0, 
        y:0
    };

    do {
        pos.x += obj.offsetLeft;
        pos.y += obj.offsetTop;
    }
    while (obj = obj.offsetParent);
    return pos;
}


function pausecomp(millis)
{
    var date = new Date();
    var curDate = null;

    do {
        curDate = new Date();
    }
    while(curDate-date < millis);
} 

