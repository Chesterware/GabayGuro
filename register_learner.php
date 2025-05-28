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
    <link rel="stylesheet" href="/iskol4rx/styles/register_learner.css">
</head>
<body>
  <div class="form-container">
    <h2>Welcome, <?= htmlspecialchars($learner['email']) ?>. Complete your profile:</h2>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
      <div class="form-group">
        <label for="password">Password:</label>
        <input id="password" type="password" name="password" required>
      </div>
      
      <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input id="confirm_password" type="password" name="confirm_password" required>
      </div>

      <div class="form-group">
        <label for="profile_photo">Upload Profile Photo:</label>
        <input id="profile_photo" type="file" name="profile_photo" accept="image/*" required>
      </div>

      <div class="form-group">
        <label for="first_name">First Name:</label>
        <input id="first_name" type="text" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
      </div>
      
      <div class="form-group">
        <label for="middle_initial">Middle Initial:</label>
        <input id="middle_initial" type="text" maxlength="1" name="middle_initial" value="<?= htmlspecialchars($_POST['middle_initial'] ?? '') ?>">
      </div>
      
      <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input id="last_name" type="text" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
      </div>
      
      <div class="form-group">
        <label for="birthdate">Birthdate:</label>
        <input id="birthdate" type="date" name="birthdate" required value="<?= htmlspecialchars($_POST['birthdate'] ?? '') ?>">
      </div>
      
      <div class="form-group">
        <label for="school_affiliation">School Affiliation:</label>
        <input id="school_affiliation" type="text" name="school_affiliation" value="<?= htmlspecialchars($_POST['school_affiliation'] ?? '') ?>">
      </div>
      
      <div class="form-group">
        <label for="grade_level">Grade Level:</label>
        <select id="grade_level" name="grade_level" required>
          <?php
            $grade_options = ['N/A','G7','G8','G9','G10','G11','G12'];
            $selected_grade = $_POST['grade_level'] ?? '';
            foreach ($grade_options as $grade) {
              $selected = ($grade === $selected_grade) ? 'selected' : '';
              echo "<option value=\"" . htmlspecialchars($grade) . "\" $selected>" . htmlspecialchars($grade) . "</option>";
            }
          ?>
        </select>
      </div>

      <div id="strand_container" style="display:none;">
        <label for="strand">Strand:</label>
        <select id="strand" name="strand">
          <?php
            $strand_options = ['N/A','STEM','ABM','HUMSS','GAS'];
            $selected_strand = $_POST['strand'] ?? 'N/A';
            foreach ($strand_options as $strand) {
              $selected = ($strand === $selected_strand) ? 'selected' : '';
              echo "<option value=\"" . htmlspecialchars($strand) . "\" $selected>" . htmlspecialchars($strand) . "</option>";
            }
          ?>
        </select>
      </div>

      <button type="submit">COMPLETE REGISTRATION</button>
    </form>
  </div>

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