def get_all_advertisers_reset(cursor):
    sql = """
    SELECT *
    FROM AdvertisersReset
    """
    cursor.execute(sql)

    rows = cursor.fetchall()

    return rows

def delete_advertiser_reset(cursor, id_ar):
    sql = """
    DELETE
    FROM AdvertisersReset 
    WHERE idAdvertiserReset={id_advertisers_reset}
    """.format(id_advertisers_reset = id_ar)

    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)
    
    
