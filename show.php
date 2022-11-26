<?php

$thingsDB = new SQLite3("/Users/martinbetz/Library/Group Containers/JLMPQHK86H.com.culturedcode.ThingsMac/Things Database.thingsdatabase/main.sqlite");

foreach (glob("queries/*.php") as $filename) {
    include $filename;
}

foreach (glob("components/*.php") as $component) {
    include $component;
}

$all_tasks = $thingsDB->query($totalTasksQuery);

echo $header;

while ($row = $all_tasks->fetchArray()) {
        echo '<tr><td>' . $row[1] . '</td><td>' . $row[0] . '</td></tr>';
}

echo $footer;
