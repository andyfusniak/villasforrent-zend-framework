<?php
class Frontend_Form_Step2PropertyForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl() . '/advertiser-property/step2-property');
        $this->setName('step2');
		$this->setAttrib('id', 'step2');
		
		$numBeds = new Zend_Form_Element_Select('numBeds');
        $numBeds->setLabel('Number of bedrooms:')
				->addMultiOption('', '--select--')
				->addMultiOptions(array_combine(range(1, 26), range(1, 26)))
				->setRequired(true)
				->addValidator('NotEmpty', true, array(
									'messages' => array('isEmpty' => 'Select the number of bedrooms')));
		
		$numSleeps = new Zend_Form_Element_Select('numSleeps');
        $numSleeps->setLabel('Sleeping capacity:')
				  ->addMultiOption('', '--select--')
				  ->addMultiOptions(array_combine(range(1, 40), range(1, 40)))
				  ->setRequired(true)
				  ->addValidator('NotEmpty', true, array(
									'messages' => array('isEmpty' => 'Select the number of people this property sleeps')));
		
		$summary = new Zend_Form_Element_Textarea('summary');
		$summary->setLabel('Brief summary:')
			    ->setDescription('A short descriptive summary of your property including its key features.');
				
		$headline1 = new Zend_Form_Element_Text('headline1');
		$headline1->setLabel('Headline 1:')
				  ->setDescription('eg \'Villa with Private Pool and Sea Views\'')
				  ->setRequired(true)
				  ->addValidator('NotEmpty', true, array(
									'messages' => array('isEmpty' => 'Enter your primary headline for the property')));
				  
		$headline2 = new Zend_Form_Element_text('headline2');
		$headline2->setLabel('Headline 2:')
				  ->setDescription('eg \'Peaceful Location, 10 Minutes Walk to the Beach\'')
				  ->setRequired(true)
				  ->addValidator('NotEmpty', true, array(
									'messages' => array('isEmpty' => 'Enter your secondary headline for the property')));
		  
		$description = new Zend_Form_Element_Textarea('description');
		$description->setLabel('Opening Paragraph:')
					->setDescription("Brief description of the bathrooms, eg\n\nEn-suite bathroom to bedroom 1 with bath, wc<br />Bathroom with bath/shower over, wc, bidet<br />Separate wc");
		
		$bedroomDesc = new Zend_Form_Element_Textarea('bedroomDesc');
		$bedroomDesc->setLabel('Bedrooms:')
					->setDescription('Brief description of the bathrooms, eg<br />En-suite bathroom to bedroom 1 with bath, wc<br />Bathroom with bath/shower over, wc, bidet<br />Separate wc');
		
		$bathroomDesc = new Zend_Form_Element_Textarea('bathroom');
		$bathroomDesc->setLabel('Bathroom(s):');
		
		$kitchenDesc = new Zend_Form_Element_Textarea('kitchenDesc');
		$kitchenDesc->setLabel('Kitchen:')
				    ->setDescription('Include the main kitchen equipment, eg cooker, hob, fridge, freezer, washing machine, microwave etc');
		
		$utilityDesc = new Zend_Form_Element_Textarea('utilityDesc');
		$utilityDesc->setLabel('Utility Room:')
					->setDescription('Include equipment');
		
		$livingDesc = new Zend_Form_Element_Textarea('livingDesc');
		$livingDesc->setLabel('Living Rooms:')
				   ->setDescription('Brief description of living rooms (eg lounge, dining room, etc) and equipment, eg TV, video, etc. Include sofa beds if applicable and state whether they are double or single');
		

		$otherDesc = new Zend_Form_Element_Textarea('other');
		$otherDesc->setLabel('Other:');
		
		
		
		$serviceDesc = new Zend_Form_Element_Textarea('serviceDesc');
		$serviceDesc->setLabel('Cleaning/towels/linen/maid service:')
					->setDescription('Please state whether towels and linen are included, how often the property is cleaned and how often towels and linen are changed etc');
		
		
		$propertyModel = new Common_Model_Property();
		$facilityRowSet = $propertyModel->getAllFacilities(true);
		
		//var_dump($facilityRowSet);
		//exit;
		
		$facilities = new Zend_Form_Element_MultiCheckbox('facilities');
		$facilities->setLabel('Type of Area:');
		foreach ($facilityRowSet as $row) {
			$facilities->addMultiOption($row->facilityCode, $row->name);
		}
		
		
		$notesDesc = new Zend_Form_Element_Textarea('notesDesc');
		$notesDesc->setLabel('Notes on accommodation:')
		          ->setDescription('Any further information you would like to include about the accommodation, eg heating, air-conditioning, suitability for children etc');	
		
		$accessDesc = new Zend_Form_Element_Textarea('accessDesc');
		$accessDesc->setLabel('Wheelchair access:');
		
		$outsideDesc = new Zend_Form_Element_Textarea('outsideDesc');
		$outsideDesc->setLabel('Outside the property:')
		            ->setDescription('Include gardens, patios, barbecue, outdoor furniture, etc. If you have a swimming pool please give the size if possible and state whether it is private or shared. If it can be heated, please include this');
					
		$golfDesc = new Zend_Form_Element_Textarea('golf');
		$golfDesc->setLabel('Golf:')
		         ->setDescription('Distance and description of local golf courses, if applicable');
		
		$skiingDesc = new Zend_Form_Element_Textarea('skiingDesc');
		$skiingDesc->setLabel('Skiing')
				   ->setDescription('For properties close to ski resorts. Please give details of easily accessible skiing facilities. Full details should be provided of the area and skiing opportunities');
				   
		$specialDesc = new Zend_Form_Element_Textarea('specialDesc');
		$specialDesc->setLabel('Special Interest Holidays:')
					->setDescription('If you provide special interest holidays for your guests, eg painting or cookery courses, please describe this here. Please note that this section should not be used to describe locally available facilities, which can be listed in the \'Further details\' section');
		
		$beachDesc = new Zend_Form_Element_Textarea('beachDesc');
		$beachDesc->setLabel('Coastline and Beach:')
				  ->setDescription('Please give distance to coast or beaches. (If a time is given, please state whether walking or driving). A description of the beach or beaches can also be included');
		
		$travelDesc = new Zend_Form_Element_Textarea('travelDesc');
		$travelDesc->setLabel('Travel:')
				   ->setDescription('Include the name and distance of nearest airport(s) or, if appropriate, driving distances from channel ports etc. Please also state if car hire (or use of car) is essential or recommended');
				   
		$bookingDesc = new Zend_Form_Element_Textarea('bookingDesc');
		$bookingDesc->setLabel('Booking Notes:');
		$bookingDesc->setDescription('Brief details of booking procedure, deposits, timing of payments, etc');
		
		$changeoverDesc = new Zend_Form_Element_Textarea('changeoverDesc');
		$changeoverDesc->setLabel('Changeover:')
					   ->setDescription('Please state if you have a preferred changeover day or if this is flexible. Arrival and departure times may also be given here');
		
		$contactDesc = new Zend_Form_Element_Textarea('contactDesc');
		$contactDesc->setLabel('Contact Details')
					->setDescription('Please list your contact details as you wish them to appear on your advertisement, eg<br />John Smith<br />Tel: (UK) + 44 (0)1234 567890<br />Fax: (UK) + 44 (0)1234 567890<br />Mobile: (UK) + 44 (0)7777 123456<br />Please do not include email addresses here - use the two email address boxes instead');
		
		$testimonialsDesc = new Zend_Form_Element_Textarea('testimonialsDesc');
		$testimonialsDesc->setLabel('Testimonials:')
						 ->setDescription('Please use this section to provide any testimonials or feedback that you have received from your guests. We suggest a maximum of 3 guest comments');
		
		$emailAddress = new Zend_Form_Element_Text('emailAddress');
		$emailAddress->setLabel('Email address:')
					 ->setDescription('Enter the e-mail address(es) that you would like rental enquiries sent to.<br />Please note that the email address you specify in your personal contact details is used for billing, renewal and newsletter communications from us.');
		
		$website = new Zend_Form_Element_Text('website');
		$website->setLabel('Personal website link:')
				->setDescription('If you have a personal website and would like us to include a link to this, please provide your URL. Note that we do not accept links to web sites which advertise other properties or to commercial/business sites. We reserve the right to refuse or remove these links at our discretion.');
		
		$this->addElements(array($numBeds, $numSleeps, $summary, $headline1, $headline2,
								 $description, $bedroomDesc, $bathroomDesc, $kitchenDesc,
								 $utilityDesc, $livingDesc, $otherDesc, $serviceDesc,
								 $facilities, $notesDesc, $accessDesc, $outsideDesc,
								 $golfDesc, $skiingDesc, $specialDesc, $beachDesc,
								 $travelDesc, $bookingDesc, $changeoverDesc, $contactDesc,
								 $testimonialsDesc, $emailAddress, $website));
		
		$this->addElement('submit', 'submit', array('required' => false,
                                                    'ignore' => true,
                                                    'decorators' => array('ViewHelper',array('HtmlTag',
                                                        array('tag' => 'dd', 'id' => 'form-submit')))
                                                    ));
	}
}
