# db config
import os
if os.getenv('APPLICATION_ENV') == 'development':
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
    'remove_unapproved_property': '/home/andy/.hpw/remove-unapproved-property.log',
    'delete-expired-password-resets': '/home/andy/.hpw/delete-expired-password-resets.log'
}

# set to True to enable debug and prevent real SQL queries
debug = {
    'sql': True
}


photo = {
    'images_original_dir': application_path + "/../data/images_originals",
    'images_dynamic_dir':  application_path + "/../public/photos"
}
