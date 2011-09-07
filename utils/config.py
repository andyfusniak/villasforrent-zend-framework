# db config
import os
if os.getenv('APPLICATION_ENV') == 'production':
    dbhost = 'localhost'
    dbuser = 'root'
    dbpass = 'mrgrey'
    dbname = 'hpw'
    
    application_path = '/var/www/zendvfr/application'
else:
    dbhost = 'localhost'
    dbuser = 'beta'
    dbpass = 'beta4046'
    dbname = 'hpw_live'
    
    application_path = '/var/www/www.holidaypropertyworldwide.com/application'

applogs = {
    'remove_unapproved_property': "/home/andy/.hpw/remove-unapproved-property.log"
}

# set to True to enable debug and prevent real SQL queries
debug = {
    'sql': False
}


photo = {
    'images_original_dir': application_path + "/../data/images_originals",
    'images_dynamic_dir':  application_path + "/../public/photos"
}
