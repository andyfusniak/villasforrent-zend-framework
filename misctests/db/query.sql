UPDATE PropertiesContent AS c
JOIN
    (
    SELECT
        DISTINCT t1.idPropertyContent, t1.idProperty, t2.content, t2.cs
    FROM
        PropertiesContent AS t1
    INNER JOIN PropertiesContent AS t2 ON
        t1.idPropertyContentField=t2.idPropertyContentField
        AND t1.idProperty=t2.idProperty
        AND t1.cs!=t2.cs
        AND t1.iso2char=t2.iso2char
    WHERE
        t1.version=1 AND t1.idProperty=10519 AND t1.iso2char='EN'
    )
AS tm
ON
    tm.idPropertyContent=c.idPropertyContent
SET
    c.content=tm.content, c.cs=tm.cs, c.updated=now();

