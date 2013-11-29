def get_all_tokens(cursor):
    sql = """
    SELECT *
    FROM AdvertisersReset
    """
    cursor.execute(sql)

    rows = cursor.fetchall()

    return rows

def delete_token(cursor, id_token):
    sql = """
    DELETE
    FROM AdvertisersReset
    WHERE idToken={id_token}
    """.format(id_token = id_token)

    if not config.debug['sql']:
        cursor.execute(sql)
    logging.debug(sql)
