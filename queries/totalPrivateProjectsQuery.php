<?php

$totalPrivateProjectsQuery = "
WITH projects AS (
	SELECT
		uuid,
		title,
		CASE WHEN area in('Euumv3Pyzpv4QXbBZKmn7n',
		'3wdSmtRBdoeCMrLSF2WKvr', 'ShFzcuAiwoj57Ts7BHkHi7') THEN
			'work'
		ELSE
			'private'
		END AS TYPE
	FROM
		TMTask t
	WHERE
		trashed = 0
		AND status <> 3
		AND area NOT NULL
),
tasks AS (
	SELECT
		*
	FROM
		TMTask
	WHERE
		trashed = 0
		AND status <> 3
)
SELECT
	p.title,
	count(*) as open_tasks
FROM
	tasks t
	JOIN projects p ON t.project = p.uuid
where p.type = 'private'
group by p.title
order by count(*) desc;
";