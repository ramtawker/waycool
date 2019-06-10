// Wait for the DOM to be ready
$(document).ready(function () {
	// debugger;
	// Initialize form validation on the registration form.
	// It has the name attribute "registration"
	$("form[name='registration']").validate({
		// Specify validation rules
		rules: {
			// The key name on the left side is the name attribute
			// of an input field. Validation rules are defined
			// on the right side
			user_name: "required",
			user_age: "required",
			user_mobile: {
				required: true,
				maxlength: 10
			},
			user_email: {
				required: true,
				// Specify that email should be validated
				// by the built-in "email" rule
				email: true
			},
		},
		// Specify validation error messages
		messages: {
			user_name: "Please enter your firstname",
			user_age: "Please enter your age",
			// user_password: {
			//   required: "Please provide a password",
			//   minlength: "Your password must be at least 5 characters long"
			// },
			user_email: "Please enter a valid email address",
			user_mobile: "Please enter your mobile number"
		},
		// Make sure the form is submitted to the destination defined
		// in the "action" attribute of the form when valid
		submitHandler: function (form) {
			form.submit();
		}
	});

  //Google Location
	geocoder = new google.maps.Geocoder;
	// jQuery("#loc_error").css('display', 'none');
	if (navigator.geolocation) {
		debugger;
		navigator.geolocation.getCurrentPosition(function (position) {
			var pos = {
				lat: position.coords.latitude,
				lng: position.coords.longitude
			};
			geocoder.geocode({
				'location': pos
			}, function (results, status) {
				if (status === 'OK') {
					if (results[0]) {
						document.getElementById('loc-inp').value = results[0].formatted_address;
					} else {
						console.log('No results found');
					}
				} else {
					console.log('Geocoder failed due to: ' + status);
				}
			});
		}, function (error) {
			console.log(error.message);
		});
	} else {
		console.log('No results found - mobile');
	}

});
