<?php
require_once 'db_connection.php';
require_once 'api/learner_tokens.php';
require_once 'api/register_learner.php';

$errors = [];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Learner Registration</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003153">
    <link rel="icon" href="/GabayGuroLogo.png">
    <link rel="icon" href="GabayGuroLogo.png" type="image/png">
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($learner['email']) ?>. Complete your profile:</h2>

    <?php if (!empty($errors)): ?>
        <div style="color:red;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <label>Password: <input type="password" name="password" required></label><br>
        <label>Confirm Password: <input type="password" name="confirm_password" required></label><br>

        <label>First Name: <input type="text" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"></label><br>
        <label>Middle Initial: <input type="text" name="middle_initial" maxlength="1" value="<?= htmlspecialchars($_POST['middle_initial'] ?? '') ?>"></label><br>
        <label>Last Name: <input type="text" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"></label><br>
        <label>Birthdate: <input type="date" name="birthdate" required value="<?= htmlspecialchars($_POST['birthdate'] ?? '') ?>"></label><br>

        <label>School Affiliation: <input type="text" name="school_affiliation" value="<?= htmlspecialchars($_POST['school_affiliation'] ?? '') ?>"></label><br>

        <label>Grade Level:
            <select name="grade_level" id="grade_level" required>
                <?php
                $grade_options = ['N/A','G7','G8','G9','G10','G11','G12'];
                $selected_grade = $_POST['grade_level'] ?? '';
                foreach ($grade_options as $grade) {
                    $selected = ($grade === $selected_grade) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($grade) . "\" $selected>" . htmlspecialchars($grade) . "</option>";
                }
                ?>
            </select>
        </label><br>

        <div id="strand_container" style="display:none;">
            <label>Strand:
                <select name="strand" id="strand">
                    <?php
                    $strand_options = ['N/A','STEM','ABM','HUMSS','GAS'];
                    $selected_strand = $_POST['strand'] ?? 'N/A';
                    foreach ($strand_options as $strand) {
                        $selected = ($strand === $selected_strand) ? 'selected' : '';
                        echo "<option value=\"" . htmlspecialchars($strand) . "\" $selected>" . htmlspecialchars($strand) . "</option>";
                    }
                    ?>
                </select>
            </label><br>
        </div>

        <button type="submit">Complete Registration</button>
    </form>

    <script>
        function toggleStrand() {
            const gradeSelect = document.getElementById('grade_level');
            const strandDiv = document.getElementById('strand_container');
            if (gradeSelect.value === 'G11' || gradeSelect.value === 'G12') {
                strandDiv.style.display = 'block';
            } else {
                strandDiv.style.display = 'none';
                document.getElementById('strand').value = 'N/A';
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            toggleStrand();
            document.getElementById('grade_level').addEventListener('change', toggleStrand);
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(() => console.log('Service Worker registered!'))
            .catch(err => console.error('Service Worker registration failed:', err));
        }
    </script> 
</body>
</html>