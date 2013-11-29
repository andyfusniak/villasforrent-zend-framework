#!/usr/bin/env python
import hashlib
import sys
import MySQLdb
import MySQLdb.cursors

import config
import logging

from hpw.models import property

#from model import *
import property_content

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
        cursor = conn.cursor()
    except MySQLdb.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])
        sys.exit(1)


    id_prop = sys.argv[1]

    rows_master = property.get_property_content_by_property_id(cursor, id_prop, 1)
    rows_update = property.get_property_content_by_property_id(cursor, id_prop, 2)


    master_total_cs = ''
    update_total_cs = ''
    for master_item in rows_master:
        id_prop_content_field = master_item['idPropertyContentField']

        # get the update row matching this one
        update_item = rows_update[id_prop_content_field-1]

        master_checksum = master_item['cs']
        update_checksum = update_item['cs']

        master_total_cs += master_checksum
        update_total_cs += update_checksum

        if master_checksum == None:
            master_checksum = 'null'

        if update_checksum == None:
            update_checksum = 'null'

        print str(id_prop_content_field).ljust(4), property_content.lookup[id_prop_content_field-1].ljust(18), master_checksum[:6].ljust(8), update_checksum[:6].ljust(8), 'Match' if master_checksum == update_checksum else 'Changed'

    print "Property Master Checksum should be", hashlib.sha1(master_total_cs).hexdigest()
    print "Property Master Checksum should be", hashlib.sha1(update_total_cs).hexdigest()

    cursor.close()
    conn.commit()
    conn.close()

if __name__ == '__main__':
    main()
