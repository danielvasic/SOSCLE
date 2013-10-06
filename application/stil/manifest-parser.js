var minifest_parser = function (_xml) {

	var xml = _xml;
	
	/*Parsed MANIFEST items, this list can be upgraded with subMANIFEST parsing and metadata parsing*/
	var main_organization = [];
	var organizations = [];
	var items = [];
	var resources = [];
	var files = [];
	/*Parsed MANIFEST items, this list can be upgraded with subMANIFEST parsing and metadata parsing*/
	
	this.get_resouces = function () { return resources; }
	this.get_organizations = function () { return organizations; }
	this.get_main_organization = function () { return main_organization; }
	this.get_files = function () { return files; }
	
	this.parse = function () {
		$(xml).find('organizations').each(function(index, element) {
			var organization = {'default'	:	$(this).attr('default')};
			main_organization.push(organization);   
		});
					
		$(xml).find("organization").each(function(index, element) {
			var organization = {
				'identifier'	:	$(this).attr('identifier'), 
				'title' 		: 	$(this).children('title').text(),
				'structure' 	: 	$(this).attr('structure'),
				'base'			:	$(this).attr('base'),
			}
			organizations.push(organization);
		});
					
		$(xml).find('item').each(function(index, element) {
			var _item = {
				'parent'		:	$(this).parent().attr('identifier'), 
				'identifier'	:	$(this).attr('identifier'), 
				'params'		:	$(this).attr('parameters'), 
				'base'			:	$(this).attr('base'),
				'identifierref' : 	$(this).attr('identifierref'),
				'title' 		: 	$(this).children('title').text(),
				'isvisible' 	: 	$(this).attr('isvisible'),
				'masteryscore'	: 	$(this).children('adlcp\\:masteryscore').text()
			}
			items.push(_item);
		});
					
		$(xml).find('resource').each(function(index, element) {
			var resource = {
				'identifier'	:	$(this).attr('identifier'), 
				'scormType'		:	$(this).attr('scormType'),
				'href'			: 	$(this).attr('href'),
				'type'			: 	$(this).attr('type')
			}
			resources.push(resource);
		});
					
		$(xml).find('file').each(function(index, element) {
			var resource = {
				'parent'		:	$(this).parent().attr('identifier'), 
				'href'			: 	$(this).attr('href')
			}
			resources.push(resource);
		});
	}
	
	this.get_item_index = function (identifier) {
		for (i in items) {
			if (items[i].identifier == identifier) {
				return i;
			}	
		}
		return -1;
	}
	
	this.get_sco_title = function (identifier) {
		for (i in items) {
			if (items[i].identifier == identifier) {
				return items[i].title;
			}	
		}
		return -1;
	}
	
	
	this.get_resource = function (identifierref) {
		for (i in resources) {
			if (resources[i].identifier == identifierref) { 
				return resources[i];
			}
		}
	}
	
	this.get_organization = function (identifier) {
		for (i in organizations) {
			if (organizations[i].identifier == identifier) {
				return organizations[i];	
			}	
		}	
	}
	
	this.get_item = function (identifier) {
		for (i in items) {
			if (items[i].identifier == identifier) return items[i];	
		}
	}
	
	this.get_items = function (identifier) {
		if (typeof identifier != "undefined") {
			var _items = [];
			for (i in items) {
				if (items[i].parent == identifier) {
					_items.push(items[i]);	
				}
			}
			
			if (_items.length == 0) {
				for (i in items) {
					if (items[i].parent == identifier) {
						_items.push(items[i]);	
					}	
				}	
			}
			return _items;
		} else {
			 return items;	
		}
	}
}
/**
*SCORM MANIFEST PARSER 1.0 API
*/