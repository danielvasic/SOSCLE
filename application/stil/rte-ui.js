
var rte_ui = function () {
	this.set_active_link = function (identifier) {
		$(".sco").removeClass('active');
		$("#"+identifier).addClass('active');	
	}
	
	this.update_progress_bar = function () {
		var items = MANIFEST.get_items ();
		var progress = ((parseInt(NAVIGATION.get_current_index())+1)/items.length)*100;
		$(".bar").html(parseInt(progress)+" %");
		$(".bar").width(progress+'%');	
	}
	
	this.update_status_bar = function (score, status) {
		var current_item = NAVIGATION.get_current_item ();
		var title = MANIFEST.get_sco_title(current_item);
		$("#sco_ime").html(title);
		var slika_url = CI_ROOT + '/stil/moj_stil/';
		switch (status) {
			case 'completed':
			case 'passed':
				slika_url += 'accept.png';
				break;
			case 'browsed':
			case 'not attempted':
				slika_url += 'delete.png';
				break;
			case 'failed':
			case 'incomplete':
				slika_url += 'road_sign.png';
				break;	
		}	
		$("#status").html($("<img />").attr('src', slika_url).attr('title', status));
		$("#score").html(score);
	}

	this.build_menu = function () {
		var organizations = MANIFEST.get_organizations();
		
		$("#navigation").html("");
		
		for (i in organizations) {
			var org = organizations[i];
			var naslov = $("<li></li>").addClass('nav-header').html(org.title);
			var org_items = MANIFEST.get_items(org.identifier);
			$("#navigation").append(naslov);
			for (i in org_items) {
				var _item = org_items[i];
				if (_item.identifierref) {
					var _res = MANIFEST.get_resource(_item.identifierref);
					var _link = $("<a></a>")
								.attr('href', 'javascript:void(0)')
								.attr('class', 'sco')
								.attr('name', _res.identifier)
								.attr('id', _item.identifier)
								.html(_item.title);
					$("#navigation").append($("<li></li>").html(_link));
				} else {
					var parent_link = $("<a></a>").attr('href', 'javascript:void(0)').addClass('sub-open').append(_item.title).prepend('&nbsp;').prepend($("<span></span>").addClass('icon').addClass('icon-plus-sign'));;
					var list = $("<li></li>").html(parent_link).append(this.build_sublist(_item));
					$("#navigation").append(list);
				}
			}
		}	
	}
	
	this.build_sublist = function (_item) {
		var sub_navigation = $("<ul></ul>").addClass('nav').addClass('sub');
		var items = MANIFEST.get_items(_item.identifier);
		for (i in items) {
			var sub_item = items[i];
			if (sub_item.identifierref) {
				var _res = MANIFEST.get_resource(sub_item.identifierref);
				var _link = $("<a></a>")
							.attr('href', 'javascript:void(0)')
							.attr('class', 'sco')
							.attr('name', _res.identifier)
							.attr('id', sub_item.identifier)
							.html(sub_item.title);
				sub_navigation.append($("<li></li>").html(_link));
			} else {
				var parent_link = $("<a></a>").attr('href', 'javascript:void(0)').addClass('sub-open').html(sub_item.title).prepend('&nbsp;').prepend($("<span></span>").addClass('icon').addClass('icon-plus-sign'));
				var list = $("<li></li>").html(parent_link).append(this.build_sublist(sub_item));
				sub_navigation.append(list);
			}
		}
		return sub_navigation;
	}	
}
/**
*SCORM RTE UI 1.0 API
*/