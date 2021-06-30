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
