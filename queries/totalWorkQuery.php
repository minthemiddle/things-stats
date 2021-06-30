<?php

$totalWorkQuery="
WITH work_todos AS (
	SELECT
		*
	FROM
		TMTask t
		JOIN TMArea a ON t.area = a.uuid
	WHERE
		trashed = 0
		AND status <> 3
		AND a.uuid in('Euumv3Pyzpv4QXbBZKmn7n',
			'3wdSmtRBdoeCMrLSF2WKvr')
),
active_tasks AS (
	SELECT
		*
	FROM
		TMTask t
	WHERE
		trashed = 0
		AND status <> 3
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