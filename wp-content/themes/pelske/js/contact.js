function contactSubmitCallback( token ){

	document.getElementById('contact-form').submit();

}

function gbEntrySubmitCallback( token ){

	document.getElementById('guestbook-form').submit();

}

(function( $ ) {
	'use strict';

	// Hide honey pots
	$('.js-validate-hp').hide();

	var invalidClassName = 'invalid';
	var forms = document.querySelectorAll('.js-validate');

	// JS Validation based on: https://css-tricks.com/form-validation-part-1-constraint-validation-html/
	// Disable native HTML validation for js-validate forms
	for (var i = 0; i < forms.length; i++) {
	  forms[i].setAttribute('novalidate', true);
	}

	// Validate the field
	var hasError = function (field) {

    // Don't validate submits, buttons, file and reset inputs, and disabled fields
    if (field.disabled || field.type === 'file' || field.type === 'reset' || field.type === 'submit' || field.type === 'button') return;

    // Get validity
    var validity = field.validity;

    // If valid, return null
    if (validity.valid) return;

    // If field is required and empty
    if (validity.valueMissing) return $php_vars.error_empty;

    // If not the right type
    if (validity.typeMismatch) {

    	if(field.type === 'email') return $php_vars.error_email;

    	return $php_vars.error_type;
    }

		// If pattern doesn't match
    if (validity.patternMismatch) {

    	// If pattern info is included, return custom error
    	if (field.hasAttribute('title')) return field.getAttribute('title');

    	return $php_vars.error_pattern;
    }

    // If all else fails, return a generic catchall error
    return $php_vars.error_generic;

	};

	// Show the error message
	var showError = function (field, error) {

		// Add error class to field
    field.classList.add(invalidClassName);

    // Get field id or name
    var id = field.id || field.name;
    if (!id) return;

    // Check if error message field already exists
    // If not, create one
    var message = field.form.querySelector('.error-message#error-for-' + id );
    if (!message) {
      message = document.createElement('strong');
      message.className = 'error-message';
      message.id = 'error-for-' + id;
      field.parentNode.insertBefore( message, field.nextSibling );
    }

    // Add ARIA role to the field
    field.setAttribute('aria-invalid', true);
    field.setAttribute('aria-describedby', 'error-for-' + id);

    // Update error message
    message.innerHTML = error;

    // Show error message
    message.style.display = 'block';
    message.style.visibility = 'visible';

	};

	// Remove the error message
	var removeError = function (field) {

    // Remove error class to field
    field.classList.remove(invalidClassName);

    // Remove ARIA role from the field
    field.removeAttribute('aria-invalid');
    field.removeAttribute('aria-describedby');

    // Get field id or name
    var id = field.id || field.name;
    if (!id) return;

    // Check if an error message is in the DOM
    var message = field.form.querySelector('.error-message#error-for-' + id + '');
    if (!message) return;

    // If so, hide it
    message.innerHTML = '';
    message.style.display = 'none';
    message.style.visibility = 'hidden';

	};

	// Set up event listeners for js-validate forms
	forms.forEach(function (form) {

	  // Validate the input on blur
	  form.addEventListener('blur', function(event){

	  	// Validate the field
	  	var error = hasError(event.target);

	  	// If there's an error, show it, else remove it
	    if (error) {
	      showError(event.target, error);
	    } else {
	    	removeError(event.target);
	    }

	  }, true); // true because blur doesn't bubble

	  // Remove the error when the input becomes valid.
	  // 'input' will fire each time the user types
	  form.addEventListener('input', function(event){
	    if (event.target.validity.valid) {
	      removeError(event.target);
	    }
	  });

	  // Validate all fields on submit, then invisible recaptcha check
	  form.addEventListener('submit', function(event){

      event.preventDefault();

	    // Get all of the form elements
	    var fields = event.target.elements;

	    // Validate each field
	    // Store the first field with an error to a variable so we can bring it into focus later
	    var error, hasErrors;
	    for (var i = 0; i < fields.length; i++) {
        error = hasError(fields[i]);
        if (error) {
          showError(fields[i], error);
          if (!hasErrors) {
            hasErrors = fields[i];
          }
        }
	    }

	    // If there are errors, focus on first element with error
	    if (hasErrors) {
        hasErrors.focus();
	    } else {
	    	// If there are no errors, check invisible recaptcha
	    	grecaptcha.execute();
	    }

	  });

	});

})( jQuery );
