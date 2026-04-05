<?php
// includes/config.php - Add language settings

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bus_ticket_system');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

define('SITE_NAME', 'TROTTER.com');
define('SITE_URL', 'http://localhost/bus-ticket-system/');

date_default_timezone_set('Asia/Dhaka');

// Language settings
$available_languages = ['en' => 'English', 'bn' => 'বাংলা'];

// Get user's language preference
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $lang_query = "SELECT language FROM users WHERE user_id = '$user_id'";
    $lang_result = mysqli_query($conn, $lang_query);
    if($lang_result && mysqli_num_rows($lang_result) > 0) {
        $lang_data = mysqli_fetch_assoc($lang_result);
        $_SESSION['language'] = $lang_data['language'];
    }
}

// Set default language
if(!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'en';
}

$current_lang = $_SESSION['language'];

// Language texts
$lang = [];

// English translations
$lang['en'] = [
    'home' => 'Home',
    'bus' => 'Bus',
    'air' => 'Air',
    'train' => 'Train',
    'launch' => 'Launch',
    'event' => 'Event',
    'park' => 'Park',
    'login' => 'Login',
    'register' => 'Register',
    'logout' => 'Logout',
    'my_profile' => 'My Profile',
    'my_bookings' => 'My Bookings',
    'settings' => 'Settings',
    'welcome' => 'Welcome to',
    'leading_platform' => "Bangladesh's Leading Online Ticketing Platform",
    'one_way' => 'One Way',
    'round_trip' => 'Round Trip',
    'from' => 'From',
    'to' => 'To',
    'journey_date' => 'Journey Date',
    'return_date' => 'Return Date',
    'search' => 'SEARCH',
    'select_city' => 'Select City',
    'our_services' => 'Our Services',
    'book_bus_tickets' => 'Book bus tickets',
    'flight_tickets' => 'Flight tickets',
    'rail_tickets' => 'Rail tickets',
    'launch_tickets' => 'Launch tickets',
    'event_tickets' => 'Event tickets',
    'parking_tickets' => 'Parking tickets',
    'discover_trending' => 'Discover Trending Destinations',
    'secure_payment' => 'Secure Payment',
    'secure_transactions' => '100% secure transactions',
    'support_247' => '24/7 Support',
    'support_text' => 'Customer support anytime',
    'easy_cancellation' => 'Easy Cancellation',
    'cancel_easily' => 'Cancel tickets easily',
    'mobile_app' => 'Mobile App',
    'book_on_go' => 'Book on the go',
    'whatsapp_booking' => 'Book Bus Tickets on WhatsApp!',
    'whatsapp_text' => 'Save 16374 to start booking via WhatsApp',
    'chat_whatsapp' => 'Chat on WhatsApp',
    'download_app' => 'Download Our App',
    'download_text' => 'Get the best experience with our mobile app',
    'google_play' => 'Google Play',
    'app_store' => 'App Store',
    'about_us' => 'About Us',
    'contact_us' => 'Contact Us',
    'terms_conditions' => 'Terms & Conditions',
    'privacy_policy' => 'Privacy Policy',
    'faq' => 'FAQ',
    'copyright' => 'All rights reserved',
    'version' => 'Version',
    'secure_payment_text' => 'Secure payment',
    'safe_booking' => '100% safe booking'
];

