CREATE TEMPORARY TABLE tm_master
SELECT idProperty, sha1(group_concat(cs)) AS cs FROM PropertiesContent WHERE version=1 GROUP BY idProperty ORDER BY idPropertyContentField ASC;

CREATE TEMPORARY TABLE tm_update
SELECT idProperty, sha1(group_concat(cs)) AS cs FROM PropertiesContent WHERE version=2 GROUP BY idProperty ORDER BY idPropertyContentField ASC;


#SELECT * FROM tm_master;
#SELECT * FROM tm_update;

UPDATE Properties AS p
JOIN tm_master AS tm
ON
    tm.idProperty=p.idProperty
SET
    p.checksumMaster=tm.cs,
    p.updated=now(),
    p.lastModifiedBy='sql';


UPDATE Properties AS p
JOIN tm_update AS tm
ON
    tm.idProperty=p.idProperty
SET
    p.checksumUpdate=tm.cs,
    p.updated=now(),
    p.lastModifiedBy='sql';



UPDATE Properties
SET checksumMaster = (
SELECT sha1(group_concat(cs)) FROM PropertiesContent WHERE version=1 AND idProperty = 10517 ORDER BY idPropertyContentField ASC
)
WHERE idProperty = 10517;

