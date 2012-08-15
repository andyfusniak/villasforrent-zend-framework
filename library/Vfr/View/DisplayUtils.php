<?php
class Vfr_View_DisplayUtils
{
    const LEFT_TO_RIGHT = 1;
    const TOP_TO_BOTTOM = 2;

    /**
     * Converts a flat list of items and converts to a multidimensions 'numColumns'
     * array either left to right, or top to bottom based on the given mode
     *
     * @param array $list the list of items
     * @param int $numColumns the number of columns required
     * @param int either LEFT_TO_RIGHT or TOP_TO_BOTTOM display style
     *
     * @return array a multi-dimensional array of columns sorted according to mode
     */
    public static function listToColumns($list, $numColumns, $mode = self::LEFT_TO_RIGHT) {
        switch ($mode) {
            case self::LEFT_TO_RIGHT:
                return self::listToColumnsLeftToRight($list, $numColumns);
                break;

            case self::TOP_TO_BOTTOM:
                return self::listToColumnsTopToBottom($list, $numColumns);
                break;

            default:
                return self::listToColumnsLeftToRight($list, $numColumns);
        }
    }

    public static function listToColumnsLeftToRight($list, $numColumns)
    {
        sort($list);
        
        $numColumns = (int) $numColumns;
        $size = sizeof($list);

        // initialise empty arrays for each column
        for ($j=0; $j < $numColumns; $j++) {
            $columnItems[$j] = array();
        }

        // algorithm for left to right placement across the columns
        for ($i=0; $i < $size; $i++) {
            $row = $list[$i];

            // determine which list to add this item to
            $allocIdx = $i % $numColumns;

            array_push($columnItems[$allocIdx], $row);
        }

        return $columnItems;
    }

    // algorithm for top to bottom placement in across the columns
    public static function listToColumnsTopToBottom($list, $numColumns)
    {
        $numColumns = (int) $numColumns;
        $size = sizeof($list);

        // initialise empty arrays for each column
        for ($j=0; $j < $numColumns; $j++) {
            $columnItems[$j] = array();
        }

        // calculate the number per column using modulus remainder
        $numExtras = $size % $numColumns;
        for ($j=0; $j < $numColumns; $j++) {
            $numPerColumn[$j] = intval(floor($size / $numColumns));

            $column = $j + 1;
            if ($column <= $numExtras) {
                $numPerColumn[$j]++;
            }
        }

        $currentColumn = 0;
        $idx = 0;
        foreach ($list as $row) {
            array_push($columnItems[$currentColumn], $row);
            $idx++;

            // if this column is complete, move to the next column and reset the index
            if ($idx == $numPerColumn[$currentColumn]) {
                $currentColumn++;
                $idx = 0;
            }
        }

        return $columnItems;
    }
}
