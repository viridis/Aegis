function initializeGrid() {

    var clicker = new MaintainClickEvents();

    var eContent = document.getElementById('eGrid');

    appendButtons(eContent, clicker);

    var eGrid = document.createElement('table');
    eGrid.setAttribute('id', 'gridTable');
    eGrid.setAttribute('class', 'grid');

    for (var i = 1; i <= 10; i++) {
        var eRow = document.createElement('tr');
        for (var j = 1; j <= 10; j++) {
            var eData = document.createElement('td');
            eData.className = "Clear";
            eData.onclick = clicker.cellOnClick;
            var sCellname = i + ", " + j;
            eData.appendChild(document.createTextNode(sCellname));
            eRow.appendChild(eData);
        }
        eGrid.appendChild(eRow);
    }

    eContent.appendChild(eGrid);
    fixAspectRatio(eGrid);

}

function fixAspectRatio(eGrid) {
    var iWidth = parseInt(window.getComputedStyle(eGrid.parentNode).width);
    if (iWidth >= window.innerHeight - 150) {
        iWidth = window.innerHeight - 150;
    }
    ;
    eGrid.style.height = iWidth + 'px';
    eGrid.style.width = iWidth + 'px';
}

function appendButtons(el, clicker) {
    var clearButton = document.createElement('div');
    clearButton.id = "Clear";
    clearButton.onclick = clicker.selectorOnClick;
    clearButton.className = 'Clear active button';
    clearButton.appendChild(document.createTextNode('clear'));

    var lordKnightButton = document.createElement('div');
    lordKnightButton.id = "LordKnight";
    lordKnightButton.onclick = clicker.selectorOnClick;
    lordKnightButton.className = 'LordKnight active button';
    lordKnightButton.appendChild(document.createTextNode('LordKnight'));

    var highWizardButton = document.createElement('div');
    highWizardButton.id = "HighWizard";
    highWizardButton.onclick = clicker.selectorOnClick;
    highWizardButton.className = 'HighWizard current button';
    highWizardButton.appendChild(document.createTextNode('HighWizard'));

    eGrid.appendChild(clearButton);
    eGrid.appendChild(lordKnightButton);
    eGrid.appendChild(highWizardButton);
}