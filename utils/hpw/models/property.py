import sys
import config
import logging

### Properties
def update_property_checksums(cursor, id_prop, m_cs, u_cs):
    sql = """
    UPDATE Properties
    SET checksumMaster='{m_cs}', checksumUpdate='{u_cs}', updated=NOW(), lastModifiedBy='python'
    WHERE idProperty={id_prop}
    """.format(m_cs = m_cs, u_cs = u_cs, id_prop = id_prop)

    if not config.debug['sql']:
        cursor.execute(sql)
        #print sql
    logging.debug(sql)


### PropertiesContent
def get_property_content_by_property_id(cursor, id_prop, version):
    sql = """
    SELECT *
    FROM PropertiesContent
    WHERE idProperty={id_property}
    AND version={version}
    """.format(id_property = id_prop, version=version)

    cursor.execute(sql)
    rows = cursor.fetchall()

    logging.debug(sql)

    return rows

def count_by_loc_name(cursor, url, visible=None):
    sql = """
    SELECT COUNT(1) as cnt
    FROM Properties
    WHERE locationUrl LIKE '{url}%'
    """.format(url=url)

    if visible == True:
        sql += ' AND visible=1'

    cursor.execute(sql)
    logging.debug(sql)

    row = cursor.fetchone()
    return row['cnt']

# CREATE

# READ

def is_property_approved(cursor, id_property):
    sql = """
    SELECT approved
    FROM Properties
    WHERE idProperty=%s
    """
    cursor.execute(sql, (id_property))


    row = cursor.fetchone()
    if row == None:
        raise PropertyNotFound

    if row['approved'] == 1:
        logging.debug("Property %s is approved", (id_property))
        return True
    else:
        logging.debug("Property {id_property} is unapproved so we can process it ".format(id_property = id_property))
        return False

def delete_property_content_by_property_id(cursor, id_property):
    sql = """
    DELETE
    FROM PropertiesContent
    WHERE idProperty={id_property}
    """.format(id_property = id_property)

    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)

def delete_photos_by_property_id(cursor, id_property):
    sql = """
    DELETE
    FROM Photos
    WHERE idProperty={id_property}
    """.format(id_property = id_property)

    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)

def delete_property_by_pk(cursor, id_property):
    sql = """
    DELETE
    FROM Properties
    WHERE idProperty={id_property}
    """.format(id_property = id_property)

    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)

# UPDATE

def update_property_content_by_pk(cursor, pk, checksum):
    sql = """
    UPDATE PropertiesContent
    SET cs='{cs}', updated=NOW()
    WHERE idPropertyContent={pk}
    """.format(cs = checksum, pk = pk)

    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)

def update_property_location_id(cursor, id_prop, id_loc):
    sql = """
    UPDATE Properties
    SET idLocation = {id_loc}, updated=now()
    WHERE idProperty = {id_prop}
    """.format(id_prop=id_prop, id_loc=id_loc)

    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)
