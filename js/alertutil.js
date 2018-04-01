var close = document.getElementsByClassName("closebtn");
var i;

for (i = 0; i < close.length; i++) {
    close[i].onclick = function(){
        var div = this.parentElement;
        //div.style.opacity = "0";
		//setTimeout(function(){ div.style.transition = "opacity 1s ease-in-out";}, 1000);
		//div.style.opacity = "1";
        //setTimeout(function(){ div.style.display = "none"; }, 200);
		setTimeout(function(){ div.classList.add("alertHideForGood")}, 100); 
		div.classList.add("alertHideAnimation");
		
    }
}