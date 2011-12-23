#!/usr/bin/env python
import hashlib
import sys
import MySQLdb
import MySQLdb.cursors

import config
import logging

from model import *
import property_content


def repair_property_content(cursor, id_prop, version):
    rows = get_property_content_by_property_id(cursor, id_prop, version)
    
    cs_sum = ''
    for row in rows:
        content = row['content']
        if content == None:
            content = ''
            
        checksum = hashlib.sha1(content).hexdigest()
        update_property_content_by_pk(cursor, row['idPropertyContent'], checksum)
        cs_sum += checksum 
    
    return hashlib.sha1(cs_sum).hexdigest()
    
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
    
    try:
        id_prop = sys.argv[1]
    
        # repair all checksums for main and update versions 
        m_cs = repair_property_content(cursor, id_prop, 1) 
        u_cs = repair_property_content(cursor, id_prop, 2) 
    
        # repair the master property table checksum totals
        update_property_checksums(cursor, id_prop, m_cs, u_cs)

    except IndexError, e:
        cursor.execute("SELECT idProperty FROM Properties")
        rowall = cursor.fetchall()
                
        for proprow in rowall:
            id_prop = proprow['idProperty']
            # repair all checksums for main and update versions 
            m_cs = repair_property_content(cursor, id_prop, 1) 
            u_cs = repair_property_content(cursor, id_prop, 2) 
    
            # repair the master property table checksum totals
            update_property_checksums(cursor, id_prop, m_cs, u_cs)

            print "Reparing... " + str(id_prop)

    
    # close the DB, commit and close the connection
    cursor.close()
    conn.commit()
    conn.close()

if __name__ == '__main__':
    main()

