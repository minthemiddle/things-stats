<?php

$thingsDB = new SQLite3(getenv('THINGS'));
$statsDB = new SQLite3('stats.sqlite');

// Find command
if (! isset($argv[1])) {
    echo "Use log, show or clear";
    die();
}

foreach (glob("queries/*.php") as $filename)
{
    include $filename;
}

// today
$today = $date_for_tasks;

// Query results
$createdPrivate = $thingsDB->querySingle($createdPrivateQuery);
$donePrivate = $thingsDB->querySingle($donePrivateQuery);
$totalPrivate = $thingsDB->querySingle($totalPrivateQuery);
$createdWork = $thingsDB->querySingle($createdWorkQuery);
$doneWork = $thingsDB->querySingle($doneWorkQuery);
$totalWork = $thingsDB->querySingle($totalWorkQuery);
$totalPrivateProjects = $thingsDB->query($totalPrivateProjectsQuery);

if ($argv[1] == 'show') {
    // Show results
	echo "Day: " . $today . "\r\n" .
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

elseif ($argv[1] == 'clear') {
	$clearQueryRaw = "DELETE FROM 'stats' WHERE date = '%s';";
	$statsDB->exec(sprintf($clearQueryRaw, $date_for_tasks));
}

elseif ($argv[1] == 'list') {

	while ($row = $totalPrivateProjects->fetchArray()) {
		echo $row[0] . ': ' . $row[1] . "\r\n";
	}
}

else {
    echo "Use log, show, list or clear";
}
