#!/usr/bin/env python
import sys
import MySQLdb
import MySQLdb.cursors

import config
import logging
import datetime

from hpw.models import property


def main():
    try:
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
        
    sql = """
    SELECT P.idProperty, L.idLocation
    FROM Properties AS P, Locations AS L
    WHERE L.url = P.locationUrl
    """
    cursor.execute(sql)
    
    rows = cursor.fetchall()
    
    for row in rows:
        print row['idProperty'], row['idLocation']
        property.update_property_location_id(cursor, row['idProperty'], row['idLocation'])
        
    # close the DB, commit and close the connection
    cursor.close()
    conn.commit()
    conn.close()

if __name__ == '__main__':
    main()