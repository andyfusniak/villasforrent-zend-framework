#!/usr/bin/env python
import sys
import MySQLdb
import MySQLdb.cursors

import config
import logging

# get the HPW DB functions
from model import *
from utils import *

#from exceptions import PropertyOutOfRange

logging.basicConfig(filename=config.applogs['remove_unapproved_property'],
                    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
                    level=logging.DEBUG)

# create formatter
#formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')

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
   
    try: 
        id_property = sys.argv[1]
    except IndexError:
        #IndexError: list index out of range
        print 'Syntax: ' + os.path.basename(sys.argv[0]) + ' <prop_id>'
        sys.exit(1)

    # make sure we're dealing with an unapproved property
    try :
        if is_property_approved(cursor, id_property):
            print "This property is already approved!"
            sys.exit(1)
    except PropertyNotFound:
        print "Couldn't find any data for this property"
        sys.exit(1)
    
    
    # locate the calendar for this property.  If it doesn't
    # exist then a CalendarNotFound exception will be raised
    # and we wont attempt to remove the rates and availability
    try:
        id_cal = get_calendar_id_by_property_id(cursor, id_property)
        delete_rates_by_calendar_id(cursor, id_cal)
        delete_avail_by_calendar_id(cursor, id_cal)
        delete_calendar_by_property_id(cursor, id_property)
        
    except CalendarNotFound:
        logging.info("Calendar not found for property " + str(id_property) + " so skipping removal of rates and availability, since these cannot exist without a calendar entry")
    
    # delete all content associated to this property
    delete_property_content_by_property_id(cursor, id_property)

    # delete all facilities tickboxes associated to this property    
    delete_property_facilities_by_property_id(cursor, id_property)
    
    # get a list of photos to delete
    photo_list = get_all_photo_ids_by_property_id(cursor, id_property)
    
    # delete the photos from the filesystem, originals and cache
    delete_filesystem_photos(id_property, photo_list)
    
    # delete the photos from the DB
    delete_photos_by_property_id(cursor, id_property)
    
    # finally delete the property from the Properties table
    delete_property_by_pk(cursor, id_property)
    
    #print property_generate_top_dir(10515)
    
    # close the cursor, commit the transactions and
    # close the MySQL connection
    cursor.close()
    conn.commit()
    conn.close()
    sys.exit(0)
    
if __name__ == '__main__':
    main()
    
