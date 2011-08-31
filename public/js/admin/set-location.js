$(function () {
	$("#lhierarchy")
		.jstree({
			'ui' : {
				'select_limit' : 1,
				'selected_parent_close' : 'select_parent',
				'initially_select' : $('#idLocation').attr('value')
			},
	
			'themes' : {
				'theme' : 'hpw',
				'dots' : true,
				'icons' : true
			},
			
			/*
			"types" : {
				"default" : {
					"valid_children" : "none",
					"icon" : {
						"image" : "file.png"
					}
				}
			},
			*/
			
			'cookies' : {
				'save_selected' : false
			},
			
			'plugins' : [ 'themes', 'html_data', 'ui', 'cookies' ]
		})
		
		.bind("select_node.jstree", function (event, data) {
			if (data.rslt.obj.hasClass('location')) {
				selectVal = data.rslt.obj.attr('id');
				$('#idLocation').attr('value', selectVal);
			} else {
				$('#idLocation').attr('value', '');
			}
		})
		
		// 2) if not using the UI plugin - the Anchor tags work as expected
		//    so if the anchor has a HREF attirbute - the page will be changed
		//    you can actually prevent the default, etc (normal jquery usage)
		.delegate("a", "click", function (event, data) { event.preventDefault(); })
});
