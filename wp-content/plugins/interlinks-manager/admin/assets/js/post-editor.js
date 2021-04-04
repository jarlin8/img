jQuery(document).ready(function($) {

    /*
     * ajax request that generates a list of interlinks suggestion in the
     * "Interlinks Suggestions" meta box
     */
    $('#generate-ideas').click(function(){
       
        //if another request is processed right now do not proceed with another ajax request
        if($('#ajax-request-status').val() == 'processing'){return;}
       
        //get the post id for which the suggestions should be generated
        var post_id = parseInt($(this).attr('data-post-id'), 10);
        
        //prepare ajax request
        var data = {
            "action": "generate_interlinks_suggestions",
            "security": daim_nonce,
            "post_id": post_id
        };

        //show the spinner
        $('#daim-meta-suggestions .spinner').css('visibility', 'visible');
        
        //set the ajax request status
        $('#ajax-request-status').val('processing');

        //send ajax request
        $.post(daim_ajax_url, data, function(list_content) {
        
            //show the new suggestions based on the xml response
            $('#suggestions-list').empty().append(list_content).show();
            
            //hide the spinner
            $('#daim-meta-suggestions .spinner').css('visibility', 'hidden');
            
            //set the ajax request status
            $('#ajax-request-status').val('inactive'); 
        
        });
        
    });

});