/*
 * Menu editing
 */

var uBarMenu	=
{
	// Text strings
	txtNew : 'New',
	txtEdit : 'Edit',
	txtMissingText : 'Missing "Text" parameter',
	txtMissingUrl : 'Missing "URL" parameter',
	txtDelete : 'Delete items?',
	
	// Item elements
	item : {},
	
	// Sets up menu editing
	init : function(id)
	{
		// Item elements
		this.item.text		= $('item-text');
		this.item.url		= $('item-url');
		this.item.target	= $('item-target');
		this.item.width		= $('item-w');
		this.item.height	= $('item-h');
		this.item.ref		= $('item-ref');
		this.item.save		= $('item-save');
		this.item.cancel	= $('item-cancel');
		
		// Insert status span
		new Element('span').setHTML(': ').inject($('params-title'));
		this.item.status	= new Element('span', {
			id : 'item-status'
		}).setHTML(this.txtNew).inject($('params-title'));
		
		// Setup buttons
		this.item.save.addEvent('click', this.save.bind(this));
		this.item.cancel.addEvent('click', this.cancel.bind(this));
		
		// MooTools Sortables
		// http://docs111.mootools.net/Plugins/Sortables.js
		this.s		= new Sortables(id);
		
		// Get list ID
		this.id		= this.s.list.getProperty('id');
	},
	
	// Inserts edit, delete links
	insertEditLinks : function(li, i, title)
	{
		// Get link title
		title	= title || li.innerHTML;
		title	= title.length > 0 ? title : '??';
		li.empty();
		
		// Title DIV
		new Element('div', {
			'class' : 'title'
		}).setHTML(title).inject(li);
		
		// Tools DIV
		var tools	= new Element('div', {
			'class' : 'tools'
		}).inject(li);
		
		// Clear
		new Element('div', {'class' : 'clear'}).inject(li);
		
		// Edit
		var edit	= new Element('a', {
			href : 'javascript:void(0)',
			events : {
				click : this.editItem.bind(this, [i])
			}
		}).inject(tools);
		new Element('img', {
			alt : 'edit',
			src : 'components/com_ubar/assets/img/page_white_edit.png'
		}).inject(edit);
		
		// Delete
		var del	= new Element('a', {
			href : 'javascript:void(0)',
			events : {
				click : this.removeItem.bind(this, [i])
			}
		}).inject(tools);
		new Element('img', {
			alt : 'delete',
			src : 'components/com_ubar/assets/img/page_white_delete.png'
		}).inject(del);
		
		// Visit link
		var link	= new Element('a', {
			href : 'javascript:void(0)',
			events : {
				click : this.previewItem.bind(this, [i])
			}
		}).inject(tools);
		new Element('img', {
			alt : 'delete',
			src : 'components/com_ubar/assets/img/world.png'
		}).inject(link);
	},
	
	// Saves existing item, or adds new item
	save : function()
	{
		// Add new item
		if (this.item.ref.value < 0)
			return this.addItem();
		
		// Save existing item
		this.saveItem();
	},
	
	// Adds an item to the list
	addItem : function(txt, u, tgt, w, h, noFocus)
	{
		// Item details
		txt	= txt || this.item.text.getValue().trim();
		u	= u || this.item.url.getValue().trim();
		tgt	= tgt || this.item.target.getValue();
		w	= w || this.item.width.getValue().replace(/[^0-9]/g, '');
		h	= h || this.item.height.getValue().replace(/[^0-9]/g, '');
		
		// Check item
		if (txt.length < 1) {
			alert(this.txtMissingText);
			return noFocus ? false : this.item.text.focus();
		}
		if (u.length < 1) {
			alert(this.txtMissingUrl);
			return noFocus ? false : this.item.url.focus();
		}
		
		// List element
		var li	= new Element('li').inject(this.s.list);
		
		// Save title, URL, target
		li.setProperty('title', txt);
		li.setProperty('rel', u +'!##'+ tgt +'!##'+ w +'!##'+ h);
		
		// Update references
		var i	= this.s.handles.push(li) - 1;
		this.s.bound.start[i]	= this.s.start.bindWithEvent(this.s, li);
		
		// Dragging
		li.addEvent('mousedown', this.s.bound.start[i]);
		
		// Insert edit links
		this.insertEditLinks(li, i, txt);
		
		// Clear fields
		noFocus ? null : this.clear();
	},
	
	// Loads parameter fields with selected item
	editItem : function(i)
	{
		// Unselect current element
		this.cancel();
		
		// Get element
		var li	= this.s.elements[i] || false;
		if (!li) return alert('Error: can\'t find item reference [ei]');
		
		// Load parameter fields
		var rel		= li.getProperty('rel').split('!##');
		this.item.ref.value		= i;
		this.item.url.value		= rel[0];
		this.item.target.value	= rel[1];
		this.item.width.value	= rel[2];
		this.item.height.value	= rel[3];
		this.item.text.value	= li.getProperty('title');
		
		// Update status
		this.item.status.setHTML(this.txtEdit);
		li.setStyle('background-color', '#d2d7e0');
	},
	
	// Opens item URL in new window
	previewItem : function(i)
	{
		// Get element
		var li	= this.s.elements[i] || false;
		if (!li) return alert(i);
		
		// Get URL
		var rel	= li.getProperty('rel').split('!##');
		var url	= rel[0].toLowerCase();
		if (url.indexOf('http') !== 0) {
			url	= url.indexOf('www') === 0 ? 'http://'+ url : this.siteRoot + url;
		}
		
		// Open URL
		window.open(url);
	},
	
	// Saves existing item
	saveItem : function()
	{
		// Find element reference
		var li	= this.s.elements[this.item.ref.value] || false;
		if (!li) return alert('Error: Can\'t find item reference [si]');
		
		// Item details
		var txt	= this.item.text.getValue().trim();
		var u	= this.item.url.getValue().trim();
		var tgt	= this.item.target.getValue();
		var w	= this.item.width.getValue().replace(/[^0-9]/g, '');
		var h	= this.item.height.getValue().replace(/[^0-9]/g, '');
		
		// Check item
		if (txt.length < 1) {
			alert(this.txtMissingText);
			return this.item.text.focus();
		}
		if (u.length < 1) {
			alert(this.txtMissingUrl);
			return this.item.url.focus();
		}
		
		// Save title, URL, target
		li.setProperty('title', txt);
		li.setProperty('rel', u +'!##'+ tgt +'!##'+ w +'!##'+ h);
		$E('.title', li).setHTML(txt);
		
		// Clear fields
		this.cancel();
	},
	
	// Removes the selected item
	removeItem : function(i)
	{
		if (confirm(this.txtDelete))
		{
			// Get element
			var li	= this.s.elements[i] || false;
			if (!li) return alert('Error: can\'t find item reference [ri]');
			
			// Remove element
			li.remove();
			this.s.bound.start[i]	= null;
			
			if (this.item.ref.value == i) this.clear();
		}
	},
	
	// Cancels editing of item
	cancel : function()
	{
		if (this.item.ref.value != -1)
		{
			// Reset background colour
			var li	= this.s.elements[this.item.ref.value] || false;
			if (li) li.setStyle('background-color', '#f6f6f6');
			
			// Clear fields
			this.clear();
		}
	},
	
	// Clears parameter fields
	clear : function()
	{
		// Clear parameter fields
		this.item.ref.value		= -1;
		this.item.url.value		= '';
		this.item.target.value	= '_self';
		this.item.width.value	= '';
		this.item.height.value	= '';
		this.item.text.value	= '';
		
		// Update status
		this.item.status.setHTML(this.txtNew);
	},
	
	// Returns menu items
	getMenuItems : function()
	{
		var items	= [];
		var list	= this.s.list.getChildren();
		
		list.each(function(li, i)
		{
			var rel	= li.getProperty('rel').split('!##');
			items.include({
				index : i,
				text : encodeURIComponent(li.getProperty('title').trim()),
				url : encodeURIComponent(rel[0].trim()),
				target : rel[1],
				width : rel[2],
				height : rel[3]
			});
		});
		
		return items;
	}
};

