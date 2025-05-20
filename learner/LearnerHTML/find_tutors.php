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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Tutors</title>
    <link rel="icon" href="../../GabayGuroLogo.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../LearnerCSS/find_tutors.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
</head>
<body>
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

    <div class="filter-top full-width">
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
                <div class="tutor-card-wrapper full-width">
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

    <script src="../LearnerJS/find_tutors.js"></script>
</body>
</html>