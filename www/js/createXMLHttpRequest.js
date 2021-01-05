function createXMLHttpRequest(){
 
    var xmlHttp;
    if(window.ActiveXObject){
                 try{                                
                              xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
                 } catch(e){
                        try{
                              xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch(e1){
                              xmlHttp = null;
                        }
                 }
    } else if(window.XMLHttpRequest){ 
        try{                       
                        xmlHttp = new XMLHttpRequest();
           } catch(e){
                        xmlHttp = null;
           }
    }
           

    if(xmlHttp == null) errorMessage();
    return xmlHttp;
}



function errorMessage(){
    alert("error:xmlHttp initialization failed");
}       
