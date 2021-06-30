<?php

$thingsDB = new SQLite3(getenv('THINGS'));
$statsDB = new SQLite3('stats.sqlite');

foreach (glob("queries/*.php") as $filename)
{
    include $filename;
}

// today
$today = date('Y-m-d');

$tasksCreatedToday = $thingsDB->querySingle($tasksCreatedTodayQuery);
echo $tasksCreatedToday;
// $enterToday = $statsDB->exec("insert into stats(date, created_private) VALUES(date(), $tasksCreatedToday)");