<?php

$thingsDB = new SQLite3(getenv('THINGS'));
$statsDB = new SQLite3('stats.sqlite');


$tasksCreatedTodayQuery = "
WITH tasks_with_creation AS (
	SELECT
		title,
		strftime ('%Y-%m-%d',
			datetime (creationDate,
				'unixepoch',
				'localtime')) AS creation_date
	FROM
		TMTask
)
SELECT
	count(*)
FROM
	tasks_with_creation
WHERE creation_date = date()
ORDER BY
	creation_date DESC;
";

$tasksCreatedToday = $thingsDB->querySingle($tasksCreatedTodayQuery);
echo $tasksCreatedToday;
// $enterToday = $statsDB->exec("insert into stats(date, created_private) VALUES(date(), $tasksCreatedToday)");