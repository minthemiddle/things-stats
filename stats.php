<?php

$thingsDB = new SQLite3(getenv('THINGS'));
$statsDB = new SQLite3('stats.sqlite');

foreach (glob("queries/*.php") as $filename)
{
    include $filename;
}

// today
$today = date('Y-m-d');

// Query results
$createdPrivate = $thingsDB->querySingle($createdPrivateQuery);
$donePrivate = $thingsDB->querySingle($donePrivateQuery);
$totalPrivate = $thingsDB->querySingle($totalPrivateQuery);
$createdWork = $thingsDB->querySingle($createdWorkQuery);
$doneWork = $thingsDB->querySingle($doneWorkQuery);
$totalWork = $thingsDB->querySingle($totalWorkQuery);


// Find command
if (! isset($argv[1])) {
    echo "Use log or show argument";
    die();
}

if ($argv[1] == 'show') {
    // Show results
	echo "Today: " . $today . "\r\n" .
	"Created Private: " . $createdPrivate . "\r\n" . 
	"Done Private: " . $donePrivate . "\r\n" . 
	"Total Private: " . $totalPrivate . "\r\n" .
	"Created Work: " . $createdWork . "\r\n" . 
	"Done Work: " . $doneWork . "\r\n" .
	"Total Work: " . $totalWork . "\r\n" . 
	"Total: " . $totalPrivate + $totalWork . "\r\n";
}

elseif ($argv[1] == 'log') {
	// Enter results in stats db
    $statsDB->exec("
		insert into stats(date, created_private, done_private, total_private, created_work, done_work, total_work) 
		VALUES('$today', $createdPrivate, $donePrivate, $totalPrivate, $createdWork, $doneWork, $totalWork)
	");
}

else {
    echo "Use log or show";
}
