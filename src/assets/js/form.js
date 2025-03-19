

document.addEventListener('DOMContentLoaded', function() {

//     if (window.location.pathname === "/" || window.location.pathname === "/index.html") {

//     setTimeout(function() {
//         document.body.classList.add("form-active");
//     }, 600); 
// }

    document.querySelectorAll(".form-button").forEach((button) => {
        const form = document.getElementById("cs-form-1150");
        
        button.addEventListener("click", () => {
            console.log("CLICK");
            document.body.classList.add("form-active");
        });
    });
    
    document.getElementById("close-form").addEventListener("click", () => {
        document.body.classList.remove("form-active");
    });
    
    document.body.addEventListener("click", (e) => {
        if (e.target.tagName === "BODY" && e.target.classList.contains("form-active")) {
            document.body.classList.remove("form-active");
        }
    });

    let isSubmitting = false;

    document.getElementById('cs-form-1150').addEventListener('submit', function(event) {
        event.preventDefault();


        if (isSubmitting) {
            console.log("Form is already being submitted, please wait.");
            return; // Exit if the form is already being processed
        }

        isSubmitting = true;
        var submitButton = document.querySelector('#cs-form-1150 button[type="submit"]');
        submitButton.disabled = true; // Disable the submit button

        var formData = new FormData(this);


        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'https://sunningdaleheights.com/submit-form.php', true);
        xhr.onload = function() {

            isSubmitting = false;

            if (this.status == 200) {
                var response = JSON.parse(this.responseText);
                var errorsContainer = document.getElementById('formErrors');
                var successContainer = document.getElementById('formSuccess');
                        
                if (response.errors && response.errors.length > 0) {
                    var errorsHtml = '<ul>';
                    response.errors.forEach(function(error) {
                        errorsHtml += '<li>' + error + '</li>';
                    });
                    errorsHtml += '</ul>';
                    errorsContainer.innerHTML = errorsHtml;
                    errorsContainer.style.display = 'block'; // Ensure errors are visible
                    successContainer.style.display = 'none'; // Hide success message
                    submitButton.disabled = false; 
                } else if (response.success) {
                    errorsContainer.innerHTML = ''; // Clear any previous errors
                    errorsContainer.style.display = 'none'; // Ensure errors are hidden
                    successContainer.innerHTML = 'Email sent successfully';
                    successContainer.style.display = 'block'; // Show success message
                    document.getElementById('cs-form-1150').reset(); // Optionally, clear the form fields
                    submitButton.disabled = false; 
                }
            } else {
                // Handle HTTP error status
                isSubmitting = false;
                submitButton.disabled = false; 
                console.error('An error occurred with the request.');
            }
        };
        xhr.onerror = function() {
            // Handle network errors
            isSubmitting = false;
            submitButton.disabled = false; 
            console.error('The request failed due to a network error.');
        };
        xhr.send(formData);
    });

});