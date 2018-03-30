function deleteWrapper()
{
	if(window.confirm("Are you sure?"))executeOnSelected('delete', nullFunc(), true);
}
function nullFunc()
{
}
function fileListUpdate()
{
	var from = document.getElementById('from').value;
	var to = document.getElementById('lim').value;
	if( from !='' && to !='' && parseInt(from,10) <= parseInt(to,10)){
		ajaxLoad('operator.php', "action=fetch-files&param="+parseInt(from,10)+"|"+parseInt(to,10), buildFileTable);
	}
}
function zipWrapper(zipId)
{
	executeOnSelected('pack-files-zip', nullFunc, false, "|"+zipId);
	
	var warframe = document.getElementById('downloader');	//dirty
	warframe.src ="downloader.php?fileLoc="+"download/"+zipId+"/"+zipId+".zip";
}
function toggleDownloadDialog()
{
	var downloadFrame = document.getElementById('download-frame');
	if(downloadFrame.style.display == 'none' || downloadFrame.style.display == '' || downloadFrame.style.display == 'undefined' )downloadFrame.style.display = 'block';
	else downloadFrame.style.display = 'none';
}
function executeOnSelected(action, callback, reloadNeeded, additional)
{
	if(reloadNeeded === undefined)reloadNeeded = true;
	if(additional === undefined)additional = '';
	var checkedValue = null; 
	var inputElements = document.getElementsByClassName('checkBoxFileList');
	for(var i=0; inputElements[i]; ++i){
      if(inputElements[i].checked){
          checkedValue = inputElements[i].id;
		  ajaxLoadSync('operator.php', "action="+action+"&param="+checkedValue+additional, callback);	//temporary needs to be fixed
      }
	}
	if(reloadNeeded)fileListUpdate();
}

function downloadWrapper(xhttp)
{	
	//console.log(xhttp.responseText);
	var warframe = document.getElementById('downloader');
	warframe.src ="downloader.php?fileLoc="+xhttp.responseText;
	//console.log(warframe.src);
	
	
	/*var a = document.createElement('a');
	a.style = "display: none;";
	a.href = "downloader.php?fileLoc="+xhttp.responseText;
	document.body.appendChild(a);
	a.click();*/

}
function incLeft()
{
	var result1 = document.getElementById('from').value;
	var result2 = document.getElementById('lim').value;
	
	var moveSize = parseInt(document.getElementById('moveSize').value,10);
	
	var final1 = parseInt(result1, 10)-moveSize;
	var final2 = parseInt(result2, 10)-moveSize;
	if(final1<1)final1= 1;
	if(final2<1)final2=1;
	
	document.getElementById('from').value = final1;
	document.getElementById('lim').value = final2;
	
	fileListUpdate();
	
}
function incRight()
{
	var result1 = document.getElementById('from').value;
	var result2 = document.getElementById('lim').value;
	
	var moveSize = parseInt(document.getElementById('moveSize').value,10);
	
	var final1 = parseInt(result1, 10)+moveSize;
	var final2 = parseInt(result2, 10)+moveSize;
	if(final1<1)final1= 1;
	if(final2<1)final2=1;
	
	document.getElementById('from').value = final1;
	document.getElementById('lim').value = final2;
	//console.log(result1);
	
	fileListUpdate();
}
/*function downloadFire(xhttp)
{
	console.log(xhttp);
	
	var saveByteArray = (function () {
    var a = document.createElement("a");
    document.body.appendChild(a);
    a.style = "display: none";
    return function (data, name) {
        var blob = new Blob(data, {type: "octet/stream"}),
            url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = name;
        //a.click();
        window.URL.revokeObjectURL(url);
    };
}());
	saveByteArray([xhttp.reponseText], "download.txt");
	
}*/

function buildFileTable(data)
{	//data.res
	//console.log(data);
	data  = data.responseText;
	//console.log(JSON.stringify(data));
	data = JSON.parse(data);
	var Table="<table id=\"fileList\" class=\"fileList\"><tr><th><input type=\"checkbox\" onclick=\"lobsterSelectAll(this)\" /></th><th>Nazwa pliku</th><th>Rozszerzenie pliku</th><th>Udostępniony publicznie?</th><th>Link do pobrania</th><th></th></tr>";
	for(var i=0; i<data.length; i++)
	{
		Table += "<tr>";
		Table += "<td><input type=\"checkbox\" class=\"checkBoxFileList\" name=\""+data[i]["link"]+"\"id=\""+data[i]["link"]+"\" id=\""+data[i]["link"]+"\"id=\""+data[i]["link"]+"\" ></td>";
		Table +="<td onclick=\"document.getElementById('"+data[i]["link"]+"').click()\">"+data[i]["name"]+"</td>";
		Table +="<td onclick=\"document.getElementById('"+data[i]["link"]+"').click()\">"+data[i]["ext"]+"</td>";
		Table +="<td onclick=\"document.getElementById('"+data[i]["link"]+"').click()\">"+data[i]["isPublic"]+"</td>";
		Table +="<td onclick=\"document.getElementById('"+data[i]["link"]+"').click()\">"+data[i]["link"]+"</td>";
		/*Table +="<td>"+data[i]["actionButton1"]+"</td>";*/
		Table += "</tr>";
	}
	Table +="</table>";
	document.getElementById("fileList").innerHTML = Table;
}
function lobsterSelectAll(c)
{
	var all = document.getElementsByClassName('checkBoxFileList');
	for(var i = 0; i< all.length; i++)
	{
		all[i].checked = c.checked;	
	}
}
function ajaxResponseDump(xhttp)
{
	alert(xhttp.responseText);
}
function ajaxLoad(file, data ,callback)
{
	if(window.XMLHttpRequest){
			xhttp = new XMLHttpRequest();
	}else{
			xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			callback(this);
		}
	}
	
	xhttp.open("POST", file, true);
	xhttp.setRequestHeader("Content-type", 'application/x-www-form-urlencoded');
	xhttp.send(data);

}
function ajaxLoadSync(file, data ,callback)
{
	if(window.XMLHttpRequest){
			xhttp = new XMLHttpRequest();
	}else{
			xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			callback(this);
		}
	}
	
	xhttp.open("POST", file, false);
	xhttp.setRequestHeader("Content-type", 'application/x-www-form-urlencoded');
	xhttp.send(data);

}