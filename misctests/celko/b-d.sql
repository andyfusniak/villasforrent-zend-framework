SET @newpos = 12;
SET @width  = 8;
SET @distance = 10;

SET @tmppos = 2;
SET @oldrpos = 9;

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
