MERGE INTO PropertiesContent AS target
USING 

WHEN MATCHED THEN
    SELECT src.content


SELECT DISTINCT t1.idPropertyContent, t2.content, t2.cs, t1.idProperty
FROM PropertiesContent AS t1 INNER JOIN PropertiesContent AS t2
WHERE t1.version = 1 AND t2.version = 2
AND t1.idProperty = 10500
AND t1.iso2char = 'EN'
AND t1.idProperty = t2.idProperty
AND t1.iso2char = t2.iso2char
AND t1.idPropertyContentField=t2.idPropertyContentField
AND t1.cs!=t2.cs
