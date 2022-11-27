<?php

require "_work_areas.php";

$raw = "
WITH work_todos AS (
	SELECT
		*
	FROM
		TMTask t
		JOIN TMArea a ON t.area = a.uuid
	WHERE
		trashed = 0
		AND status not in (2, 3)
		AND start not in (2)
		AND a.uuid not in %s
),
active_tasks AS (
	SELECT
		*
	FROM
		TMTask t
	WHERE
		trashed = 0
		AND status not in (2, 3)
		AND start not in (2)
		AND start <> 2
)
SELECT
	count(*) AS count_work_tasks
FROM
	active_tasks a
	JOIN work_todos w ON a.project = w.uuid
ORDER BY
	count(*)
	DESC;
";

$totalPrivateQuery = sprintf($raw, $work_areas);
