def get_calendar_id_by_property_id(cursor, id_property):
    sql = """
    SELECT idCalendar
    FROM Calendars
    WHERE idProperty={id_property}
    """.format(id_property = id_property)
    
    cursor.execute(sql)
    logging.debug(sql)
    
    row = cursor.fetchone()
    
    if row == None:
        raise CalendarNotFound
    else:
        return row['idCalendar']
        
def get_rates_by_calendar_id(cursor, id_calendar):
    cursor.execute("""
    SELECT *
    FROM Rates
    WHERE id_calendar=%s
    """, (id_calendar))
    
    return cursor.fetchall()
    
def get_availability_by_calendar_id(cursor, id_calendar):
    cursor.execute("""
    SELECT *
    FROM Availability
    WHERE id_calendar=%s
    """, (id_calendar))
    
    return cursor.fetchall()

# UPDATE

#
# DELETE
#

def delete_rates_by_calendar_id(cursor, id_calendar):
    sql = """
    DELETE
    FROM Rates
    WHERE idCalendar={id_calendar}
    """.format(id_calendar = id_calendar)
    
    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)
    
    
def delete_avail_by_calendar_id(cursor, id_calendar):
    sql = """
    DELETE
    FROM Availability
    WHERE idCalendar={id_calendar}
    """.format(id_calendar = id_calendar)
    
    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)
    
    
def delete_calendar_by_property_id(cursor, id_property):
    sql = """
    DELETE
    FROM Calendars
    WHERE idProperty={id_property}
    """.format(id_property = id_property)

    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)
    

def delete_property_facilities_by_property_id(cursor, id_property):
    sql = """
    DELETE
    FROM PropertiesFacilities
    WHERE idProperty={id_property}
    """.format(id_property = id_property)
    
    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)