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