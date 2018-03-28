function toggleDownloadDialog()
{
	var downloadFrame = document.getElementById('download-frame');
	//console.log(downloadFrame.style.display);
	if(downloadFrame.style.display == 'none' || downloadFrame.style.display == '' || downloadFrame.style.display == 'undefined' )downloadFrame.style.display = 'block';
	else downloadFrame.style.display = 'none';
}
function executeOnSelected(action, callback, reloadNeeded)
{
	if(reloadNeeded === undefined)reloadNeeded = true;
	var checkedValue = null; 
	var inputElements = document.getElementsByClassName('checkBoxFileList');
	for(var i=0; inputElements[i]; ++i){
      if(inputElements[i].checked){
          checkedValue = inputElements[i].id;
		  ajaxLoadSync('operator.php', "action="+action+"&param="+checkedValue, callback);
      }
	}
	if(reloadNeeded)ajaxLoad('operator.php', 'action=fetch-files&param=NULL', buildFileTable);
}

function downloadWrapper(xhttp)
{	
	//console.log(xhttp.responseText);
	var warframe = document.getElementById('downloader');
	warframe.src ="downloader.php?fileLoc="+xhttp.responseText;
	//document.getElementById('downloader').contentWindow.location.reload();
	//document.getElementById('downloader').contentDocument.defaultView.location.reload();
	console.log(warframe.src);
	//setTimeout(function(){},50000);

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
	var Table="<table id=\"fileList\" class=\"fileList\"><tr><th></th><th>Nazwa pliku</th><th>Rozszerzenie pliku</th><th>Udostępniony publicznie?</th><th>Link do pobrania</th><th></th></tr>";
	for(var i=0; i<data.length; i++)
	{
		Table += "<tr>";
		Table += "<td><input type=\"checkbox\" class=\"checkBoxFileList\" name=\""+data[i]["link"]+"\"id=\""+data[i]["link"]+"\" ></td>";
		Table +="<td>"+data[i]["name"]+"</td>";
		Table +="<td>"+data[i]["ext"]+"</td>";
		Table +="<td>"+data[i]["isPublic"]+"</td>";
		Table +="<td>"+data[i]["link"]+"</td>";
		/*Table +="<td>"+data[i]["actionButton1"]+"</td>";*/
		Table += "</tr>";
	}
	Table +="</table>";
	document.getElementById("fileList").innerHTML = Table;
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