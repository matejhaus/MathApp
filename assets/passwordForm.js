document.addEventListener('DOMContentLoaded', function () {
    const openModalButton = document.getElementById('openPasswordModalButton');
    if (openModalButton) {
        openModalButton.addEventListener('click', function () {
            let themeId = this.getAttribute('data-id-theme');
            fetch('/theme/test/check-password/' + themeId)
                .then(response => response.text())
                .then(data => {
                    if (data.redirect) {
                        console.log(data.redirect);
                        window.location.href = data.redirect;
                    } else {
                        let passwordFormContainer = document.getElementById('passwordFormContainer');
                        passwordFormContainer.innerHTML = data;

                        document.getElementById('passwordModal').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.log('Error:', error);
                });
        });
    }

    const modal = document.getElementById("passwordModal");
    const closeButton = document.querySelector(".close");

    function openModal() {
        modal.style.display = "block";
    }

    if (closeButton) {
        closeButton.addEventListener("click", function() {
            modal.style.display = "none";
        });
    }

    if(modal){
        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    }
});

