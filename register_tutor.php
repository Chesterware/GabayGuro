<?php
require_once 'db_connection.php';
require_once 'api/tutor_tokens.php';
require_once 'api/register_tutor.php';
$errors = [];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Tutor Registration</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003153">
    <link rel="icon" href="/GabayGuroLogo.png">
    <link rel="icon" href="GabayGuroLogo.png" type="image/png">
    <link rel="stylesheet" href="/iskol4rx/styles/register_tutor.css">
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <h2>Welcome, <?= htmlspecialchars($tutor['email']) ?>. Complete your profile:</h2>
        <?php if (!empty($errors)): ?>
            <div style="color:red;">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <label>Password: <input type="password" name="password" required></label><br>
        <label>Confirm Password: <input type="password" name="confirm_password" required></label><br>

        <label>First Name: <input type="text" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"></label><br>
        <label>Middle Initial: <input type="text" name="middle_initial" maxlength="1" value="<?= htmlspecialchars($_POST['middle_initial'] ?? '') ?>"></label><br>
        <label>Last Name: <input type="text" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"></label><br>
        <label>Birthdate: <input type="date" name="birthdate" required value="<?= htmlspecialchars($_POST['birthdate'] ?? '') ?>"></label><br>

        <label>Educational Attainment:
            <select name="educational_attainment" required>
                <option value="">--Select--</option>
                <?php
                $education_options = [
                    'Junior High School Graduate',
                    'Senior High School Graduate',
                    'College Undergraduate',
                    "Associate's Degree",
                    "Bachelor's Degree",
                    "Master's Degree",
                    "Doctoral Degree"
                ];
                $selected_education = $_POST['educational_attainment'] ?? '';
                foreach ($education_options as $option) {
                    $selected = ($option === $selected_education) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($option) . "\" $selected>" . htmlspecialchars($option) . "</option>";
                }
                ?>
            </select>
        </label><br>

        <label>Years of Experience:
            <select name="years_of_experience" required>
                <?php
                $experience_options = [
                    'Less than 1 year',
                    '1-3 years',
                    '4-6 years',
                    '7+ years'
                ];
                $selected_experience = $_POST['years_of_experience'] ?? 'Less than 1 year';
                foreach ($experience_options as $option) {
                    $selected = ($option === $selected_experience) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($option) . "\" $selected>" . htmlspecialchars($option) . "</option>";
                }
                ?>
            </select>
        </label><br>

        <fieldset>
            <legend>Specializations (check all that apply):</legend>
            <?php
            $groupedSpecs = [];
            foreach ($specializations as $spec) {
                $groupedSpecs[$spec['category']][] = $spec;
            }
            $selected_specs = $_POST['specializations'] ?? [];

            foreach ($groupedSpecs as $category => $specs) {
                echo "<strong>" . htmlspecialchars($category) . "</strong><br>";
                foreach ($specs as $spec) {
                    $checked = in_array($spec['specialization_id'], $selected_specs) ? 'checked' : '';
                    echo '<label><input type="checkbox" name="specializations[]" value="' . (int)$spec['specialization_id'] . '" ' . $checked . '> ' . htmlspecialchars($spec['specialization_name']) . '</label><br>';
                }
                echo "<br>";
            }
            ?>
        </fieldset>

        <label>Diploma (PDF/image): <input type="file" name="diploma" accept=".pdf,image/*" required></label><br>
        <label>Other Certificates (PDF/image): <input type="file" name="other_certificates[]" accept=".pdf,image/*" multiple></label><br>

        <label>Rate per Hour: <input type="number" name="rate_per_hour" step="0.01" min="0" value="<?= htmlspecialchars($_POST['rate_per_hour'] ?? '') ?>"></label><br>
        <label>Rate per Session: <input type="number" name="rate_per_session" step="0.01" min="0" value="<?= htmlspecialchars($_POST['rate_per_session'] ?? '') ?>"></label><br>

        <button type="submit">COMPLETE REGISTRATION</button>
    </form>

    <script>
        if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(() => console.log('Service Worker registered!'))
            .catch(err => console.error('Service Worker registration failed:', err));
        }
    </script> 
</body>
</html>