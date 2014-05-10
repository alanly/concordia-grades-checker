<?php

/**
 * This value references the page to the student record transcript. This
 * is the source document of the "Student Record" frame in the MyConcordia
 * portal.
 *
 * @var string
 */
$recordUrl = 'https://genesis.concordia.ca/Transcript/PortalStudentRecord.aspx?token=PERSISTENT_TOKEN&language=ENG&dbname=SIS_CRS&liveinfo=false';

/**
 * This value should contain the email address where new grade
 * notifications will be sent.
 *
 * @var string 
 */
$notifEmail = 'user@email.address';

// Fetch
if (true) {
    $ch = curl_init($recordUrl);
    $fp = fopen('response.html', 'w');

    curl_setopt_array($ch, [
        CURLOPT_FILE   => $fp,
        CURLOPT_HEADER => false,
    ]);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

// Parse

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTMLFile('response.html', LIBXML_NOWARNING);

$table = $dom->getElementsByTagName('table')->item(0);

$gradesRow = $table->childNodes->item(4);
$gradesTable = $gradesRow->firstChild->firstChild;

$courses = [];

foreach ($gradesTable->childNodes as $row) {
    if ($row->childNodes->length == 13) {
        $data = $row->childNodes;
        $course = new stdClass;

        $course->code          = $data->item(0)->nodeValue . ' ' . $data->item(1)->nodeValue;
        $course->semester      = $data->item(2)->nodeValue;
        $course->section       = $data->item(3)->nodeValue;
        $course->name          = $data->item(4)->nodeValue;
        $course->credits       = $data->item(5)->nodeValue;
        $course->grade         = $data->item(6)->nodeValue;
        $course->gpaEarned     = $data->item(7)->nodeValue;
        $course->classAvg      = $data->item(8)->nodeValue;
        $course->classSize     = $data->item(9)->nodeValue;
        $course->creditsEarned = $data->item(10)->nodeValue;

        $courses[] = $course;
    }
}

echo "Course Code\t" . str_pad('Description', 27) . "\tGrade\n\n";

foreach ($courses as $course) {
    echo $course->code . "\t" . str_pad($course->name, 27) . "\t" . $course->grade .  "\n";
}

echo "\nNumber of grades received: ";

$gradeCount = 0;
$gradeMissing = 0;

foreach ($courses as $course) {
    if ($course->grade !== '') {
        ++$gradeCount;
    } else {
        ++$gradeMissing;
    }
}

echo $gradeCount . "\n";

echo "Number of grades missing: " . $gradeMissing . "\n\n";

$lastCount = 0;

if (file_exists('gradecount.txt')) {
    $lastCount = intval(file_get_contents('gradecount.txt'));
}

file_put_contents('gradecount.txt', $gradeCount);

if ($gradeCount > $lastCount) {
    echo "New grades available!\n\n";
    mail($notifEmail, 'New Grades!', 'This is a notification that there are new grades.');
} else {
    echo "No new grades to report.\n\n";
}
