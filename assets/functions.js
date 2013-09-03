function Counter (max) {
	this.max = max;
	this.current = 0;
 
	this.canIncrease = function () { return this.current < this.max; }
	this.increase = function () { this.current= Math.min(this.current+1, this.max); }
	this.canDecrease = function () { return this.current > 0; }
	this.decrease = function () { this.current= Math.max(this.current-1, 0) }
	this.getLeftOver = function () { return this.max-this.current; }
}
 
function MaintainCounters () {
	self = this;
	this.counters = {};
	this.current = "";
 
	this.nextCounter = function (identifier) { self.current = identifier; }
	this.getCounter = function (identifier) { return self.counters[identifier]; }
	this.getCurrentCounter = function () { return self.counters[self.current]; }
	this.getCurrentIdentifier = function () { return self.current; }
 
	this.addCounter = function (identifier, counter) {
		self.counters[identifier] = counter;
		self.current = identifier;
	}
}
 
function MaintainClickEvents (gridSize) {
	var self = this;
	this.counters = new MaintainCounters ();
 
	this.counters.addCounter ('Clear', new Counter (100-9))
	this.counters.addCounter ('LordKnight', new Counter (6));
	this.counters.addCounter ('HighWizard', new Counter (3));
 
	this.selectorOnClick = function (clickEvent) {
		var el = clickEvent.target;
		var classes = el.className.split (' ');
		classes[1] = 'current';
 
		var buttons = document.querySelectorAll ('.button');
		for (var i = 0; i < buttons.length; i++) {
			var _el = buttons[i];
			var _classes = _el.className.split (' ');
			_classes[1] = 'active';
			_el.className = _classes.join (' ');
		}
 
		self.counters.nextCounter (classes[0]);
		el.className = classes.join(' ');
		return null;
	}
 
	this.cellOnClick = function (clickEvent) {
		var counter = self.counters.getCurrentCounter ();
		var identifier = self.counters.getCurrentIdentifier ();
		var el = clickEvent.target;
 
		if ( el.className == identifier ) { return null; }
		if ( ! (counter.canIncrease()) ) {return null; }
 
		counter.increase();
		var otherCounter = self.counters.getCounter (el.className);
		otherCounter.decrease();
		el.className = identifier;
		
		var buttonElement = document.getElementById(identifier);
		while(buttonElement.hasChildNodes()){
			buttonElement.removeChild(buttonElement.lastChild);
		}
		buttonElement.appendChild(document.createTextNode(self.counters.getCurrentIdentifier() + ": " + counter.getLeftOver()));

	}
}