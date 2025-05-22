<?php
require_once '../LearnerPHP/ai_gabay.php';
require_once '../LearnerPHP/learner_details.php';

if (!isset($_SESSION['learner_id'])) {
    header("Location: ../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Find Tutors</title>
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../LearnerCSS/index.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
.filter-top {
    background-color: transparent;
    padding: 20px;
    border-radius: 8px;
    margin: 90px 10px 0 240px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    transition: all 0.3s ease;
}

body.sidebar-collapsed .filter-top {
    margin-left: 10px;
    margin-right: 10px;
}

.dropdown-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

select {
    width: 100%;
    padding: 10px 20px 10px 10px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    color: #003153;
    background-color: #FFFFFF;
    margin-top: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

select::-ms-expand {
    display: none;
}

select {
    background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 10 6%22%3E%3Cpath fill=%22none%22 stroke=%22%23003153%22 stroke-width=%221%22 d=%22M1 1l4 4 4-4%22/%3E%3C/svg%3E');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 12px;
}

.choose-rate {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: space-between;
    width: 100%;
}

.choose-rate-btn {
    margin-top: 9px;
    width: 48%;
    height: 38px;
    padding: 10px 30px;
    border: 2px solid #003153;
    color: #003153;
    background-color: transparent;
    border-radius: 5px;
    cursor: pointer;
    font-size: .9rem;
    transition: background-color 0.3s ease, color 0.3s ease;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
}

.choose-rate-btn:hover {
    background-color: #003153;
    color: white;
}

.choose-rate-btn.active {
    background-color: #003153;
    color: white;
}

.enter-rate {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: space-between;
    width: 100%;
    margin-top: 10px;
}

.enter-rate input[type="number"] {
    width: 48%;
    height: 38px;
    padding: 10px 20px 10px 10px;
    border: none;
    border-radius: 5px;
    color: #003153;
    background-color: #FFFFFF;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
    outline: none;
    font-weight: normal;
}

.enter-rate input[type="number"]:focus {
    border: 2px solid #003153;
}

.enter-rate input[type="number"]::-webkit-outer-spin-button,
.enter-rate input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.enter-rate input[type="number"]:invalid {
    border: none;
}

.enter-rate input[type="number"]::placeholder {
    color: #003153;
    font-size: 130%;
}

.enter-rate input[type="hidden"] {
    display: none;
}

#error-message{
    color: #FF000D;
    display: none;
    margin-top: 20px;
    margin-bottom: 0;
}

.subjects {
    background-color: #FFFFFF;
    padding: 5px 20px 20px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.subjects p {
    font-size: 1rem;
    margin-bottom: 5px;
    grid-column: span 4;
    font-style: italic;
    font-weight: bold;
    color: #003153;
}

.subjects label {
    font-size: 1rem;
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    gap: 5px;
    font-weight: bold;
    color: #003153;
}

.subjects input[type="checkbox"] {
    order: -1;
    margin-right: 10px;
}

.subjects input[type="checkbox"]:checked {
    accent-color: #003153;
}

.tutor-list {
    display: none;
}

.tutor-filter {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}

.apply-all {
    margin-top: 10px;
    width: 100%;
    padding: 12px 24px;
    background-color: #003153;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-sizing: border-box;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.apply-all:hover {
    background-color: #002244;
}

.sort-filter {
    color: #FFFFFF;
    display: flex;
    align-items: center;
    margin: 20px 0 20px 0;
}

.sort-filter label {
    margin: 10px 10px 0 0;
    color: #003153;
    font-style: italic;
    font-weight: bold;
}

.sort-filter select {
    margin-left: 18px;
    font-weight: bold; 
    padding: 12px;
    font-size: 1rem;
    border-radius: 5px;
    width: 90%;
    box-sizing: border-box;
}

/* Tutor Card Styles */
.tutor-card-wrapper {
    display: grid;
    grid-template-columns: 1fr 3fr;
    gap: 20px;
    padding: 20px;
    margin: 20px 20px 0px 40px;
    box-sizing: border-box;
    border: 2px solid #003153;
    border-radius: 10px;
    background-color: #FFFFFF;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: margin-left 0.3s ease;
}

body.sidebar-collapsed .tutor-card-wrapper {
    margin-left: 270px;
}

.tutor-profile-column {
    margin: 10px 0 0 0; 
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.tutor-details-column {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 10px;
}

.tutor-details-column p {
    margin-bottom: -10px;
}

.tutor-details-column strong {
    color: #003153;
}

.tutor-add-button {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    color: #FFFFFF;
    background-color: #003153;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 15px;
    transition: background-color 0.3s, color 0.3s;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.tutor-add-button:hover {
    background-color: #002440;
}

.tutor-profile-icon {
    width: 100px;
    height: 100px;
    font-size: 3rem;
    background-color: #eee;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
}

.tutor-name-heading {
    font-weight: bold;
    margin: 5px 0;
}

.star {
    color: #FFD700;
    font-size: 1em;
}

.reviews-container {
    display: none;
    margin: 12px 0 10px 0;
}
.reviews-container.show {
    display: block;
}

.review-item {
    background-color: #FAF9F6;
    border-left: 5px solid #003153;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    transition: background 0.3s ease;
}

.review-item:hover {
    background-color: #F0F0F0;
}

.review-item p {
    margin: 5px 0;
    font-size: 12px;
    font-style: italic;
    color: #000000;
}

.review-item strong {
    color: #003153;
}

.view-reviews-btn {
    background-color:transparent;
    color: #000000;
    border: none;
    margin: 15px 0 0 0;
    cursor: pointer;
    font-size: 1rem;
    display: inline-flex;
}

.view-reviews-btn:hover {
    color: #003153;
    text-decoration: underline;
}

.no-review{
    font-style: italic;
    font-weight:bold;
    color: #8B8B8B;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #FFFFFF;
    border-radius: 8px;
    padding: 30px;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: relative;
}

h4 {
    font-size: 2rem;
    color: #003153;
    margin: 0 0 20px 0;
    text-align: center;
}

.booking-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-field {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-field label {
    font-size: 1em;
    color: #003153;
    width: 30%;
    text-align: left;
}

.form-field input,
.form-field select {
    padding: 10px;
    font-size: 1em;
    border: 1px solid #003153;
    border-radius: 5px;
    width: 100%;
}

.form-field input:focus {
    border: 2px solid #003153;
    outline: none;
    font-weight: bold;
    color: #003153;
}

.form-field select:focus {
    border: 2px solid #003153;
    outline: none;
    font-weight: bold;
    color: #003153;
}

.subject-selection select {
    padding: 10px;
    font-size: 1rem;
    border-radius: 5px;
    width: 100%;
}

.form-action-buttons {
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

.btn-book-now,
.cancel-booking {
    padding: 12px 20px;
    font-size: 1rem;
    border-radius: 5px;
    cursor: pointer;
    width: 48%;
    border: none;
    height: 50px;
}

.btn-book-now {
    color: #FFFFFF;
    background-color: #003153;
}

.cancel-booking {
    color: #003153;
    background-color: transparent;
}

.btn-book-now:hover {
    background-color: #002440;
}

.cancel-booking:hover {
    border: 2px solid #003153;
}

/* Responsive Styles */
@media (max-width: 1200px) {
    .dropdown-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 992px) {
    .dropdown-container {
        grid-template-columns: 1fr;
    }

    .tutor-filter {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 200px;
        padding: 15px 10px;
    }

    .btn {
        font-size: 0.9rem;
        padding: 10px;
    }

    .sidebar-label {
        font-size: 1.25rem;
    }

    .choose-rate-btn {
        width: 100%;
        padding: 12px 0;
        font-size: 1rem;
    }

    .apply-all {
        font-size: 0.9rem;
        padding: 10px 20px;
    }

    .tutor-filter {
        justify-content: center;
    }

    .tutor-card-wrapper {
        flex-direction: column;
        align-items: center;
        padding-top: 60px;
    }

    .tutor-profile-column,
    .tutor-details-column {
        width: 100%;
        text-align: center;
    }

    .reviews-container.show {
        max-height: 200px;
    }
    
    .view-reviews-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .choose-rate-btn {
        width: 100%;
        height: 50px;
        font-size: 1rem;
    }

    .apply-all {
        font-size: 1rem;
        padding: 10px 15px;
    }

    .reviews-container.show {
        max-height: 150px;
    }

    .form-action-buttons {
        flex-direction: column;
    }

    .btn-book-now,
    .cancel-booking {
        width: 100%;
        margin: 10px 0;
    }

    .form-field {
        flex-direction: column;
        align-items: flex-start;
    }

    .form-field label {
        width: 100%;
        margin-bottom: 5px;
    }

    .form-field input,
    .form-field select {
        width: 100%;
    }
}
</style>
<body class="sidebar-collapsed">
    <div class="header-title">
        <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="page-label">FIND TUTORS</div>
        <div class="datetime" id="datetime"></div>
    </div>

    <div class="sidebar">
        <h2 class="learner-name"><?php echo htmlspecialchars($learnerData['full_name']); ?></h2>
        <h3 class="sidebar-label">LEARNER</h3>

        <div class="separator"></div>

        <form action="" method="GET">
            <button type="submit" class="btn active">
                <i class="fas fa-search"></i>Find Tutors
            </button>
        </form>
        <form action="notifications.php" method="GET">
            <button type="submit" class="btn">
                <i class="fas fa-bell"></i>Notifications
            </button>
        </form>
        <form action="booking_history.php" method="GET">
            <button type="submit" class="btn">
                <i class="fas fa-history"></i>Booking History
            </button>
        </form>
        <form action="learner_profile.php" method="GET">
            <button type="submit" class="btn">
                <i class="fas fa-user-cog"></i>My Profile
            </button>
        </form>

        <div class="separator"></div>

        <form action="../../api/logout.php" method="POST" style="margin: 0;">
            <button type="submit" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>

        <div class="filter-top sidebar-collapsed">
        <form action="" method="GET">
            <input type="hidden" name="apply_filters" value="1">
            <div class="dropdown-container">
                <div class="educational-attainmments">
                    <select name="education" id="education">
                        <option value="">Select Educational Level</option>
                        <option value="Junior High School Graduate" <?= ($education == 'Junior High School Graduate') ? 'selected' : '' ?>>Junior High School Graduate</option>
                        <option value="Senior High School Graduate" <?= ($education == 'Senior High School Graduate') ? 'selected' : '' ?>>Senior High School Graduate</option>
                        <option value="College Undergraduate" <?= ($education == 'College Undergraduate') ? 'selected' : '' ?>>College Undergraduate</option>
                        <option value="Associate's Degree" <?= ($education == 'Associate\'s Degree') ? 'selected' : '' ?>>Associate's Degree</option>
                        <option value="Bachelor's Degree" <?= ($education == 'Bachelor\'s Degree') ? 'selected' : '' ?>>Bachelor's Degree</option>
                        <option value="Master's Degree" <?= ($education == 'Master\'s Degree') ? 'selected' : '' ?>>Master's Degree</option>
                        <option value="Doctoral Degree" <?= ($education == 'Doctoral Degree') ? 'selected' : '' ?>>Doctoral Degree</option>
                    </select>
                </div>

                <div class="years-exp">
                    <select name="experience" id="experience">
                        <option value="">Select Experience Range</option>
                        <option value="Less than 1 year" <?= ($experience == 'Less than 1 year') ? 'selected' : '' ?>>Less than 1 year</option>
                        <option value="1-3 years" <?= ($experience == '1-3 years') ? 'selected' : '' ?>>1-3 years</option>
                        <option value="4-6 years" <?= ($experience == '4-6 years') ? 'selected' : '' ?>>4-6 years</option>
                        <option value="7+ years" <?= ($experience == '7+ years') ? 'selected' : '' ?>>7+ years</option>
                    </select>
                </div>

                <div class="enter-rate">
                    <input 
                        type="number" 
                        name="min_rate" 
                        placeholder="Min rate" 
                        step="0.01" 
                        min="0"
                        value="<?= htmlspecialchars($_GET['min_rate'] ?? '') ?>"
                    >
                    <input 
                        type="number" 
                        name="max_rate" 
                        placeholder="Max rate" 
                        step="0.01" 
                        min="0"
                        value="<?= htmlspecialchars($_GET['max_rate'] ?? '') ?>"
                    >
                    <input 
                        type="hidden" 
                        name="rate_type" 
                        id="rate-type-input" 
                        value="<?= htmlspecialchars($_GET['rate_type'] ?? '') ?>"
                    >
                </div>


                <div class="choose-rate">
                    <button 
                        type="button" 
                        class="choose-rate-btn <?= ($_GET['rate_type'] ?? '') === 'session' ? 'active' : '' ?>" 
                        data-value="session"
                    >SESSION</button>
                    
                    <button 
                        type="button" 
                        class="choose-rate-btn <?= ($_GET['rate_type'] ?? '') === 'hour' ? 'active' : '' ?>" 
                        data-value="hour"
                    >HOUR</button>
                </div>
            </div>

            <div class="subjects" id="specializations-container">
                <p>Please check all that apply for specializations:</p>
                
                <label for="Mother_Tongue_JHS">Mother Tongue
                    <input type="checkbox" name="specialization[]" value="1" <?= in_array(1, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="Filipino_JHS">Filipino
                    <input type="checkbox" name="specialization[]" value="2" <?= in_array(2, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="English_JHS">English
                    <input type="checkbox" name="specialization[]" value="3" <?= in_array(3, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="Mathematics_JHS">Mathematics
                    <input type="checkbox" name="specialization[]" value="4" <?= in_array(4, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="Science_JHS">Science
                    <input type="checkbox" name="specialization[]" value="5" <?= in_array(5, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="Araling_Panlipunan_JHS">Social Studies
                    <input type="checkbox" name="specialization[]" value="6" <?= in_array(6, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="ESP_JHS">Values Education
                    <input type="checkbox" name="specialization[]" value="7" <?= in_array(7, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="MAPEH_JHS">MAPEH
                    <input type="checkbox" name="specialization[]" value="8" <?= in_array(8, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="TLE_JHS">TLE
                    <input type="checkbox" name="specialization[]" value="9" <?= in_array(9, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="Research_SHS">Research
                    <input type="checkbox" name="specialization[]" value="10" <?= in_array(10, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="SHS_Math">SHS Math
                    <input type="checkbox" name="specialization[]" value="11" <?= in_array(11, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="SHS_English">SHS Eng
                    <input type="checkbox" name="specialization[]" value="12" <?= in_array(12, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="SHS_Science">SHS Sci
                    <input type="checkbox" name="specialization[]" value="13" <?= in_array(13, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="Humanities_SHS">Humanities and Social Science
                    <input type="checkbox" name="specialization[]" value="14" <?= in_array(14, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="Business_SHS">Business and Accounting
                    <input type="checkbox" name="specialization[]" value="15" <?= in_array(15, $subject_specializations) ? 'checked' : '' ?> />
                </label>
                <label for="ICT_SHS">ICT
                    <input type="checkbox" name="specialization[]" value="16" <?= in_array(16, $subject_specializations) ? 'checked' : '' ?> />
                </label>
            </div>
            
            <div id="error-message">
                    Please select at least one specialization.
            </div>

            <div class="sort-filter">
                <label for="sort_by">SORT BY:</label>
                <select name="sort_by" id="sort_by">
                    <option value="best_match" <?= ($_GET['sort_by'] ?? 'best_match') === 'best_match' ? 'selected' : '' ?>>BEST MATCH</option>
                    <option value="name_asc" <?= ($_GET['sort_by'] ?? '') === 'name_asc' ? 'selected' : '' ?>>A–Z</option>
                    <option value="name_desc" <?= ($_GET['sort_by'] ?? '') === 'name_desc' ? 'selected' : '' ?>>Z–A</option>
                    <option value="rating_desc" <?= ($_GET['sort_by'] ?? '') === 'rating_desc' ? 'selected' : '' ?>>HIGHEST RATING</option>
                    <option value="rate_asc" <?= ($_GET['sort_by'] ?? '') === 'rate_asc' ? 'selected' : '' ?>>LOWEST RATE</option>
                    <option value="experience_desc" <?= ($_GET['sort_by'] ?? '') === 'experience_desc' ? 'selected' : '' ?>>MOST EXPERIENCED</option>
                    <option value="education_desc" <?= ($_GET['sort_by'] ?? '') === 'education_desc' ? 'selected' : '' ?>>HIGHEST EDUCATIONAL ATTAINMENT</option>
                </select>
            </div>

            <div class="tutor-filter">
                <button type="submit" class="apply-all">APPLY ALL</button>
            </div>

        </form>
    </div>

    <div class="tutor-list">
        <?php if (!empty($tutors)): ?>
            <?php foreach ($tutors as $tutor): ?>
                <div class="tutor-card-wrapper">
                    <div class="tutor-profile-column">
                        <div class="tutor-profile-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="tutor-info">
                            <h3><?= htmlspecialchars($tutor['first_name']) . ' ' . htmlspecialchars($tutor['last_name']) ?></h3>
                            <p><i class="fas fa-star star"></i> <?= number_format($tutor['average_rating'], 1) ?> | <?= intval($tutor['num_bookings']) ?> Bookings</p>
                        </div>
                        <form action="" method="GET">
                            <button type="button" class="tutor-add-button" onclick="openBookingModal(<?= $tutor['tutor_id'] ?>)">REQUEST BOOKING</button>
                        </form>
                    </div>

                    <div class="tutor-details-column">
                        <p>Status: <strong><?= htmlspecialchars($tutor['status']) ?></strong></p>
                        <p>Years of Experience: <strong><?= htmlspecialchars($tutor['years_of_experience']) ?></strong></p>
                        <p>Educational Attainment: <strong><?= htmlspecialchars($tutor['educational_attainment']) ?></strong></p>
                        <p>Rate per Hour: <strong>₱<?= number_format($tutor['rate_per_hour'], 2) ?> per hour</strong></p>
                        <p>Rate per Session: <strong>₱<?= number_format($tutor['rate_per_session'], 2) ?> per session</strong></p>
                        <p>Specializations: <strong><?= !empty($tutor['specialization_names']) ? htmlspecialchars(implode(', ', $tutor['specialization_names'])) : 'None' ?></strong></p>

                        <div class="reviews-section">
                            <?php $reviewCount = count($tutor['reviews'] ?? []); ?>

                            <?php if ($reviewCount > 0): ?>
                                <a onclick="toggleReviews(this)" class="view-reviews-btn">
                                    View Reviews (<?= $reviewCount ?>)
                                </a>
                            <?php else: ?>
                                <p class="no-review">No reviews yet.</p>
                            <?php endif; ?>

                            <div class="reviews-container">
                                <?php if (!empty($tutor['reviews']) && is_array($tutor['reviews'])): ?>
                                    <?php foreach ($tutor['reviews'] as $review): ?>
                                        <div class="review-item">
                                            <p><strong>Rating:</strong> <?= htmlspecialchars($review['rating']) ?>/5</p>
                                            <p><strong>Review:</strong> <?= htmlspecialchars($review['review_text']) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif ($no_tutors_found): ?>
            <p>No tutors found based on the selected filters.</p>
        <?php endif; ?>
    </div>

    <div id="bookingFormModal" class="modal booking-form-modal">
        <div class="modal-content booking-form-content">
            <h4>BOOKING FORM</h4>
            <form class="booking-form" method="POST" action="../LearnerPHP/request_booking.php">
            <input type="hidden" name="tutor_id" id="modal-tutor-id" value="">
                <div class="form-field">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="Enter your address" required />
                </div>
                <div class="form-field">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required />
                </div>
                <div class="form-field">
                    <label for="time">Start Time:</label>
                    <input type="time" id="time" name="start_time" required />
                </div>
                <div class="form-field">
                    <label for="time">End Time:</label>
                    <input type="time" id="time" name="end_time" required />
                </div>
                <div class="form-field">
                    <label for="offer">Offer:</label>
                    <input type="number" id="offer" name="offer" placeholder="Enter offer amount" min="1" step="1" required />
                </div>

                <div class="form-field">
                    <label for="subject-dropdown">Subject:</label>
                    <select id="subject-dropdown" name="subject" required>
                        <option value="Mother Tongue" <?= in_array(1, $subject_specializations) ? 'selected' : '' ?>>Mother Tongue</option>
                        <option value="Filipino" <?= in_array(2, $subject_specializations) ? 'selected' : '' ?>>Filipino</option>
                        <option value="English" <?= in_array(3, $subject_specializations) ? 'selected' : '' ?>>English</option>
                        <option value="Mathematics" <?= in_array(4, $subject_specializations) ? 'selected' : '' ?>>Mathematics</option>
                        <option value="Science" <?= in_array(5, $subject_specializations) ? 'selected' : '' ?>>Science</option>
                        <option value="Social Studies" <?= in_array(6, $subject_specializations) ? 'selected' : '' ?>>Social Studies</option>
                        <option value="Values Education" <?= in_array(7, $subject_specializations) ? 'selected' : '' ?>>Values Education</option>
                        <option value="MAPEH" <?= in_array(8, $subject_specializations) ? 'selected' : '' ?>>MAPEH</option>
                        <option value="TLE" <?= in_array(9, $subject_specializations) ? 'selected' : '' ?>>TLE</option>
                        <option value="Research" <?= in_array(10, $subject_specializations) ? 'selected' : '' ?>>Research</option>
                        <option value="Humanities and Social Science" <?= in_array(14, $subject_specializations) ? 'selected' : '' ?>>Humanities and Social Science</option>
                        <option value="Business and Accounting" <?= in_array(15, $subject_specializations) ? 'selected' : '' ?>>Business and Accounting</option>
                        <option value="ICT" <?= in_array(16, $subject_specializations) ? 'selected' : '' ?>>ICT</option>
                    </select>
                </div>

                <div class="form-action-buttons">
                    <button type="button" class="cancel-booking">CANCEL</button>
                    <button type="submit" class="btn-book-now">SEND REQUEST</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    function toggleSidebar() {
        document.body.classList.toggle("sidebar-collapsed");
    }

    function updateDateTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();
        document.getElementById('datetime').innerHTML = `${date} ${time}`;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();

    const noTutorsFound = <?php echo json_encode($no_tutors_found); ?>;
        const tutorListContainer = document.querySelector('.tutor-list');
        const noTutorsMessage = document.createElement('p');

        if (noTutorsFound) {
            noTutorsMessage.textContent = 'No tutors found based on the selected filters.';
            tutorListContainer.innerHTML = '';
            tutorListContainer.appendChild(noTutorsMessage);
        } else {
            tutorListContainer.style.display = 'block';
        }
    </script>
</body>
</html>