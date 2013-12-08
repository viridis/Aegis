function initializePage() {
    var iHeight = parseInt(window.innerHeight);

    // if(iHeight >= 990){
    // var a = "assets/site_style_H990.css"
    // loadJsCssFile(a, "css");
    // }
    fixContentSize(iHeight);
}

function popup(id, state) {
    document.getElementById(id).style.display = state;
    document.getElementById("popupWrapper").style.display = state;
}

function setFocus(id) {
    document.getElementById(id).focus();
}

function fixContentSize(iWindowHeight) {
    var eContent = document.querySelector(".content");
    var iContentHeight = parseInt(window.getComputedStyle(eContent).height);
    if (iContentHeight < iWindowHeight - 270) { //Header(70)+Footer(200) = 270px;
        eContent.style.height = iWindowHeight - 270 + 'px';
    }
}

function commaSeparateNumber(val) {
    val = val.replace(/,/g, '');
    return val.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
}

function dropDownSettings(){
    dropdown = document.getElementById('settingsMenu');
    console.log(dropdown.style.display);
    if(dropdown.style.display != 'inline'){
        dropdown.style.display = 'inline';
    } else {
        dropdown.style.display = 'none';
    }
}

