
function initializePage(){
	var iHeight = parseInt(window.innerHeight);
	
	// if(iHeight >= 990){
		// var a = "assets/site_style_H990.css"
		// loadJsCssFile(a, "css");
	// }
	fixContentSize(iHeight);
}

// function loadJsCssFile(filename, filetype){
	// var oHead = document.getElementsByTagName("head")[0];
		
	// if (filetype=="js"){ //if filename is a external JavaScript file
		// var fileref=document.createElement('script');
		// fileref.setAttribute("type","text/javascript");
		// fileref.setAttribute("src", filename);
	// }
	// else if (filetype=="css"){ //if filename is an external CSS file
		// var bExists = false;
		// for(var i = 0; i < oHead.childNodes.length; i++){
			// if(oHead.childNodes[i].tagName === "LINK"){
				// if(oHead.childNodes[i].getAttribute("href") === filename){
					// bExists = true;
				// }
			// }
		// }
		// if(!bExists){
			// var fileref=document.createElement("link");
			// fileref.setAttribute("rel", "stylesheet");
			// fileref.setAttribute("type", "text/css");
			// fileref.setAttribute("href", filename);
			// document.getElementsByTagName("head")[0].appendChild(fileref);
		// }
	// }
// }

function fixContentSize(iWindowHeight){
	var eContent = document.querySelector(".content");
	var iContentHeight = parseInt(window.getComputedStyle(eContent).height);
	if(iContentHeight < iWindowHeight-270){ //Header(70)+Footer(200) = 270px;
		eContent.style.height = iWindowHeight-270 +'px';
	}
}

