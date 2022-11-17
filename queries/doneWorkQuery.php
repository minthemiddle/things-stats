<?php

require "_date_for_tasks.php";
require "_work_areas.php";

$raw = "
WITH completed_tasks_today AS (
	SELECT
		t.title,
		t.project,
		strftime ('%%Y-%%m-%%d',
			datetime (t.stopDate,
				'unixepoch',
				'localtime')) AS stop_date
	FROM
		TMTask t
		JOIN TMTask p ON t.project = p.uuid
),
active_projects AS (
	SELECT
		uuid,
		title,
		CASE WHEN area in %s THEN
			'work'
		ELSE
			'private'
		END AS TYPE
	FROM
		TMTask t
	WHERE
		trashed = 0
		AND status not in (2, 3)
		AND area NOT NULL
)
SELECT
	count(CASE WHEN type = 'work' THEN 1 END) as done_work
FROM
	completed_tasks_today ctt
	JOIN active_projects ap ON ctt.project = ap.uuid
WHERE
	stop_date = '%s';
";

$doneWorkQuery = sprintf($raw, $work_areas, $date_for_tasks);