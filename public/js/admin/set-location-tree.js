$(function(){
    $("#tree").dynatree({
        minExpandLevel: 2,
        checkbox: true,
        // Override class name for checkbox icon
        classNames: {checkbox: "dynatree-radio"},
        selectMode: 1,
        onClick: function(node, event) {
            if (node.getEventTargetType(event) == "checkbox") {
                console.log(node);

                if (node.childList != null)
                    return false;
                
                $("input#idLocation").val(node.data.idLocation);
            }
        }
    });
    
    $("form").submit(function() {});
});