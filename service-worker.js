const CACHE_NAME = "gabayguro-cache-v1";
const urlsToCache = [
    "/",
    "/ADMIN/AdminCSS/admin_dashboard.css",
    "/ADMIN/AdminCSS/index.css",
    "/ADMIN/AdminCSS/manage_users.css",
    "/ADMIN/AdminCSS/tutor_verification.css",
    "/ADMIN/AdminJS/adminProfile.js",
    "/ADMIN/AdminJS/time_date.js",
    "/ADMIN/AdminJS/tutor_verification.js",

    "/learner/LearnerCSS/booking_history.css",
    "/learner/LearnerCSS/find_tutors.css",
    "/learner/LearnerCSS/learner_profile.css",
    "/learner/LearnerCSS/notifications.css",
    "/learner/LearnerJS/booking_history.js",
    "/learner/LearnerJS/find_tutors.js",
    "/learner/LearnerJS/notifications.js",

    "/styles/forgotPassword.css",
    "/styles/login.css",
    "/styles/register.css",
    "/styles/register_tutor.css",
    "/styles/register_learner.css",
    "/styles/reset_password.css",
    "/styles/select_role.css",
    "/interact/login.js",

    "/tutor/TutorCSS/ratings_review.css",
    "/tutor/TutorCSS/tutor_booking.css",
    "/tutor/TutorCSS/tutor_profile.css",
    "/tutor/TutorJS/ratings_review.js",
    "/tutor/TutorJS/tutor_booking.js",

    "/GabayGuroLogo.png"
];

self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});
