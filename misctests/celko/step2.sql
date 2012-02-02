SET @width  = 8;
SET @distance = 10;
SET @tmppos = 2;

UPDATE Tree
SET lft = lft + @distance,
    rgt = rgt + @distance
WHERE
    lft >= @tmppos AND
    rgt < @tmppos + @width;
