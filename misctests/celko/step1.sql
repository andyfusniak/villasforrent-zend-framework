SET @newpos = 12;
SET @width  = 8;

UPDATE Tree
SET lft = lft + @width
WHERE lft >= @newpos;

UPDATE Tree
SET rgt = rgt + @width
WHERE rgt >= @newpos;
