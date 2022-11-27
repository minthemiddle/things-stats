<?php

require "_work_areas.php";

$raw = "
WITH projects AS (
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
),
tasks AS (
	SELECT
		*
	FROM
		TMTask
	WHERE
		trashed = 0
		AND status not in (2, 3)
		AND start not in (2)
)
SELECT
	p.title,
	t.title,
	p.type
FROM
	tasks t
	JOIN projects p ON t.project = p.uuid
order by 3, 1;
";

$totalTasksQuery = sprintf($raw, $work_areas);
