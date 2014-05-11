<?php

require "vendor/autoload.php";

use MyConcordiaApi\Portal;

/**
 * --------------------
 * Begin Configuration
 * --------------------
 */

// Your username to the MyConcordia portal.
$netname   = "your_portal_netname";

// Your password to the MyConcordia portal.
$password  = "your_portal_password";

// The email address to email notifications to.
$email     = "your_email_address";

// Path to a counter file which can keep track of how many grades you have.
$countPath = __DIR__."/gradesCounter.txt";

// Set to `true` in order to receive email notifications or `false` otherwise.
$notify    = true;

// Set to `true` in order to get a listing of courses and grades on STDOUT or `false` otherwise.
$verbose   = false;

/**
 * ------------------
 * End Configuration
 * ------------------
 */

if (count($argv) == 4) {
    $netname  = $argv[1];
    $password = $argv[2];
    $email    = $argv[3];
}

$portal = new Portal($netname, $password);;
$courses = $portal->getTranscriptCourses();

$currentNumberOfGrades = 0;
$lastNumberOfGrades = 0;

if (file_exists($countPath)) {
    $lastNumberOfGrades = intval(file_get_contents($countPath));
}

foreach ($courses as $c) {
    if ($c->grade !== "") {
        ++$currentNumberOfGrades;
    }

    if ($verbose === true) {
        echo $c->code . "\t" . $c->grade . "\n";
    }
}

file_put_contents($countPath, $currentNumberOfGrades);

if ($currentNumberOfGrades > $lastNumberOfGrades) {
    echo "New grades are available!\n";

    if ($notify === true) {
        mail($email, "New grades are out!", "This is a notification that there are new grades out on MyConcordia, go get 'em!");
    }
}

exit();
