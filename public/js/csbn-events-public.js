(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

    $(function(){
        $('.csbn_button2').click(function(){
            // You need to get the attribute from the element
            //window.alert( "Handler for .click() called." + $(this).attr('value'));
            event.preventDefault();
            var formData = $(this).attr('value');
            $.ajax({
                url: '/wp-json/csbn-events/v1/checkin',
                type: 'POST',
                data: formData,
                async: true,
				context: this,
                success: function(data) {
					document.querySelector("button[value='" + $(this).attr('value') + "']").disabled = true;
                    //alert(data);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

		$('#addnew').click(function(){
			event.preventDefault();
			//var formData = $(this).attr('value');
			var formData = document.getElementById("fname").value
				+ ':' + document.getElementById("lname").value
				+ ':' + document.getElementById("email").value
				+ ':' + document.getElementById("event_id").value;
			$.ajax({
				url: '/wp-json/csbn-events/v1/addnew',
				type: 'POST',
				data: formData,
				async: true,
				success: function(data) {
					alert(data);
				},
				cache: false,
				contentType: false,
				processData: false
			});
			document.getElementById("fname").value = '';
			document.getElementById("lname").value = '';
			document.getElementById("email").value = '';
		});
    });


})( jQuery );
/*
function myFunction(displayName) {
    window.alert( "Handler for .click() called." + displayName);
}

*/