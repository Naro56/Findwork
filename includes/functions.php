<?php
/**
 * Common functions for the FindWork job portal
 */

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is a recruiter
function isRecruiter() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'recruiter';
}

// Function to sanitize output
function sanitize($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

// Function to format date
function formatDate($date) {
    return date('M d, Y', strtotime($date));
}
?>