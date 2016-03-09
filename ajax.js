function makeinput(td){
 if(td.innerHTML.substr(0,5).toLowerCase()!="<form"){
  td.innerHTML="<form onsubmit='senddata(this);return false'><div><input id='f::"+td.id+"' value='"+td.innerHTML+"'></div><div><input type='submit' value='Update' class='button'></div></form>";
  document.getElementById("f::"+td.id).select();
 }
}
function senddata(form){
 form.childNodes[0].childNodes[0].style.display="none";
 form.childNodes[1].childNodes[0].value="Processing";
 form.childNodes[1].childNodes[0].style.backgroundColor="gray";
 form.childNodes[1].childNodes[0].disabled="true";
 td_id=form.childNodes[0].childNodes[0].id.substr(3);
 td_value=encodeURIComponent(form.childNodes[0].childNodes[0].value);
 td_value=td_value.replace(/\'/g,"`");
 td_id=td_id.split("::");
 url="index.php?action=ajaxedit&value1="+mysqltable+"&value2="+td_id[0]+"&value3="+td_id[1]+"&value4="+td_value;
 ajaxdata("GET",url,"");
}

function edittxt(file){
 url="index.php?action=edittxt&value1="+file;
 ajaxdata("GET",url,"");
}

function savetxt(file){
 url="index.php";
 content=encodeURIComponent(document.getElementById("textcontent").value);
 file=document.getElementById("filelocation").innerHTML;
 parameters="action=savetxt&value1="+file+"&value2="+content;
 ajaxdata("POST",url,parameters);
}

function newthumb(file){
 url="index.php?action=newthumb&value1="+file;
 ajaxdata("GET",url,"");
}

function updatemp3(file){
 url="index.php?action=createrecords&value1="+file;
 ajaxdata("GET",url,"");
}

function renumbertable(){
 url="index.php?action=renumbertable&value1="+mysqltable;
 ajaxdata("GET",url,"");
}

function deleterow(row){
 document.getElementById(row+"::number").style.backgroundColor="#ff0000";
 url="index.php?action=deleterow&value1="+mysqltable+"&value2="+row;
 ajaxdata("GET",url,"");
}

function moveup(row){
 document.getElementById(row+"::number").style.backgroundColor="#ff0000";
 url="index.php?action=moveup&value1="+mysqltable+"&value2="+row;
 ajaxdata("GET",url,"");
}

function movedown(row){
 document.getElementById(row+"::number").style.backgroundColor="#ff0000";
 url="index.php?action=movedown&value1="+mysqltable+"&value2="+row;
 ajaxdata("GET",url,"");
}

function insert(){
 condition=true;
 i=1;
 url="index.php?action=insert&value1="+mysqltable+"&value2=";
 str="";
 while(condition){
  if(document.getElementById("insert"+i)){
   str=str+document.getElementById("insert"+i).value+"::";
   i++;
  } else {
   condition=false;
  }
 }
 str=encodeURIComponent(str=str.substr(0,str.length-2));
 str=str.replace(/\'/g,"`");
 url=url+str;
 ajaxdata("GET",url,"");
}
function ajaxdata(method,url,parameters){
 xmlhttp=null;
 xmlhttp=new XMLHttpRequest();
 if (xmlhttp!=null){
  xmlhttp.onreadystatechange=state_Change;
  xmlhttp.open(method,url,true);
  if(method=="POST"){
   xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
   xmlhttp.setRequestHeader("Content-length",parameters.length);
   xmlhttp.setRequestHeader("Connection","close");
   xmlhttp.send(parameters);
  } else {
   xmlhttp.send(null);
  }
 } else  {
  alert("Your browser does not support XMLHTTP.");
 }
}
function state_Change(){
 if (xmlhttp.readyState==4){// 4 = "loaded"
  if (xmlhttp.status==200){// 200 = "OK"
   response=xmlhttp.responseText;
//   alert(response)
   response=response.split("::");
   if(response[0]=="1"){
    datasent(response[2]+"::"+response[3],response[5]);
   } else if(response[0]=="2"){
    tt=document.getElementById("dhtmltooltip");
    tt.style.top="0px";
    tt.style.left="0px";
    tt.style.width="auto";
    tt.style.right="0px";
    tt.style.height="auto";
    tt.style.bottom="0px";
    tt.style.border="20px solid black";
    tt.style.visibility="visible";
    tt.innerHTML=response[1]+"<input type='button' value='Cancel' onclick='hidett()'>";
   } else if(response[0]=="3"){
    hidett();
   } else if(response[0]=="4"){
    alert("Done!");
   } else if(response[0]=="5"){
//    alert("Page needs to reload for changes to come into affect.");
    document.location.reload();
   } else if(response[0]=="6"){
    document.getElementById(response[1]+"::number").parentNode.style.display="none";
   } else if(response[0]=="7"){
//    alert("Page needs to reload for changes to come into affect.");
    document.location.reload();
   } else if(response[0]=="8"){
//    alert("Page needs to reload for changes to come into affect.");
    document.location.reload();
   } else if(response[0]=="9"){
//    tr=document.getElementById("edittable").insertRow(-1);
//    td=tr.insertCell(0);
//    td.appendChild(document.createTextNode('New top row'));

    document.location.reload();
//    alert(response[1]);
   } else if(response[0]=="10"){
      alert(response[1]);
      document.location.reload();
   } else {
    alert("Error Occured\n\n"+xmlhttp.responseText);
   }
  } else {
   alert("Problem retrieving data:\n\n" + xmlhttp.statusText);
  }
 }
}


function datasent(id,value){
 document.getElementById(id).innerHTML=value;
}
function hidett(){
 tt=document.getElementById("dhtmltooltip");
 tt.style.visibility="hidden"
 tt.style.left="-2000px"
 tt.style.backgroundColor=''
 tt.style.width=''
}