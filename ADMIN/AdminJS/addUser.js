const addUserBtn = document.getElementById("addUserBtn");
        const registerContainer = document.getElementById("registerContainer");
        const overlay = document.getElementById("overlay");
        const cancelBtn = document.getElementById("cancelBtn");

        addUserBtn.addEventListener("click", () => {
            registerContainer.style.display = "block";
            overlay.style.display = "block";
        });

        cancelBtn.addEventListener("click", () => {
            registerContainer.style.display = "none";
            overlay.style.display = "none";
        });

        overlay.addEventListener("click", () => {
            registerContainer.style.display = "none";
            overlay.style.display = "none";
        });

        function togglePasswordVisibility(inputId) {
            const inputField = document.getElementById(inputId);
            const currentType = inputField.type;

            const fieldsToToggle = [inputId, inputId === "password" ? "confirmPassword" : "password"];

            fieldsToToggle.forEach(field => {
                const fieldElement = document.getElementById(field);
                const fieldIcon = document.getElementById(`toggle${field.charAt(0).toUpperCase() + field.slice(1)}`);

                if (fieldElement.type === "password") {
                    fieldElement.type = "text"; 
                    fieldIcon.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    fieldElement.type = "password";d
                    fieldIcon.classList.replace('fa-eye', 'fa-eye-slash');
                }
            });
        }