// Bengali translations
$lang['bn'] = [
    'home' => 'হোম',
    'bus' => 'বাস',
    'air' => 'এয়ার',
    'train' => 'ট্রেন',
    'launch' => 'লঞ্চ',
    'event' => 'ইভেন্ট',
    'park' => 'পার্ক',
    'login' => 'লগইন',
    'register' => 'রেজিস্টার',
    'logout' => 'লগআউট',
    'my_profile' => 'আমার প্রোফাইল',
    'my_bookings' => 'আমার বুকিং',
    'settings' => 'সেটিংস',
    'welcome' => 'স্বাগতম',
    'leading_platform' => 'বাংলাদেশের শীর্ষস্থানীয় অনলাইন টিকেটিং প্ল্যাটফর্ম',
    'one_way' => 'এক পথ',
    'round_trip' => 'দুই পথ',
    'from' => 'যেখান থেকে',
    'to' => 'যেখানে',
    'journey_date' => 'ভ্রমণের তারিখ',
    'return_date' => 'ফেরার তারিখ',
    'search' => 'খুঁজুন',
    'select_city' => 'শহর নির্বাচন করুন',
    'our_services' => 'আমাদের সেবাসমূহ',
    'book_bus_tickets' => 'বাস টিকেট বুক করুন',
    'flight_tickets' => 'ফ্লাইট টিকেট',
    'rail_tickets' => 'ট্রেন টিকেট',
    'launch_tickets' => 'লঞ্চ টিকেট',
    'event_tickets' => 'ইভেন্ট টিকেট',
    'parking_tickets' => 'পার্কিং টিকেট',
    'discover_trending' => 'জনপ্রিয় গন্তব্যস্থল',
    'secure_payment' => 'নিরাপদ পেমেন্ট',
    'secure_transactions' => '১০০% নিরাপদ লেনদেন',
    'support_247' => '২৪/৭ সাপোর্ট',
    'support_text' => 'যেকোনো সময় সাপোর্ট',
    'easy_cancellation' => 'সহজ বাতিল',
    'cancel_easily' => 'সহজে টিকেট বাতিল',
    'mobile_app' => 'মোবাইল অ্যাপ',
    'book_on_go' => 'যেকোনো সময় বুকিং',
    'whatsapp_booking' => 'হোয়াটসঅ্যাপে বাস টিকেট বুক করুন!',
    'whatsapp_text' => '১৬৩৭৪ সেভ করে হোয়াটসঅ্যাপে বুকিং শুরু করুন',
    'chat_whatsapp' => 'হোয়াটসঅ্যাপে চ্যাট করুন',
    'download_app' => 'আমাদের অ্যাপ ডাউনলোড করুন',
    'download_text' => 'আমাদের মোবাইল অ্যাপ দিয়ে সেরা অভিজ্ঞতা নিন',
    'google_play' => 'গুগল প্লে',
    'app_store' => 'অ্যাপ স্টোর',
    'about_us' => 'আমাদের সম্পর্কে',
    'contact_us' => 'যোগাযোগ',
    'terms_conditions' => 'শর্তাবলী',
    'privacy_policy' => 'গোপনীয়তা নীতি',
    'faq' => 'জিজ্ঞাসা',
    'copyright' => 'সকল অধিকার সংরক্ষিত',
    'version' => 'সংস্করণ',
    'secure_payment_text' => 'নিরাপদ পেমেন্ট',
    'safe_booking' => '১০০% নিরাপদ বুকিং'
];

// Function to get translation
function __($key) {
    global $lang, $current_lang;
    return isset($lang[$current_lang][$key]) ? $lang[$current_lang][$key] : (isset($lang['en'][$key]) ? $lang['en'][$key] : $key);
}

// Other functions...
function generateUserId() {
    return 'USR' . time() . rand(100, 999);
}

function generateBookingId() {
    return 'BOK' . time() . rand(100, 999);
}

function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    global $current_lang;
    if(strpos($url, '?') !== false) {
        $url .= '&lang=' . $current_lang;
    } else {
        $url .= '?lang=' . $current_lang;
    }
    header("Location: " . SITE_URL . $url);
    exit();
}

function displayMessage() {
    if(isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if(isset($_SESSION['error'])) {
        echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
}

function getUserDetails($user_id) {
    global $conn;
    $query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function formatDate($date, $format = 'd M, Y') {
    return date($format, strtotime($date));
}

function formatTime($time, $format = 'h:i A') {
    return date($format, strtotime($time));
}

function getAvailableSeats($route_id, $journey_date) {
    global $conn;
    $route_query = "SELECT total_seats FROM routes WHERE id = $route_id";
    $route_result = mysqli_query($conn, $route_query);
    $route = mysqli_fetch_assoc($route_result);
    $total_seats = $route['total_seats'] ?? 40;
    
    $booked_query = "SELECT seat_numbers FROM bookings WHERE route_id = $route_id AND journey_date = '$journey_date' AND status = 'confirmed'";
    $booked_result = mysqli_query($conn, $booked_query);
    $booked_seats = [];
    while($row = mysqli_fetch_assoc($booked_result)) {
        $seats = explode(',', $row['seat_numbers']);
        $booked_seats = array_merge($booked_seats, $seats);
    }
    
    return [
        'total' => $total_seats,
        'booked' => $booked_seats,
        'available' => $total_seats - count($booked_seats)
    ];
}
?>