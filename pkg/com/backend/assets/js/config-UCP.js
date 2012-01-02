/*
 * Configuration
 */

// Parameter value
var getParam	= function(n, d)
{
	var el	= $('params'+ n);
	if (!el) return d;
	
	return $pick(el.getValue(), d);
};
var setFn	= function(n, o, f)
{
	var el	= $('params'+ n);
	if (!el) return;
	
	el.addEvent(o, f.bind(el));
};

// Preview window position, size
var pre	=
{
	p : function() {
		return $('preview-w').getPosition();
	},
	
	s : function() {
		return $('preview-w').getSize().size;
	}
};

// Bar position setup
var pos	= function(p)
{
	p	= p.split('-', 2);
	
	switch (p[0])
	{
		case 'left':
		case 'right':
			poss(p[0]);
			break;
		
		default:
			posb(p[1]);
	}
};
var poss	= function(a)
{
	// Remove float
	$$('.bar-btn').setStyle('float', 'none');
	
	// Styles
	var p	= pre.p();
	var s	= pre.s();
	$('bar').setStyles({
		top : (p.y + 30),
		width : 'auto',
		height : 'auto',
		left : (a == 'left' ? p.x : p.x + s.x - 26)
	});
};
var posb	= function(a)
{
	// Add float
	$$('.bar-btn').setStyle('float', 'left');
	
	var p	= pre.p();
	var s	= pre.s();
	
	// Set bottom
	var b	= $('bar').setStyles({
		top : (p.y + s.y - 30),
		width : (s.x*0.8).round()
	});
	
	// Margins
	switch (a)
	{
		case 'left':
			b.setStyle('left', p.x);
			break;
		
		case 'right':
			b.setStyle('left', p.x + s.x - b.getSize().size.x);
			break;
		
		default:
			b.setStyle('left', p.x + ((s.x - b.getSize().size.x) / 2).round());
	}
};

window.addEvent('domready', function()
{
	var b	= $('bar');
	if (!b) return;
	
	// Setup bar
	b.setStyles({
		position : 'absolute',
		display : 'block',
		padding : 0,
		color : getParam('textColor', '#ccc'),
		opacity : getParam('opacity', 0.9),
		'border-color' : getParam('brdclr', '#333'),
		'border-style' : getParam('brds', 'none'),
		'border-width' : getParam('brdw', 2),
		'background-color' : getParam('tbclr', '#333')
	});
	pos(getParam('pos', 'bottom-center'));
	
	// Buttons
	$$('.bar-btn').each(function(i)
	{
		i.addEvent('mouseenter', function() {
			this.setStyle('background-color', getParam('light', '#444'));
		});
		i.addEvent('mouseleave', function() {
			this.setStyle('background-color', getParam('tbclr', '#333'));
		});
	});
	
	// Setup parameter inputs
	setFn('tbclr', 'blur', function() {
		$('bar').setStyle('background-color', this.getValue());
	});
	setFn('txtclr', 'blur', function() {
		$('bar').setStyle('color', this.getValue());
	});
	setFn('brdclr', 'blur', function() {
		$('bar').setStyle('border-color', this.getValue());
		$$('.bar-btn').setStyle('border-color', this.getValue());
	});
	setFn('brdw', 'change', function() {
		$('bar').setStyle('border-width', this.getValue().toInt());
		$$('.bar-btn').setStyle('border-width', this.getValue().toInt());
	});
	setFn('brds', 'change', function() {
		$('bar').setStyle('border-style', this.getValue());
	});
	setFn('opacity', 'change', function() {
		$('bar').setOpacity(this.getValue());
	});
	setFn('pos', 'change', function() {
		pos(this.getValue());
	});
});

