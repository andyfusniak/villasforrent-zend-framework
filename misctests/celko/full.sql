SET @newpos = 2;
SET @width  = 12;
SET @distance = -22;
SET @oldrpos = 23;
SET @tmppos = 24;

#LOCK Tree;
BEGIN;

UPDATE Tree
SET lft = lft + @width
WHERE lft >= @newpos;

UPDATE Tree
SET rgt = rgt + @width
WHERE rgt >= @newpos;

UPDATE Tree
SET lft = lft + @distance,
    rgt = rgt + @distance
WHERE
    lft >= @tmppos AND
    rgt < @tmppos + @width;

UPDATE Tree
SET lft = lft - @width
WHERE lft > @oldrpos;

UPDATE Tree
SET rgt = rgt - @width
WHERE rgt > @oldrpos;

COMMIT;
#UNLOCK Tree;
