<?php
class Common_Resource_PropertyContentField implements Common_Resource_PropertyContentField_Interface
{
	public static $propertiesContentFieldsMap = array (
		Common_Resource_PropertyContent::FIELD_LOCATION_URL 		=> 'locationUrl',
		Common_Resource_PropertyContent::FIELD_META_DATA 			=> 'metaData',
		Common_Resource_PropertyContent::FIELD_SEO_DATA 			=> 'seoData',
		Common_Resource_PropertyContent::FIELD_WEBSITE 				=> 'website',
		Common_Resource_PropertyContent::FIELD_HEADLINE_1 			=> 'headline1',
		Common_Resource_PropertyContent::FIELD_HEADLINE_2			=> 'headline2',
		Common_Resource_PropertyContent::FIELD_SUMMARY 				=> 'summary',
		Common_Resource_PropertyContent::FIELD_DESCRIPTION 			=> 'description',
		Common_Resource_PropertyContent::FIELD_BEDROOM_DESC			=> 'bedroomDesc',
		Common_Resource_PropertyContent::FIELD_BATHROOM_DESC 		=> 'bathroomDesc',		
		Common_Resource_PropertyContent::FIELD_KITCHEN_DESC 		=> 'kitchenDesc',
		Common_Resource_PropertyContent::FIELD_UTILITY_DESC 		=> 'utilityDesc',
		Common_Resource_PropertyContent::FIELD_LIVING_DESC 			=> 'livingDesc',
		Common_Resource_PropertyContent::FIELD_OTHER_DESC 			=> 'otherDesc',
		Common_Resource_PropertyContent::FIELD_SERVICE_DESC 		=> 'serviceDesc',
		Common_Resource_PropertyContent::FIELD_NOTES_DESC 			=> 'notesDesc',
		Common_Resource_PropertyContent::FIELD_ACCESS_DESC 			=> 'accessDesc',
		Common_Resource_PropertyContent::FIELD_OUTSIDE_DESC 		=> 'outsideDesc',
		Common_Resource_PropertyContent::FIELD_GOLF_DESC 			=> 'golfDesc',
		Common_Resource_PropertyContent::FIELD_SKIING_DESC 			=> 'skiingDesc',
		Common_Resource_PropertyContent::FIELD_SPECIAL_DESC 		=> 'specialDesc',
		Common_Resource_PropertyContent::FIELD_BEACH_DESC 			=> 'beachDesc',
		Common_Resource_PropertyContent::FIELD_TRAVEL_DESC 			=> 'travelDesc',
		Common_Resource_PropertyContent::FIELD_BOOKING_DESC 		=> 'bookingDesc',
		Common_Resource_PropertyContent::FIELD_TESTIMONIALS_DESC 	=> 'testimonialsDesc',
		Common_Resource_PropertyContent::FIELD_CHANGEOVER_DESC  	=> 'changeoverDesc',
		Common_Resource_PropertyContent::FIELD_CONTACT_DESC 		=> 'contactDesc',
		Common_Resource_PropertyContent::FIELD_COUNTRY 				=> 'country',
		Common_Resource_PropertyContent::FIELD_REGION 				=> 'region',
		Common_Resource_PropertyContent::FIELD_LOCATION 			=> 'location'
	);

    public static $propertiesContentNiceHeadersMap = array (
        Common_Resource_PropertyContent::FIELD_LOCATION_URL 		=> 'locationUrl',
		Common_Resource_PropertyContent::FIELD_META_DATA 			=> 'metaData',
		Common_Resource_PropertyContent::FIELD_SEO_DATA 			=> 'seoData',
		Common_Resource_PropertyContent::FIELD_WEBSITE 				=> 'Owners Website',
		Common_Resource_PropertyContent::FIELD_HEADLINE_1 			=> 'Headline 1',
		Common_Resource_PropertyContent::FIELD_HEADLINE_2			=> 'Headline 2',
		Common_Resource_PropertyContent::FIELD_SUMMARY 				=> 'Summary',
		Common_Resource_PropertyContent::FIELD_DESCRIPTION 			=> 'Introduction',
		Common_Resource_PropertyContent::FIELD_BEDROOM_DESC			=> 'Bedrooms',
		Common_Resource_PropertyContent::FIELD_BATHROOM_DESC 		=> 'Bathroom',
		Common_Resource_PropertyContent::FIELD_KITCHEN_DESC 		=> 'Kitchen',
		Common_Resource_PropertyContent::FIELD_UTILITY_DESC 		=> 'Utility Room',
		Common_Resource_PropertyContent::FIELD_LIVING_DESC 			=> 'Living Room',
		Common_Resource_PropertyContent::FIELD_OTHER_DESC 			=> 'Other',
		Common_Resource_PropertyContent::FIELD_SERVICE_DESC 		=> 'Service ',
		Common_Resource_PropertyContent::FIELD_NOTES_DESC 			=> 'Notes on Accommodation',
		Common_Resource_PropertyContent::FIELD_ACCESS_DESC 			=> 'Accessability',
		Common_Resource_PropertyContent::FIELD_OUTSIDE_DESC 		=> 'Outside the Property',
		Common_Resource_PropertyContent::FIELD_GOLF_DESC 			=> 'Golf',
		Common_Resource_PropertyContent::FIELD_SKIING_DESC 			=> 'Skiing',
		Common_Resource_PropertyContent::FIELD_SPECIAL_DESC 		=> 'Special Interests',
		Common_Resource_PropertyContent::FIELD_BEACH_DESC 			=> 'Beach',
		Common_Resource_PropertyContent::FIELD_TRAVEL_DESC 			=> 'Travel',
		Common_Resource_PropertyContent::FIELD_BOOKING_DESC 		=> 'How to Book',
		Common_Resource_PropertyContent::FIELD_TESTIMONIALS_DESC 	=> 'Testimonials',
		Common_Resource_PropertyContent::FIELD_CHANGEOVER_DESC  	=> 'Changeover Day',
		Common_Resource_PropertyContent::FIELD_CONTACT_DESC 		=> 'Contact Details',
		Common_Resource_PropertyContent::FIELD_COUNTRY 				=> 'Country',
		Common_Resource_PropertyContent::FIELD_REGION 				=> 'Region',
		Common_Resource_PropertyContent::FIELD_LOCATION 			=> 'Location'
    );
	
	public function getAllPropertyContentFields()
	{
		return self::$propertiesContentFieldsMap;
	}
}
