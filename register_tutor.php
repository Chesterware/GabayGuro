<?php
require_once 'api/register_tutor.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Registration - GABAYGURO</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=Inter:wght@400;500&family=Raleway:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/iskol4rx/styles/register_tutor.css">
</head>
<body>
    <div class="header">
        <div class="logo"></div>
        <h1 class="title">Welcome to GABAYGURO</h1>
    </div>
    
    <div class="registration-container">
        <div class="registration-box">
            <a href="register.php" class="back-arrow"></a>
            <h2 class="registration-title">Register as TUTOR</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
                <div class="text-center">
                    <a href="../tutor/TutorHTML/tutor_booking.php" class="btn-login">Proceed to Dashboard</a>
                </div>
            <?php else: ?>
            
            <form method="POST" enctype="multipart/form-data" class="registration-form">
                <div class="form-section">
                    <h3 class="section-title">Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                        </div>
                        <div class="form-group middle-initial">
                            <label for="middle_initial">Middle Initial</label>
                            <input type="text" id="middle_initial" name="middle_initial" maxlength="1" value="<?php echo isset($_POST['middle_initial']) ? htmlspecialchars($_POST['middle_initial']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Birthdate *</label>
                            <input type="date" id="birthdate" name="birthdate" required value="<?php echo isset($_POST['birthdate']) ? htmlspecialchars($_POST['birthdate']) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3 class="section-title">Qualifications</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="educational_attainment">Highest Education *</label>
                            <select id="educational_attainment" name="educational_attainment" required>
                                <option value="">-- Select --</option>
                                <option value="Junior High School Graduate" <?php echo (isset($_POST['educational_attainment']) && $_POST['educational_attainment'] == 'Junior High School Graduate') ? 'selected' : ''; ?>>Junior High School Graduate</option>
                                <option value="Senior High School Graduate" <?php echo (isset($_POST['educational_attainment']) && $_POST['educational_attainment'] == 'Senior High School Graduate') ? 'selected' : ''; ?>>Senior High School Graduate</option>
                                <option value="College Undergraduate" <?php echo (isset($_POST['educational_attainment']) && $_POST['educational_attainment'] == 'College Undergraduate') ? 'selected' : ''; ?>>College Undergraduate</option>
                                <option value="Associate's Degree" <?php echo (isset($_POST['educational_attainment']) && $_POST['educational_attainment'] == 'Associate\'s Degree') ? 'selected' : ''; ?>>Associate's Degree</option>
                                <option value="Bachelor's Degree" <?php echo (isset($_POST['educational_attainment']) && $_POST['educational_attainment'] == 'Bachelor\'s Degree') ? 'selected' : ''; ?>>Bachelor's Degree</option>
                                <option value="Master's Degree" <?php echo (isset($_POST['educational_attainment']) && $_POST['educational_attainment'] == 'Master\'s Degree') ? 'selected' : ''; ?>>Master's Degree</option>
                                <option value="Doctoral Degree" <?php echo (isset($_POST['educational_attainment']) && $_POST['educational_attainment'] == 'Doctoral Degree') ? 'selected' : ''; ?>>Doctoral Degree</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="years_of_experience">Years of Experience *</label>
                            <select id="years_of_experience" name="years_of_experience" required>
                                <option value="">-- Select --</option>
                                <option value="Less than 1 year" <?php echo (isset($_POST['years_of_experience']) && $_POST['years_of_experience'] == 'Less than 1 year') ? 'selected' : ''; ?>>Less than 1 year</option>
                                <option value="1-3 years" <?php echo (isset($_POST['years_of_experience']) && $_POST['years_of_experience'] == '1-3 years') ? 'selected' : ''; ?>>1-3 years</option>
                                <option value="4-6 years" <?php echo (isset($_POST['years_of_experience']) && $_POST['years_of_experience'] == '4-6 years') ? 'selected' : ''; ?>>4-6 years</option>
                                <option value="7+ years" <?php echo (isset($_POST['years_of_experience']) && $_POST['years_of_experience'] == '7+ years') ? 'selected' : ''; ?>>7+ years</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="specialization-label">Subject Specialization *</label>
                        <div class="specialization-grid">
                            <?php
                            $specs = $conn->query("SELECT * FROM specializations ORDER BY specialization_name");
                            while ($spec = $specs->fetch_assoc()) {
                                $checked = (isset($_POST['specializations']) && in_array($spec['specialization_id'], $_POST['specializations'])) ? 'checked' : '';
                                echo '<div class="specialization-item">';
                                echo '<input type="checkbox" id="spec-'.$spec['specialization_id'].'" name="specializations[]" value="'.$spec['specialization_id'].'" '.$checked.'>';
                                echo '<label for="spec-'.$spec['specialization_id'].'">'.htmlspecialchars($spec['specialization_name']).'</label>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <div class="form-hint">Select at least one specialization</div>
                    </div>
                </div>
                
                <!-- Rates Section -->
                <div class="form-section">
                    <h3 class="section-title">Rates</h3>
                    <div class="form-grid">
                        <div class="form-group rate-input">
                            <label for="rate_per_hour">Rate Per Hour (₱) *</label>
                            <input type="number" id="rate_per_hour" name="rate_per_hour" min="0" step="0.01" required value="<?php echo isset($_POST['rate_per_hour']) ? htmlspecialchars($_POST['rate_per_hour']) : ''; ?>">
                        </div>
                        <div class="form-group rate-input">
                            <label for="rate_per_session">Rate Per Session (₱) *</label>
                            <input type="number" id="rate_per_session" name="rate_per_session" min="0" step="0.01" required value="<?php echo isset($_POST['rate_per_session']) ? htmlspecialchars($_POST['rate_per_session']) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Documents Section -->
                <div class="form-section">
                    <h3 class="section-title">Documents</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="diploma">Diploma (PDF/Image) *</label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="diploma" name="diploma" accept=".pdf,.jpg,.jpeg,.png" required>
                                <span class="file-upload-label">Choose file</span>
                                <div class="file-upload-hint">Max 5MB - PDF, JPG, PNG</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="certificates">Other Certificates (Optional)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="certificates" name="certificates" accept=".pdf,.jpg,.jpeg,.png">
                                <span class="file-upload-label">Choose file</span>
                                <div class="file-upload-hint">Max 5MB - PDF, JPG, PNG</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-footer">
                    <button type="submit" class="submit-btn">Complete Registration</button>
                    <div class="form-note">*All fields are required</div>
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
                    } else {
                        label.textContent = 'Choose file';
                        label.style.borderColor = '#ddd';
                        label.style.backgroundColor = '#f8f9fa';
                    }
                });
            });

            // Form validation
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    let isValid = true;
                    const errorMessages = [];
                    
                    // Check required fields
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
                    
                    // Special check for tutor specializations
                    const checkedSpecs = this.querySelectorAll('input[name="specializations[]"]:checked');
                    if (checkedSpecs.length === 0) {
                        const specLabel = this.querySelector('.specialization-label');
                        specLabel.style.color = '#e74c3c';
                        isValid = false;
                        errorMessages.push('Please select at least one specialization');
                    } else {
                        const specLabel = this.querySelector('.specialization-label');
                        specLabel.style.color = '';
                    }
                    
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
        });
    </script>
</body>
</html>