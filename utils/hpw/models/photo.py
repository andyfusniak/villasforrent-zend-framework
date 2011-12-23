def get_all_photo_ids_by_property_id(cursor, id_property):
    sql = """
    SELECT idPhoto
    FROM Photos
    WHERE idProperty={id_property}
    """.format(id_property = id_property)
    
    cursor.execute(sql)
    logging.debug(sql)
    
    
    rows = cursor.fetchall()
    
    # for each record returned, add it to the list
    # of photo ids
    l = []
    for record in rows:
        l.append(int(record['idPhoto']))
        
    logging.debug(l)
    return l
        
def get_photos_by_property_id(cursor, id_property):
    cursor.execute("""
    SELECT *
    FROM Photos
    WHERE idProperty=%s
    """, (id_property))
    
    return cursor.fetchall()
    
# DELETE filesystem procedures
def delete_filesystem_photos(id_property, photolist):
    # reference the base path to the original images
    original_image_dir = config.photo['images_original_dir']
    logging.info("Original Image Dir: " + original_image_dir)
    
    # reference the base path to the cache (dynamic) images
    cache_image_dir = config.photo['images_dynamic_dir']
    logging.info("Cache Image Dir: " + cache_image_dir)
    
    # for each photo id in our whack list
    for id_photo in photolist:
        file_details = path_to_photo_file(id_property, id_photo, block_interval=50)
        
        #
        # deal with the original image
        #
        
        # generate the full path to the original image
        orig_img = original_image_dir + '/' + file_details['fullpath']
        container_dir = original_image_dir + '/' + file_details['dir'] 
        logging.debug("container_dir is " + container_dir)
        
        # if it exists on the filesystem then delete it
        if os.path.exists(container_dir):
            if os.path.exists(orig_img):
                logging.debug("Following image exists on the filesystem:")
                logging.debug(orig_img)
                os.remove(orig_img)
                logging.info("Deleted Original Photo " + orig_img)             
            else:
                logging.info("Original image " + orig_img + " is missing from the filesystem")
        else:
            logging.debug("Container dir " + container_dir + " does not exist so I didn't check for the file deletion within")
        # remove the container directory if it exists
        # and is empty
        # e.g. 10500/10515
        if os.path.exists(container_dir):
            if os.listdir(container_dir) == []:
                os.rmdir(container_dir)
                logging.info("Deleted Containing Original Dir " + container_dir)
                
        #
        # deal with the cache (dynamic) images using globing
        #
        
        g = cache_image_dir + '/' + file_details['dir'] \
          + '/' + file_details['file'] \
          + '_*' + file_details['extension']
        logging.debug("globbing string is " + g)
        
        container_dir = cache_image_dir + '/' + file_details['dir']
        logging.debug("container cache dir is " + container_dir)
        
        # for each file in the glob scan
        if os.path.exists(container_dir):
            for filename in glob.glob(g) :
                os.remove(filename)
        else:
            logging.info("Cache Container Dir " + container_dir + " does not exist so I didn't check for the files within")
        
        if os.path.exists(container_dir):
            if os.listdir(container_dir) == []:
                os.rmdir(container_dir)
                logging.info("Deleted Containing Cache Dir " + container_dir)
