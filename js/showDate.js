/**
 * Created by waqar on 11/4/2017.
 */


function dateShower()
{
    var d=new Date();

    var day=d.getDate();
    var month=d.getMonth();
    var year=d.getFullYear();

    //date
    var sh=day.toString();

    //month
    if(month==0)
        sh+=" january, ";
    else if(month==1)
        sh+=" february, ";
    else if(month==2)
        sh+=" march, ";
    else if(month==3)
        sh+=" april, ";
    else if(month==4)
        sh+=" may, ";
    else if(month==5)
        sh+=" june, ";
    else if(month==6)
        sh+=" july, ";
    else if(month==7)
        sh+=" august, ";
    else if(month==8)
        sh+=" september, ";
    else if(month==9)
        sh+=" october, ";
    else if(month==10)
        sh+=" november, ";
    else
        sh+=" december, ";

    sh+=year.toString();

    document.getElementById("tt").innerHTML=sh;
}



