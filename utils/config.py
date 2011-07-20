# db config
import os
if os.getenv('APPLICATION_ENV') == 'production':
    dbhost = 'localhost'
    dbuser = 'root'
    dbpass = 'mrgrey'
    dbname = 'villasforrent'
else:
    dbhost = 'localhost'
    dbuser = 'beta'
    dbpass = 'beta4046'
    dbname = 'hpw_live'
