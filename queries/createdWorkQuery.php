<?php

$createdWorkQuery = "WITH tasks_with_creation AS (
	SELECT
		t.title as title,
		p.title as project,
		p.uuid as project_uuid,
		strftime ('%Y-%m-%d',
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
		CASE WHEN area in('Euumv3Pyzpv4QXbBZKmn7n',
			'3wdSmtRBdoeCMrLSF2WKvr') THEN
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
)
SELECT
	count(case when ap.type = 'work' then uuid end) as created_work
FROM
	tasks_with_creation twc
JOIN active_projects ap on twc.project_uuid = ap.uuid
WHERE creation_date = date()
ORDER BY
	creation_date DESC;";