SELECT parent.lt, parent.rt, parent.url, parent.rowname, parent.idLocation
FROM Locations child, Locations parent
WHERE child.lt > parent.lt AND
      child.lt < parent.rt
AND child.idLocation = 274
ORDER BY parent.lt DESC
LIMIT 1
