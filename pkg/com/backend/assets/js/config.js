/*
 * Configuration
 */

// Parameter value
var getParam	= function(n, d)
{
	alert('getParam: '+ n +' ('+ d +')')
};
var getValue    = function(a, b, c)
{
	var d	= $('params'+ a);
	if (!d) return b;
        if (d.value == undefined) return b;
        if (d.value.trim().length < (c || 1)) return b;
	
	return d.value;
};
var getSelect   = function(a, b)
{
	var c	= $('params'+ a);
	if (!c) return b;
        
	return (c.options[c.selectedIndex].value || b);
}
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
		return $('preview-w').getSize();
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
                    b.setStyle('left', p.x + s.x - b.getSize().x);
                    break;
            
            default:
                    b.setStyle('left', p.x + ((s.x - b.getSize().x) / 2).round());
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
        color : getValue('textColor', '#ccc', 4),
        opacity : getValue('opacity', 0.9),
        'border-color' : getValue('brdclr', '#333', 4),
        'border-style' : getSelect('brds', 'none'),
        'border-width' : getSelect('brdw', 2),
        'background-color' : getValue('tbclr', '#333')
    });
    pos(getSelect('pos', 'bottom-center'));
    
    // Buttons
    $$('.bar-btn').each(function(i)
    {
        i.addEvent('mouseenter', function() {
            this.setStyle('background-color', getValue('light', '#444'));
        });
        i.addEvent('mouseleave', function() {
            this.setStyle('background-color', getValue('tbclr', '#333'));
        });
    });
    
    // Setup parameter inputs
    setFn('tbclr', 'blur', function() {
        $('bar').setStyle('background-color', this.get('value'));
    });
    setFn('txtclr', 'blur', function() {
        $('bar').setStyle('color', this.get('value'));
    });
    setFn('brdclr', 'blur', function() {
        $('bar').setStyle('border-color', this.get('value'));
        $$('.bar-btn').setStyle('border-color', this.get('value'));
    });
    setFn('brdw', 'change', function() {
        var w   = getSelect('brdw', 2).toInt();
        $('bar').setStyle('border-width', w);
        $$('.bar-btn').setStyle('border-width', w);
    });
    setFn('brds', 'change', function() {
        $('bar').setStyle('border-style', getSelect('brds', 'none'));
    });
    setFn('opacity', 'change', function() {
        $('bar').setOpacity(this.get('value'));
    });
    setFn('pos', 'change', function() {
        pos(getSelect('pos', 'bottom-center'));
    });
});

