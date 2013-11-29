#!/usr/bin/env python
import sys
import MySQLdb
import MySQLdb.cursors

import config
import logging

from hpw.models import location, property

logging.basicConfig(filename=config.applogs['check-locations'],
                    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
                    level=logging.DEBUG)

def main():
    try:
        # connect to the mysql db
        logging.info("Opening MySQL DB connection")
        conn = MySQLdb.connect (
            host   = config.dbhost,
            user   = config.dbuser,
            passwd = config.dbpass,
            db     = config.dbname,
            cursorclass = MySQLdb.cursors.DictCursor
        )
    except MySQLdb.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])
        sys.exit(1)

    cursor = conn.cursor()

    # show a list of top level properties
    #rows = location.get_locations_by_depth(cursor, depth=1)
    #for row in rows:
    #    print row['rowname']
    #    pass

    # show the number of properties in the database
    #num_props = location.get_all_leaf_nodes(cursor, mode='hasproperties', count=True)
    #print 'There are {num_props} properties in the database'.format(num_props=num_props)

    # get all properties
    #rows = location.get_all_leaf_nodes(cursor, mode='hasproperties')
    #for row in rows:
    #    print row['idProperty'], "\t", row['url'], row['lt'], row['rt']

    # depth first traversal of the nested set
    rows = location.get_all_nodes(cursor)
    for row in rows:
        lt = row['lt']
        rt = row['rt']

        cnt_vis     = property.count_by_loc_name(cursor, row['url'], visible=True)
        cnt_nonvis  = property.count_by_loc_name(cursor, row['url'], visible=None)

        print row['idLocation'], \
              "\t", cnt_nonvis, \
              "\t", cnt_vis, \
              "\t", row['url'], \
              "\t", row['lt'], \
              "\t", row['rt']
        location.update_totals(cursor, row['idLocation'], cnt_nonvis, cnt_vis)

    sys.exit(0)

if __name__ == '__main__':
    main()
