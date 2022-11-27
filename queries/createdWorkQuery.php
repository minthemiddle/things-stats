<?php

require "_date_for_tasks.php";
require "_work_areas.php";

$raw = "WITH tasks_with_creation AS (
	SELECT
		t.title as title,
		p.title as project,
		p.uuid as project_uuid,
		strftime ('%%Y-%%m-%%d',
			datetime (t.creationDate,
				'unixepoch',
				'localtime')) AS creation_date
	FROM
		TMTask t
	JOIN TMTask p on t.project = p.uuid
), active_projects AS (
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
		AND start not in (2)
		AND area NOT NULL
)
SELECT
	count(case when ap.type = 'work' then uuid end) as created_work
FROM
	tasks_with_creation twc
JOIN active_projects ap on twc.project_uuid = ap.uuid
WHERE creation_date = '%s'
ORDER BY
	creation_date DESC;";

$createdWorkQuery = sprintf($raw, $work_areas, $date_for_tasks);
