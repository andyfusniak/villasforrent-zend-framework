# db config
import os

hwd = os.getenv('HOME') + os.sep

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
    'remove_unapproved_property': hwd + os.sep + '.hpw/remove-unapproved-property.log',
    'delete-expired-password-resets': hwd + os.sep + '.hpw/delete-expired-password-resets.log',
    'check-locations': hwd + os.sep + '.hpw/check-locations.log'
}

# set to True to enable debug and prevent real SQL queries
debug = {
    'sql': False
}

photo = {
    'images_original_dir': application_path + "/../data/images_originals",
    'images_dynamic_dir':  application_path + "/../public/photos"
}
