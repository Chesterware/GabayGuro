<?php
require_once 'api/register_learner.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learner Registration - GABAYGURO</title>
    <link rel="icon" href="GabayGuroLogo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=Inter:wght@400;500&family=Raleway:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/iskol4rx/styles/register_learner.css">
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h1 class="title">Welcome to GABAYGURO</h1>
    </div>
    
    <div class="registration-container">
        <div class="registration-box">
            <a href="register.php" class="back-arrow"></a>
            <h2 class="registration-title">Register as LEARNER</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?= $success ?></div>
                <div class="text-center">
                    <a href="../learner/LearnerHTML/booking_history.php" class="btn-login">Proceed to Dashboard</a>
                </div>
            <?php else: ?>
            
            <form method="POST" enctype="multipart/form-data" class="registration-form">
                <div class="form-section">
                    <h3 class="section-title">Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group middle-initial">
                            <label for="middle_initial">Middle Initial</label>
                            <input type="text" id="middle_initial" name="middle_initial" maxlength="1">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate *</label>
                            <input type="date" id="birthdate" name="birthdate" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_picture">Profile Picture</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="profile_picture" name="profile_picture" accept=".jpg,.jpeg,.png">
                            <span class="file-upload-label">Choose file</span>
                            <div class="file-upload-hint">Max 2MB - JPG, PNG</div>
                        </div>
                    </div>
                </div>
                
                <!-- School Information Section -->
                <div class="form-section">
                    <h3 class="section-title">School Information</h3>
                    <div class="form-group">
                        <label for="school_affiliation">School Name *</label>
                        <input type="text" id="school_affiliation" name="school_affiliation" required>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="grade_level">Grade Level *</label>
                            <select id="grade_level" name="grade_level" required>
                                <option value="">-- Select --</option>
                                <option value="G7">Grade 7</option>
                                <option value="G8">Grade 8</option>
                                <option value="G9">Grade 9</option>
                                <option value="G10">Grade 10</option>
                                <option value="G11">Grade 11</option>
                                <option value="G12">Grade 12</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="strand">Strand *</label>
                            <select id="strand" name="strand" required>
                                <option value="">-- Select --</option>
                                <option value="N/A">N/A (For JHS)</option>
                                <option value="STEM">STEM</option>
                                <option value="ABM">ABM</option>
                                <option value="HUMSS">HUMSS</option>
                                <option value="GAS">GAS</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-footer">
                    <button type="submit" class="submit-btn">Complete Registration</button>
                    <div class="form-note">* Required fields</div>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File upload display
            const fileInputs = document.querySelectorAll('input[type="file"]');
            
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const wrapper = this.closest('.file-upload-wrapper');
                    const label = wrapper.querySelector('.file-upload-label');
                    
                    if (this.files && this.files.length > 0) {
                        label.textContent = this.files[0].name;
                        label.style.borderColor = '#27ae60';
                        label.style.backgroundColor = 'rgba(39, 174, 96, 0.1)';
                        
                        // Show preview for profile picture
                        if (this.id === 'profile_picture' && this.files[0].type.match('image.*')) {
                            const preview = document.createElement('img');
                            preview.className = 'profile-picture-preview';
                            preview.style.display = 'block';
                            
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                            }
                            reader.readAsDataURL(this.files[0]);
                            
                            const existingPreview = wrapper.querySelector('.profile-picture-preview');
                            if (existingPreview) {
                                existingPreview.remove();
                            }
                            wrapper.appendChild(preview);
                        }
                    } else {
                        label.textContent = 'Choose file';
                        label.style.borderColor = '#ddd';
                        label.style.backgroundColor = '#f8f9fa';
                        
                        // Remove preview if exists
                        const preview = wrapper.querySelector('.profile-picture-preview');
                        if (preview) {
                            preview.remove();
                        }
                    }
                });
            });

            // Form validation
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    let isValid = true;
                    const errorMessages = [];
                    
                    const requiredFields = this.querySelectorAll('[required]');
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.style.borderColor = '#e74c3c';
                            isValid = false;
                            errorMessages.push(`Please fill in ${field.labels[0].textContent}`);
                        } else {
                            field.style.borderColor = '#ddd';
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        
                        const errorDiv = this.querySelector('.error-message') || document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.innerHTML = `
                            <strong>Please fix the following errors:</strong>
                            <ul style="margin-top: 0.5rem; padding-left: 1.5rem">
                                ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                            </ul>
                        `;
                        
                        if (!this.querySelector('.error-message')) {
                            this.insertBefore(errorDiv, this.firstChild);
                        }
                        
                        const firstError = this.querySelector('[style*="border-color: rgb(231, 76, 60)"]');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstError.focus();
                        }
                    }
                });
            });
            
            document.querySelectorAll('input, select').forEach(field => {
                field.addEventListener('input', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.style.borderColor = '#e74c3c';
                    } else {
                        this.style.borderColor = '#ddd';
                    }
                });
            });
            const gradeLevelSelect = document.getElementById('grade_level');
            const strandSelect = document.getElementById('strand');
            
            if (gradeLevelSelect && strandSelect) {
                gradeLevelSelect.addEventListener('change', function() {
                    if (this.value && ['G11', 'G12'].includes(this.value)) {
                        document.querySelector('.grade-level-info').style.display = 'block';
                    } else {
                        document.querySelector('.grade-level-info').style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>