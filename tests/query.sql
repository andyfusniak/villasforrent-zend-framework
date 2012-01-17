SELECT ancestor.idLocation, ancestor.url, ancestor.rowname
FROM Locations child, Locations ancestor
WHERE child.lt BETWEEN ancestor.lt AND ancestor.rt
AND child.idLocation = 274
AND ancestor.url != '/'
GROUP BY ancestor.idLocation

