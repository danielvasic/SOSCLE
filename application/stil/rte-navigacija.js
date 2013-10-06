var rte_navigation = function (){
	this.current_sco ="";
	this.current_item = "";
	this.current_index = 0;
	var items = MANIFEST.get_items ();
	

	this.get_current_sco = function () { return this.current_sco; }	
	this.get_current_item = function () { return this.current_item;	}
	this.get_current_index = function () { return this.current_index; }

	this.set_current_sco = function (value) { this.current_sco = value;}
	this.set_current_item = function (value) { this.current_item = value; }
	this.set_current_index = function (value) { this.current_index = value; }
	
	this.set_default = function () {
		var main_organization = MANIFEST.get_main_organization();
		var org = MANIFEST.get_organization(main_organization[0].default);
		var _item = MANIFEST.get_items(org.identifier);
		var res = MANIFEST.get_resource(_item[0].identifierref);
		this.current_sco = res.identifier;
		this.current_item = _item[0].identifier;
	}
	
	
	this.show_item = function (identifier) {

		for (i in items) {
			if (items[i].identifier == identifier) {
				
				if (typeof(items[i].identifierref) != 'undefined') {
					
					var base = "";
					var params = "";
					if (typeof (items[i].params) != 'undefined') params = items[i].params;
					if (typeof (items[i].base) != 'undefined') base = items[i].base;
					
					
					this.current_item = identifier;
					var res = MANIFEST.get_resource(items[i].identifierref);
					this.current_sco = res.identifier;
					UI.set_active_link(identifier);
					UI.update_progress_bar();
					
					
					$("#iframe_window").attr('src', CI_ROOT + 'scorms/paketi/' + ID_SADRZAJA + "/" + base + res.href + params );
					$("#iframe_window").load(function () {
						$("#big_loader").hide();
						$("#fade").hide();
					});
					return;
				} else {
					this.next();
				}
			}
		}
	}
	
	this.next = function () {
		var items = MANIFEST.get_items();		
		this.current_index = MANIFEST.get_item_index (this.current_item);
		this.current_index = parseInt(this.current_index) + 1;

		if (this.current_index < items.length) {
			var _item = {};
			if (items.length > this.current_index) {
				_item = items[this.current_index];
			}
			if (_item && typeof(_item.identifierref) != "undefined") {
				$("#big_loader").show();
				$("#fade").show();
				this.show_item(_item.identifier);
			}
		}
	}
	
	this.previous = function () {
		var items = MANIFEST.get_items ();
		this.current_index = MANIFEST.get_item_index (this.current_item);

		if (this.current_index > 0) {
			this.current_index = parseInt(this.current_index) - 1;
			if (this.current_index >= 0) {
				_item = items[this.current_index];
			}
			if (_item && typeof(_item.identifierref) != "undefined") {
				$("#big_loader").show();
				$("#fade").show();		
				this.show_item(_item.identifier);
			}	
		}
	}
}
/**
*SCORM RTE NAVIGATION 1.1 API
*/
