<?php

$boolBdaysToJson = false;
$bdays = [
    ['for' => 'John Smith', 'birthdate' => '1988-01-01', 'group' => 'sample'],
];

$fileName = 'bdays.json';
$fileDir = __DIR__;
$filePath = "$fileDir/$fileName";
$filePathLocal = "$fileDir/.local/$fileName";

if (file_exists($filePathLocal)) {
    echo "JSON file: " . $filePathLocal . "\n";
    $jsonData = file_get_contents($filePathLocal);
    $bdays = json_decode($jsonData, true);
} elseif(file_exists($filePath)) {
    echo "JSON file: " . $filePath . "\n";
    $jsonData = file_get_contents($filePath);
    $bdays = json_decode($jsonData, true);
} else {}

if($boolBdaysToJson && count($bdays) > 0) {
    $jsonData = json_encode($bdays, JSON_PRETTY_PRINT);
    file_put_contents($filePath, $jsonData);
}

function daysUntilBirthday($birthday) {
    // Set the timezone to Asia/Manila
    $timezone = new DateTimeZone('Asia/Manila');

    // Get the current date with the correct timezone and set the time to 00:00:00
    $currentDate = new DateTime('now', $timezone);
    $currentDate->setTime(0, 0, 0);

    // Convert the provided birthday to a DateTime object and set the time to 00:00:00
    $birthdayDate = new DateTime($birthday . ' 00:00:00', $timezone);

    // Set the birthday date to the current year
    $birthdayDate->setDate($currentDate->format('Y'), $birthdayDate->format('m'), $birthdayDate->format('d'));

    // Check if the birthday has already passed or is today
    if ($birthdayDate < $currentDate) {
        // If the birthday has passed, calculate for the next year
        $birthdayDate->modify('+1 year');
    }

    // Calculate the difference in days
    $interval = $currentDate->diff($birthdayDate);
    $daysLeft = $interval->days;

    $text = '';
    // Return a human-friendly message
    if ($daysLeft == 0) {
        $text = "Your birthday is today!";
    } elseif ($daysLeft == 1) {
        $text = "Your birthday is tomorrow!";
    } elseif ($daysLeft == 364 || $daysLeft == 365) { // Adjusting for a leap year
        $text = "Your birthday was yesterday!";
    } else {
        $text = "Days left until your next birthday: " . $daysLeft;
    }

    return [
        'text' => $text,
        'days_left' => $daysLeft
    ];
}

$currentDate = date('Y-m-d');
$group = null;
// Output the result
foreach ($bdays as $item) {
    if($group != $item['group']) {
        echo "\n";
        $group = $item['group'];
    }
    $birthday = $item['birthdate'];
    $date_start = $birthday;
    $date_end = $currentDate;

	$date1 = new DateTime($date_start);
	$date2 = new DateTime($date_end);

	$interval = $date1->diff($date2);
    $daysUntilBirthday = daysUntilBirthday($birthday);
	// echo "For " . $item['for'] . ', ' . $interval->format('%y years, %m months, %d days')."\n";
	echo $item['for'] . ', ' . $interval->format('%y years old') . '. ';

    if($daysUntilBirthday['days_left'] <= 31) {
        echo $daysUntilBirthday['text'];
    }
    echo "\n";
}
?>
