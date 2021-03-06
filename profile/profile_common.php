<?php
// Common code for all profile/*.php actions.

// Start the session.
session_start();

// Get the data passed in, and do basic verification.
$username = '';

// Get the list of fields to use, excluding the id.
$fields = array('username', 'pictureLink', 'paragraph',
                'zipCode', 'distanceMax',
                'ourAgeMin', 'ourAgeMax', 'theirAgeMin', 'theirAgeMax');

$booleanFields = array('boardGames', 'cardGames', 'cooking', 'conversation',
    'artGalleries', 'comedy', 'classicalConcerts', 'popularConcerts',
    'ballroomDancing', 'countryDancing', 'salsaDancing', 'casualDining', 'fineDining',
    'karaoke', 'liveTheater', 'movies', 'wineTasting',
    'bicycling', 'bowling', 'golf', 'hiking', 'horsebackRiding', 'kayaking',
    'motorcycling', 'racquetball', 'tennis', 'walking',
    'camping', 'rving', 'domesticTravel', 'foreignTravel');

// Get a sanitized version of a string parameter.
function get_sanitized_string($s) {
    return filter_var($s, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_HIGH);
}

// Get a sanitized version of the username passed in.
function get_sanitized_username() {
    if (isset($_POST['username']) && $_POST['username']) {
        return filter_var($_POST['username'], FILTER_SANITIZE_STRING,
            FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_HIGH);
    }
    else {
        return "";
    }
}

// Check whether a boolean string is "true" or "1".
function check_boolean_string($s) {
    return $s == "true" || $s == "1";
}
// Convert a profile from the database so it returns numbers and booleans correctly.
function convert_profile_to_client($profile) {
    // Convert the numeric values from "123" to 123.
    $profile['profileId'] = intval($profile['id']);
    $profile['zipCode'] = intval($profile['zipCode']);
    $profile['distanceMax'] = intval($profile['distanceMax']);
    $profile['ourAgeMin'] = intval($profile['ourAgeMin']);
    $profile['ourAgeMax'] = intval($profile['ourAgeMax']);
    $profile['theirAgeMin'] = intval($profile['theirAgeMin']);
    $profile['theirAgeMax'] = intval($profile['theirAgeMax']);

    // Convert the booleans from "0" or "1" to a boolean.
    global $booleanFields;
    foreach ($booleanFields as $field) {
        $profile[$field] = ($profile[$field] == "1");
    }

    return $profile;
}

// Convert a profile from the client so it can be entered into the database.
function convert_profile_from_client($profile) {
    // Convert string values to sanitized versions.
    $profile['username'] = get_sanitized_string($profile['username']);
    $profile['pictureLink'] = get_sanitized_string($profile['pictureLink']);
    $profile['paragraph'] = get_sanitized_string($profile['paragraph']);
    
    // Convert the numeric values to sanitized versions.
    $profile['profileId'] = get_sanitized_string((string)$profile['id']);
    $profile['zipCode'] = get_sanitized_string((string)$profile['zipCode']);
    $profile['distanceMax'] = get_sanitized_string((string)$profile['distanceMax']);
    $profile['ourAgeMin'] = get_sanitized_string((string)$profile['ourAgeMin']);
    $profile['ourAgeMax'] = get_sanitized_string((string)$profile['ourAgeMax']);
    $profile['theirAgeMin'] = get_sanitized_string((string)$profile['theirAgeMin']);
    $profile['theirAgeMax'] = get_sanitized_string((string)$profile['theirAgeMax']);

    // Convert the booleans from a boolean to "0" or "1".
    global $booleanFields;
    foreach ($booleanFields as $field) {
        $profile[$field] = check_boolean_string($profile[$field]) ? "1" : "0";
    }

    return $profile;
}