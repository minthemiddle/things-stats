<?php

require "_work_areas.php";

$raw="
WITH work_todos AS (
    SELECT
        *
    FROM
        TMTask t
        JOIN TMArea a ON t.area = a.uuid
    WHERE
        trashed = 0
        AND status not in (2, 3)
        AND a.uuid in %s
),
active_tasks AS (
    SELECT
        *
    FROM
        TMTask t
    WHERE
        trashed = 0
        AND status not in (2, 3)
        AND start <> 2
)
SELECT
    a.title,
    a.status,
    a.uuid
FROM
    active_tasks a
    JOIN work_todos w ON a.project = w.uuid
WHERE a.project = 'R2eYB3aXVLNs9N8zaJiacb'
limit 2
;
";

$debugQuery = sprintf($raw, $work_areas);
