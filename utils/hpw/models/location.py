import sys
import logging
import config

def delete_location_by_property_id(cursor, id_property):
    # need to analyse the nested-set model before implementing
    return

def get_all_nodes(cursor):
    sql = """
    SELECT *
    FROM Locations
    ORDER BY lt ASC
    """
    
    cursor.execute(sql)
    logging.debug(sql)
    
    rows = cursor.fetchall()
    return rows

### DEPRECATED - properties are no longer stored on the Location objects
### check models/property.py
#def count_properties_by_loc_name(cursor, url, visible=None):
#    sql = """
#    SELECT COUNT(1) AS cnt
#    FROM Properties
#    WHERE locationUrl LIKE '{url}%'
#    """.format(url=url)
#    
#    if visible == True:
#        sql += ' AND visible=1'
#        
#    cursor.execute(sql)
#    logging.debug(sql)
#    
#    row = cursor.fetchone()
#    return row['cnt']

def get_id_loc_by_url(cursor, url):
    sql = """
    SELECT idLocation
    FROM Locations WHERE url = '{url}'
    """.format(url=url)
    
    cursor.execute(sql)
    logging.debug(sql)
    
    row = cursor.fetchone()
    
    if row == None:
        return None
    
    return row['idLocation']

def get_all_leaf_nodes(cursor, mode=None, count=None):
    
    if count != None:
        sql = """
        SELECT COUNT(1) AS cnt
        FROM Locations
        WHERE lt = (rt - 1)
        """
    else:
        sql = """
        SELECT *
        FROM Locations
        WHERE lt = (rt - 1)
        """
    
    if mode == 'noproperties':
        sql += ' AND idProperty IS NULL'
    elif mode == 'hasproperties':
        sql += ' AND idProperty IS NOT NULL'
    #print sql
    
    cursor.execute(sql)
    logging(sql)
    
    if count == None:
        rows = cursor.fetchall()
    else:
        row = cursor.fetchone()
        return row['cnt']
        
    return rows

def get_locations_by_depth(cursor, depth=1, limit=None,display_pri='displayPriority'):
    sql = """
    SELECT *
    FROM Locations
    WHERE depth={depth}
    """.format(depth=depth)
            
    if display_pri != None:
        sql += ' ORDER BY {display_pri}'.format(display_pri=display_pri)

    if limit != None:
        sql += ' LIMIT {limit}'.format(limit=limit)

    cursor.execute(sql)
    logging.debug(sql)
    
    rows = cursor.fetchall()
    return rows


def update_totals(cursor, id_loc, t, t_vis):
    sql = """
    UPDATE Locations
    SET total={t}, totalVisible={t_vis}
    WHERE idLocation={id_loc}
    LIMIT 1
    """.format(t=t,t_vis=t_vis,id_loc=id_loc)
    
    if not config.debug['sql']:
        cursor.execute(sql)
        
    logging.debug(sql)
    
def delete_leaf_node(cursor, lft):
    cursor.execute('BEGIN')
    
    sql_delete = """
    DELETE FROM Locations
    WHERE lf = {lf}
    """.format(lf=lft)
    if not config.debug['sql']:
        cursor.execute(sql_delete)
        
    sql_update_lft = """
    UPDATE Locations
    SET lt = lt - 2
    WHERE lt > {lft_new}
    """.format(lft_new=lft+1)
    if not config.debug['sql']:
        cursor.execute(sql_update_lft)
        
    sql_update_rgt = """
    UPDATE Locations
    SET rt = rt - 2
    WHERE rt > {rgt_new}
    """.format(rgt_new=lft+1)
    if not config.debug['sql']:
        cursor.execute(sql_update_rgt)
    
    cursor.execute('COMMIT')