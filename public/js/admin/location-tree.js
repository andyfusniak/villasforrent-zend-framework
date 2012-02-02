$(function() {
    $("#tree").dynatree({
        minExpandLevel: 2,
        onClick: function(node, event) {
            console.log(node);
            //if (node.childList == null)
            //    console.log(node);
            //    console.log(node.data.idLocation);
                //$("input#idLocation").val(node.data.idLocation);
        }
    });
 
    $( "#dialog-confirm" ).dialog({
        autoOpen: false,
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            "Delete": function() {
                $( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        }
    });
    
    
    $( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 300,
			width: 350,
            resizable: false,
			modal: true,
            buttons: {
				"Create an account": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( name, "username", 3, 16 );
					bValid = bValid && checkLength( email, "email", 6, 80 );
					bValid = bValid && checkLength( password, "password", 5, 16 );

					bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );

					if ( bValid ) {
						$( "#users tbody" ).append( "<tr>" +
							"<td>" + name.val() + "</td>" + 
							"<td>" + email.val() + "</td>" + 
							"<td>" + password.val() + "</td>" +
						"</tr>" ); 
						$( this ).dialog( "close" );
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
            }   
    });
    
    $("#create-user").button().click(function() {
        $("#dialog-form").dialog("open");
	});
    
    $.contextMenu({
        selector: '.context-menu',
        autoHide: true,
        callback: function(key, options) { 
        },
        items: {
            "add": {
                name: "Add a new location",
                icon: "edit"
            },
            "copy": {name: "Copy", icon: "copy"},
            "paste": {name: "Paste", icon: "paste"},
            "sep1": "---------",
            "quit": {name: "Quit", icon: "quit"}
        }
    });
    
    $.contextMenu({
        selector: '.context-menu-leaf',
        autoHide: true,
        callback: function(key, options) {
            //console.log(this);
        },
        events: {
            show: function(opt) {
                this.addClass('currently-showing-menu');
                //console.log(opt);
                t = this
                //console.log(t);
            }
        },
        items: {
            "delete": {
                name: "Delete",
                icon: "delete",
                callback: function(key, options) {
                    $("#dialog-confirm").dialog("open"); 
                }
            }
        }
    });
});