def expire_oauth_grants(cursor):
    sql = """
    DELETE
    FROM OAuthGrants WHERE expiry <= NOW()
    """
    cursor.execute(sql)

