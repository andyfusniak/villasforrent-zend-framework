$(function () {
	$("#demo1")
		.jstree({
			'ui' : {
				'select_limit' : 1,
				'selected_parent_close' : 'select_parent',
				'initially_select' : $('#idFastLookup').attr('value')
			},
	
			'themes' : {
				'theme' : 'classic',
				'dots' : true,
				'icons' : true
			},
			
			'cookies' : {
				'save_selected' : false
			},
			
			'plugins' : [ 'themes', 'html_data', 'ui', 'cookies' ]
		})
		
		.bind("select_node.jstree", function (event, data) {
			if (data.rslt.obj.hasClass('destination')) {
				selectVal = data.rslt.obj.attr('id');
				$('#idFastLookup').attr('value', selectVal);	
			} else {
				$('#idFastLookup').attr('value', '');
			}
		})
		
		// 2) if not using the UI plugin - the Anchor tags work as expected
		//    so if the anchor has a HREF attirbute - the page will be changed
		//    you can actually prevent the default, etc (normal jquery usage)
		.delegate("a", "click", function (event, data) { event.preventDefault(); })
});
