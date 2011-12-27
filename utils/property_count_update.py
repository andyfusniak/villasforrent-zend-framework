#!/usr/bin/env python
import sys
import MySQLdb
import MySQLdb.cursors

import config

from hpw.models import location, property

def main():
    try:
        conn = MySQLdb.connect (
            host   = config.dbhost,
            user   = config.dbuser,
            passwd = config.dbpass,
            db     = config.dbname,
            cursorclass = MySQLdb.cursors.DictCursor
        )
        
        cursor = conn.cursor()
    except MySQLdb.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])
        sys.exit(1)

    # for each location in the Locations table
    rows = location.get_all_nodes(cursor)
    for row in rows:    
        cnt_vis     = property.count_by_loc_name(cursor, row['url'], visible=True)
        cnt_nonvis  = property.count_by_loc_name(cursor, row['url'], visible=None)
        location.update_totals(cursor, row['idLocation'], cnt_nonvis, cnt_vis)
        
    sys.exit(0)
    
if __name__ == '__main__':
    main()