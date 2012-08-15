# db config
import sys
import os

hwd = os.getenv('HOME') + os.sep
app_env = os.getenv('APPLICATION_ENV')

if app_env == 'development':
    dbhost = 'localhost'
    dbuser = 'root'
    dbpass = 'mrgrey'
    dbname = 'hpw'
    
    application_path = '/var/www/zendvfr/application'
elif app_env == 'mars':
    dbhost = 'localhost'
    dbuser = 'root'
    dbpass = 'mrgrey'
    dbname = 'r5-mirror'
    
    application_path = '/var/www/zendvfr/application'
elif app_env == 'beta':
    dbhost = 'localhost'
    dbuser = 'beta'
    dbpass = 'beta4046'
    dbname = 'hpw_beta'

    application_path = '/var/www/beta.holidaypropertyworldwide.com'
elif app_env == 'release4':
    dbhost = 'localhost'
    dbuser = 'beta'
    dbpass = 'beta4046'
    dbname = 'hpw-release4'

    application_path = '/var/www/www.holidaypropertyworldwide.com/application'
else:
    dbhost = 'localhost'
    dbuser = 'beta'
    dbpass = 'beta4046'
    dbname = 'hpw_live'
    
    application_path = '/var/www/www.holidaypropertyworldwide.com/application'

applogs = {
    'remove_unapproved_property': hwd + os.sep + '.hpw/remove-unapproved-property.log',
    'delete-expired-password-resets': hwd + os.sep + '.hpw/delete-expired-password-resets.log',
    'check-locations': hwd + os.sep + '.hpw/check-locations.log',
    'update-property-count': hwd + os.sep + '.hpw/update-property-count.log'
}

# set to True to enable debug and prevent real SQL queries
debug = {
    'sql': False
}

photo = {
    'images_original_dir': application_path + "/../data/images_originals",
    'images_dynamic_dir':  application_path + "/../public/photos"
}
