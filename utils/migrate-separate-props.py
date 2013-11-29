#!/usr/bin/env python
import sys
import MySQLdb
import MySQLdb.cursors
import config
import logging

from hpw.models import location

def main():
    try:
        # connect to the MySQL DB
        conn = MySQLdb.connect(
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


    rows = location.get_all_non_prop_nodes(cursor)
    counter = 1
    for row in rows:
        print row['url'],
        counter+= 1

    # close the DB, commit and close the connection
    cursor.close()
    conn.commit()
    conn.close()

if __name__ == '__main__':
    main()
