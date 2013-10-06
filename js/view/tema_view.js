window.TemaView = Backbone.View.extend ({
	el: $("#teme"),
	
	initialize : function () {
		this.render ();
	}	
	
	render: function () {
		$.get('template/tema_view.html', {}, function data () {
			
		})	
	}
});