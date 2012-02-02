SET @width  = 8;
SET @oldrpos = 9;

UPDATE Tree
SET lft = lft - @width
WHERE lft > @oldrpos;

UPDATE Tree
SET rgt = rgt - @width
WHERE rgt > @oldrpos;
