<?php

$thingsDB = new SQLite3(getenv('THINGS'));
$statsDB = new SQLite3('stats.sqlite');

$help = "Use log, show, clear, work, private";

// Find command
if (! isset($argv[1])) {
    echo $help;
    die();
}

foreach (glob("queries/*.php") as $filename) {
    include $filename;
}

// today
$today = $date_for_tasks;

// Query results
$debugResult = $thingsDB->query($debugQuery);
$createdPrivate = $thingsDB->querySingle($createdPrivateQuery);
$donePrivate = $thingsDB->querySingle($donePrivateQuery);
$totalPrivate = $thingsDB->querySingle($totalPrivateQuery);
$createdWork = $thingsDB->querySingle($createdWorkQuery);
$doneWork = $thingsDB->querySingle($doneWorkQuery);
$totalWork = $thingsDB->querySingle($totalWorkQuery);
$totalPrivateProjects = $thingsDB->query($totalPrivateProjectsQuery);
$totalWorkProjects = $thingsDB->query($totalWorkProjectsQuery);
$activeWorkProjects = $thingsDB->query($activeWorkProjectsQuery);
$activePrivateProjects = $thingsDB->query($activePrivateProjectsQuery);

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
} elseif ($argv[1] == 'log') {
    // Enter results in stats db
    $statsDB->exec(
        "
		insert into stats(date, created_private, done_private, total_private, created_work, done_work, total_work) 
		VALUES('$today', $createdPrivate, $donePrivate, $totalPrivate, $createdWork, $doneWork, $totalWork)
	"
    );
} elseif ($argv[1] == 'clear') {
    $clearQueryRaw = "DELETE FROM 'stats' WHERE date = '%s';";
    $statsDB->exec(sprintf($clearQueryRaw, $date_for_tasks));
} elseif ($argv[1] == 'private') {
    while ($row = $totalPrivateProjects->fetchArray()) {
        echo $row[0] . ': ' . $row[1] . "\r\n";
    }
} elseif ($argv[1] == 'work') {
    while ($row = $totalWorkProjects->fetchArray()) {
        echo $row[0] . ': ' . $row[1] . "\r\n";
    }
} elseif ($argv[1] == 'work-review') {
    $i = 0;
    $current_week_number = idate('W', time());

    $data = array();
    $data['type'] = 'project';
    $data['attributes']['title'] = '🎥 Review Work - Week ' . $current_week_number;
    $data['attributes']['area-id'] = '9nNDw4EjbzdPhQkKshBeAZ';


    while ($row = $activeWorkProjects->fetchArray()) {
        $data['attributes']['items'][$i]['type'] = 'to-do';
        $data['attributes']['items'][$i]['attributes']['title'] = $row['title'];
        $data['attributes']['items'][$i]['attributes']['notes'] = "[Link](things:///show?id=" . $row['uuid'] . ")";
        $i++;
    }

    $things_json = json_encode($data);
    $things_command = 'open \'things:///json?data=[' . $things_json . ']\'';

    shell_exec($things_command);
} elseif ($argv[1] == 'private-review') {
    $i = 0;
    $current_week_number = idate('W', time());

    $data = array();
    $data['type'] = 'project';
    $data['attributes']['title'] = '🎥 Review Private - Week ' . $current_week_number;
    $data['attributes']['area-id'] = 'SH2dsLxMjsnK99z1haAXqH';


    while ($row = $activePrivateProjects->fetchArray()) {
        $data['attributes']['items'][$i]['type'] = 'to-do';
        $data['attributes']['items'][$i]['attributes']['title'] = $row['title'];
        $data['attributes']['items'][$i]['attributes']['notes'] = "[Link](things:///show?id=" . $row['uuid'] . ")";
        $i++;
    }

    $things_json = json_encode($data);
    $things_command = 'open \'things:///json?data=[' . $things_json . ']\'';

    shell_exec($things_command);
} elseif ($argv[1] == 'debug') {
    $i = 0;
} else {
    echo $help;
}
