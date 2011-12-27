#!/usr/bin/env python
import sys
import MySQLdb
import MySQLdb.cursors

import config
import logging
import datetime

from hpw.models import location, featured

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

    #idLocation = location.get_id_loc_by_url(cursor, '/')
    #print idLocation
    #sys.exit(0)

    # get all currently featured properties
    # since this is throw away code, it's not put in the hpw models library code
    sql = """
    SELECT idProperty, locationUrl, featureMask, featureExpiry, featurePriority
    FROM Properties
    WHERE featureMask > 0 AND locationUrl != 'default/default/default'
    """
    cursor.execute(sql)
    
    rows = cursor.fetchall()
    
    # setup two dates to start and end the featured properties
    st_d = datetime.date(2011, 12, 26)
    ex_d = datetime.date(2012, 3, 31)
    
    for row in rows:
        idProperty  = row['idProperty']
        locationUrl = row['locationUrl']
        featureMask = row['featureMask']
        parts = locationUrl.split('/')
        numParts = len(parts)
        
        # home feature check
        if featureMask >> featured.mask_homepage & 0x01 == 1:
            #pass
            print str(row['idProperty']) + ' ' + row['locationUrl'] + ' is featured on homepage '
            idLocation = location.get_id_loc_by_url(cursor, '/')
            featured.add_new_featured(cursor, idProperty, idLocation, st_d, ex_d, 'system', p='next')
            
        for i in range(0, numParts):
            if (i == 0):    # country
                if featureMask >> featured.mask_country & 0x01 == 1:
                    #pass
                    print str(row['idProperty']) + ' ' + row['locationUrl'] + ' is featured in the country ' + parts[0]
                    idLocation = location.get_id_loc_by_url(cursor, parts[0])
                    featured.add_new_featured(cursor, idProperty, idLocation, st_d, ex_d, 'system', p='next')
            elif (i == 1):  # region
                if featureMask >> featured.mask_region & 0x01 == 1:
                    #pass
                    print str(row['idProperty']) + ' ' + row['locationUrl'] + ' is featured in the region ' + parts[0] + '/' + parts[1]
                    idLocation = location.get_id_loc_by_url(cursor, parts[0] + '/' + parts[1])
                    featured.add_new_featured(cursor, idProperty, idLocation, st_d, ex_d, 'system', p='next')
            elif (i == 2):  # destination
                if featureMask >> featured.mask_destination & 0x01 == 1:
                    #pass
                    print str(row['idProperty']) + ' ' + row['locationUrl'] + ' is featured in the destination ' + parts[0] + '/' + parts[1] + '/' + parts[2]
                    idLocation = location.get_id_loc_by_url(cursor, parts[0] + '/' + parts[1] + '/' + parts[2])
                    featured.add_new_featured(cursor, idProperty, idLocation, st_d, ex_d, 'system', p='next')
    
    # close the DB, commit and close the connection
    cursor.close()
    conn.commit()
    conn.close()

if __name__ == '__main__':
    main()