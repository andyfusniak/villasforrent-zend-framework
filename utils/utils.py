import exceptions
import math

def property_generate_top_dir(id_property, block_interval=50):
    # e.g. 1234
    if id_property < 10000:
        raise PropertyOutOfRange
    
    # e.g 12345 - 10000 = 2345
    range = int(id_property) - 10000
    return int(10000 + (math.floor(range / block_interval) * block_interval))


def path_to_photo_file(id_property, id_photo, block_interval=50):
    d = str(property_generate_top_dir(id_property)) + '/' + str(id_property)
    f = str(id_photo)
    e = '.jpg'
    fp = d + '/' + f + e
    
    return {
        'dir': d,
        'file': f,
        'extension': e,
        'fullpath': fp
    }


