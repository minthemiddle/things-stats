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
    t.uuid
FROM
    tasks t
    JOIN projects p ON t.project = p.uuid
where p.type = 'work'
group by p.title
order by count(*) desc;
";

$activeWorkProjectsQuery = sprintf($raw, $work_areas);
