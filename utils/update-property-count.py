#!/usr/bin/env python
import sys
import MySQLdb
import MySQLdb.cursors

import config
import logging

from hpw.models import location, property

logging.basicConfig(filename=config.applogs['update-property-count'],
                    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
                    level=logging.DEBUG)

def main():
    try:
        logging.info("Opening MySQL DB connection")
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

    # depth first traversal of the nested set
    rows = location.get_all_nodes(cursor)
    for row in rows:
        lt = row['lt']
        rt = row['rt']

        cnt_vis     = property.count_by_loc_name(cursor, row['url'], visible=True)
        cnt_nonvis  = property.count_by_loc_name(cursor, row['url'], visible=None)

        location.update_totals(cursor, row['idLocation'], cnt_nonvis, cnt_vis)

    cursor.close()
    conn.commit()
    conn.close()

    sys.exit(0)

if __name__ == '__main__':
    main()
