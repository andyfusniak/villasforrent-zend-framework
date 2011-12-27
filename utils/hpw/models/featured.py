import sys
import config
import logging

mask_homepage       = 0
mask_country        = 1
mask_region         = 2
mask_destination    = 3

### Featured Properties
def add_new_featured(cursor, id_prop, id_loc, st_d, ex_d, lmb, p='next'):
    if p == 'next':
        sql = """
        SELECT MAX(position) + 1 AS nxt
        FROM FeaturedProperties
        WHERE idLocation={id_loc} AND startDate='{st_d}'
        """.format(id_prop=id_prop, id_loc=id_loc,st_d=st_d)
        print sql
        cursor.execute(sql)
        logging.debug(sql)
        
        row = cursor.fetchone()
        p = row['nxt']
        print p
        if p == None:
            p = 1
    
    sql = """
    INSERT INTO FeaturedProperties (idFeaturedProperty, idProperty, idLocation, startDate, expiryDate, position, added, updated, lastModifiedBy) VALUES (null, {id_prop}, {id_loc}, '{st_d}', '{ex_d}', {p}, now(), now(), '{lmb}')
    """.format(id_prop=id_prop, id_loc=id_loc, st_d=st_d, ex_d=ex_d, p=p, lmb=lmb)
    print sql
    if not config.debug['sql']:
        print "inserting!"
        cursor.execute(sql)
    logging.debug(sql)
    
def purge_featured(cursor):
    sql = """
    DELETE FROM FeaturedProperties
    WHERE 1
    """
    
    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)
    