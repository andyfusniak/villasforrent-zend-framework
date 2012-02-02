#!/usr/bin/python2.6
import config
import datetime
import logging
import MySQLdb
import MySQLdb.cursors
import sys

from utils import parse_mysql_datetime
from model import get_all_tokens, delete_token

logging.basicConfig(filename=config.applogs['delete-expired-password-resets'],
                    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
                    level=logging.DEBUG)

def main():
    try:
        # connect to the MySQL DB
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

    
    ar_rows = get_all_advertisers_reset(cursor)
    
    logging.info("There are " + str(len(ar_rows)) + " entries in the AdvertisersReset table")
    
    # comparison point
    now = datetime.datetime.today()
    
    # for each token
    for row in ar_rows:
        # if this reset link has expired, remove it from the DB
        # to prevent security risks
        if now > row['expires']:
            delete_token(cursor, row['idToken'])
            
    sys.exit(0)
        
if __name__ == '__main__':
    main() 
