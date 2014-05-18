function setFocus(id) {
    document.getElementById(id).focus();
}

function commaSeparateNumber(val) {
    val = val.replace(/,/g, '');
    return val.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
}

