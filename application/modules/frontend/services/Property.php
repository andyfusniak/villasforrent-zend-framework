<?php
class Frontend_Service_Property
{
    /**
     * @var DOMDocument the DOM tree
     */
    protected $_domDocument = null;

    /**
     * @var Frontend_Model_PropertyMapper the mapper
     */
    protected $_propertyMapper = null;

    private function _attachPropertyElements($propertyElement, $propertyObj)
    {
        $idPropertyAttrib = $this->_domDocument->createAttribute("idProperty");
        $idPropertyAttrib->value = $propertyObj->getIdProperty();
        $propertyElement->appendChild($idPropertyAttrib);

        // idPropertyType (2)
        $propertyElement->appendChild(
            $this->_createElement(
                "idPropertyType",
                null,
                $propertyObj->getIdPropertyType()
            )
        );

        // idAdvertiser (3)
        $propertyElement->appendChild(
            $this->_createElement(
                "idAdvertiser",
                null,
                $propertyObj->getIdAdvertiser()
            )
        );

        // idHolidayType (4)
        $propertyElement->appendChild(
            $this->_createElement(
                "idHolidayType",
                null,
                $propertyObj->getIdHolidayType()
            )
        );

        // idLocation (5)
        $propertyElement->appendChild(
            $this->_createElement(
                "idLocation",
                null,
                $propertyObj->getIdLocation()
            )
        );

        // urlName (6)
        $propertyElement->appendChild(
            $this->_createElement(
                "urlName",
                null,
                $propertyObj->getUrlName()
            )
        );

        // shortName (7)
        $propertyElement->appendChild(
            $this->_createElement(
                "shortName",
                array('lg' => 'en'),
                $propertyObj->getShortName()
            )
        );

        // locationUrl (8)
        $propertyElement->appendChild(
            $this->_createElement(
                "locationUrl",
                null,
                $propertyObj->getLocationUrl()
            )
        );

        // numBeds (9)
        $propertyElement->appendChild(
            $this->_createElement(
                "numBeds",
                null,
                $propertyObj->getNumBeds()
            )
        );

        // numSleeps (10)
        $propertyElement->appendChild(
            $this->_createElement(
                "numSleeps",
                null,
                $propertyObj->getNumSleeps()
            )
        );

        // approved (11)
        $propertyElement->appendChild(
            $this->_createElement(
                "approved",
                null,
                $propertyObj->getApproved()
            )
        );

        // visible (12)
        $propertyElement->appendChild(
            $this->_createElement(
                "visible",
                null,
                $propertyObj->getVisible()
            )
        );

        // expiry (13) - not used any more

        // emailAddress (14)
        $propertyElement->appendChild(
            $this->_createElement(
                "emailAddress",
                null,
                $propertyObj->getEmailAddress()
            )
        );

        // photoLimit (15)
        $propertyElement->appendChild(
            $this->_createElement(
                "photoLimit",
                null,
                $propertyObj->getPhotoLimit()
            )
        );

        // added (16)
        $propertyElement->appendChild(
            $this->_createElement(
                "added",
                null,
                $propertyObj->getAdded()
            )
        );

        // updated (17)
        $propertyElement->appendChild(
            $this->_createElement(
                "updated",
                null,
                $propertyObj->getUpdated()
            )
        );

        // awaitingApproval (18)
        $propertyElement->appendChild(
            $this->_createElement(
                "awaitingApproval",
                null,
                $propertyObj->getAwaitingApproval()
            )
        );

        // changesMade (19)
        $propertyElement->appendChild(
            $this->_createElement(
                "changesMade",
                null,
                $propertyObj->getChangesMade()
            )
        );

        // status (20)
        $propertyElement->appendChild(
            $this->_createElement(
                "status",
                null,
                $propertyObj->getStatus()
            )
        );

        // checksumMaster (21)
        $propertyElement->appendChild(
            $this->_createElement(
                "checksumMaster",
                null,
                $propertyObj->getChecksumMaster()
            )
        );

        // checksumUpdate (22)
        $propertyElement->appendChild(
            $this->_createElement(
                "checksumUpdate",
                null,
                $propertyObj->getChecksumUpdate()
            )
        );

        // lastModifiedBy (23)
        $propertyElement->appendChild(
            $this->_createElement(
                "lastModifiedBy",
                null,
                $propertyObj->getLastModifiedBy()
            )
        );
    }

    private function _attachContentElements($propertyElement, $propertyObj)
    {
        // locationUrl (1)
        $propertyElement->appendChild(
            $this->_createElement(
                "cLocationUrl",
                null,
                $propertyObj->getClocationUrl()
            )
        );

        // metaData (2)
        $propertyElement->appendChild(
            $this->_createElement(
                "metaData",
                null,
                $propertyObj->getMetaData()
            )
        );

        // seoData (3)
        $propertyElement->appendChild(
            $this->_createElement(
                "seoData",
                null,
                $propertyObj->getSeoData()
            )
        );

        // website (4)
        $propertyElement->appendChild(
            $this->_createElement(
                "website",
                null,
                $propertyObj->getWebsite()
            )
        );

        // headline1 (5)
        $propertyElement->appendChild(
            $this->_createElement(
                'headline1',
                array('lg' => 'en'),
                $propertyObj->getHeadline1()
            )
        );

        // headline2 (6)
        $propertyElement->appendChild(
            $this->_createElement(
                'headline2',
                array('lg' => 'en'),
                $propertyObj->getHeadline2()
            )
        );

        // summary (7)
        $propertyElement->appendChild(
            $this->_createElement(
                'summary',
                array('lg' => 'en'),
                $propertyObj->getSummary()
            )
        );

        // description (8)
        $propertyElement->appendChild(
            $this->_createElement(
                'description',
                array('lg' => 'en'),
                $propertyObj->getDescription()
            )
        );

        // bedroomDesc (9)
        $propertyElement->appendChild(
            $this->_createElement(
                'bedroomDesc',
                array('lg' => 'en'),
                $propertyObj->getBedroomDesc()
            )
        );

        // bathroomDesc (10)
         $propertyElement->appendChild(
            $this->_createElement(
                'bathroomDesc',
                array('lg' => 'en'),
                $propertyObj->getBathroomDesc()
            )
        );

        // kitchenDesc (11)
        $propertyElement->appendChild(
            $this->_createElement(
                'kitchenDesc',
                array('lg' => 'en'),
                $propertyObj->getKitchenDesc()
            )
        );

        // utilityDesc (12)
        $propertyElement->appendChild(
            $this->_createElement(
                'utilityDesc',
                array('lg' => 'en'),
                $propertyObj->getUtilityDesc()
            )
        );

        // livingDesc (13)
         $propertyElement->appendChild(
            $this->_createElement(
                'livingDesc',
                array('lg' => 'en'),
                $propertyObj->getLivingDesc()
            )
        );

        // otherDesc (14)
         $propertyElement->appendChild(
            $this->_createElement(
                'otherDesc',
                array('lg' => 'en'),
                $propertyObj->getOtherDesc()
            )
        );

        // serviceDesc (15)
        $propertyElement->appendChild(
            $this->_createElement(
                'serviceDesc',
                array('lg' => 'en'),
                $propertyObj->getServiceDesc()
            )
        );

        // notesDesc (16)
        $propertyElement->appendChild(
            $this->_createElement(
                'notesDesc',
                array('lg' => 'en'),
                $propertyObj->getNotesDesc()
            )
        );

        // accessDesc (17)
        $propertyElement->appendChild(
            $this->_createElement(
                'accessDesc',
                array('lg' => 'en'),
                $propertyObj->getAccessDesc()
            )
        );

        // outsideDesc (18)
        $propertyElement->appendChild(
            $this->_createElement(
                'outsideDesc',
                array('lg' => 'en'),
                $propertyObj->getOutsideDesc()
            )
        );

        // golfDesc (19)
        $propertyElement->appendChild(
            $this->_createElement(
                'golfDesc',
                array('lg' => 'en'),
                $propertyObj->getGolfDesc()
            )
        );

        // skiingDesc (20)
         $propertyElement->appendChild(
            $this->_createElement(
                'skiingDesc',
                array('lg' => 'en'),
                $propertyObj->getSkiingDesc()
            )
        );

        // specialDesc (21)
        $propertyElement->appendChild(
            $this->_createElement(
                'specialDesc',
                array('lg' => 'en'),
                $propertyObj->getSpecialDesc()
            )
        );

        // beachDesc (22)
        $propertyElement->appendChild(
            $this->_createElement(
                'beachDesc',
                array('lg' => 'en'),
                $propertyObj->getBeachDesc()
            )
        );

        // travelDesc (23)
        $propertyElement->appendChild(
            $this->_createElement(
                'travelDesc',
                array('lg' => 'en'),
                $propertyObj->getTravelDesc()
            )
        );

        // testimonialsDesc
        $propertyElement->appendChild(
            $this->_createElement(
                'testimonialsDesc',
                array('lg' => 'en'),
                $propertyObj->getTestimonialsDesc()
            )
        );

        // changeoverDesc
        $propertyElement->appendChild(
            $this->_createElement(
                'changeoverDesc',
                array('lg' => 'en'),
                $propertyObj->getChangeoverDesc()
            )
        );

        // contactDesc
        $propertyElement->appendChild(
            $this->_createElement(
                'contactDesc',
                array('lg' => 'en'),
                $propertyObj->getContactDesc()
            )
        );

        // country
        $propertyElement->appendChild(
            $this->_createElement(
                'country',
                array('lg' => 'en'),
                $propertyObj->getCountry()
            )
        );

        // region
        $propertyElement->appendChild(
            $this->_createElement(
                'region',
                array('lg' => 'en'),
                $propertyObj->getRegion()
            )
        );

        // location
        $propertyElement->appendChild(
            $this->_createElement(
                'location',
                array('lg' => 'en'),
                $propertyObj->getLocation()
            )
        );
    }

    private function _attachCalendarElements($propertyElement, $propertyObj)
    {
        $calendarsElement = $this->_createElement('calendars');

        foreach ($propertyObj->getCalendars() as $calendarObj) {
            $calendarElement = $this->_createElement(
                'calendar',
                array(
                    'idCalendar'  => $calendarObj->getIdCalendar(),
                    'rentalBasis' => $calendarObj->getRentalBasis(),
                    'currenyCode' => $calendarObj->getCurrencyCode()
                )
            );

            $rates = $calendarObj->getRatesList();
            if ($rates) {
                $ratesElement = $this->_createElement('rates');
                foreach ($rates as $rateObj) {
                    $rateElement = $this->_createElement(
                        'rate',
                        array(
                            'idRate' => $rateObj->getIdRate()
                        )
                    );

                    // startDate
                    $rateElement->appendChild(
                        $this->_createElement(
                            'startDate',
                            null,
                            $rateObj->getStartDate()
                        )
                    );

                    // endDate
                    $rateElement->appendChild(
                        $this->_createElement(
                            'endDate',
                            null,
                            $rateObj->getEndDate()
                        )
                    );

                    // name
                    $rateElement->appendChild(
                        $this->_createElement(
                            'name',
                            array('lg' => 'en'),
                            $rateObj->getName()
                        )
                    );

                    // minStayDays
                    $rateElement->appendChild(
                        $this->_createElement(
                            'minStayDays',
                            null,
                            $rateObj->getMinStayDays()
                        )
                    );

                    // weeklyRate
                    $rateElement->appendChild(
                        $this->_createElement(
                            'weeklyRate',
                            null,
                            $rateObj->getWeeklyRate()
                        )
                    );

                    // weekendNightlyRate
                    $rateElement->appendChild(
                        $this->_createElement(
                            'weekendNightlyRate',
                            null,
                            $rateObj->getWeekendNightlyRate()
                        )
                    );

                    // midweekNightlyRate
                    $rateElement->appendChild(
                        $this->_createElement(
                            'midweekNightlyRate',
                            null,
                            $rateObj->getMidweekNightlyRate()
                        )
                    );

                    // added
                    $rateElement->appendChild(
                        $this->_createElement(
                            'added',
                            null,
                            $this->_convertMysqlDatetimeToXsdDatetime($rateObj->getAdded())
                        )
                    );

                    // updated
                    $rateElement->appendChild(
                        $this->_createElement(
                            'updated',
                            null,
                            $this->_convertMysqlDatetimeToXsdDatetime($rateObj->getUpdated())
                        )
                    );

                    $ratesElement->appendChild($rateElement);
                }

                $calendarElement->appendChild($ratesElement);
            }

            $availability = $calendarObj->getAvailabilityList();
            if ($availability) {
                $availabilitiesElement = $this->_createElement('availabilities');

                foreach ($availability as $availabilityObj) {
                    $availabilityElement = $this->_createElement(
                        'availability',
                        array('idAvailability' => $availabilityObj->getIdAvailability())
                    );

                    $availabilityElement->appendChild(
                        $this->_createElement('startDate',
                            null,
                            $availabilityObj->getStartDate()
                        )
                    );

                    $availabilityElement->appendChild(
                        $this->_createElement('endDate',
                            null,
                            $availabilityObj->getEndDate()
                        )
                    );

                    $availabilitiesElement->appendChild($availabilityElement);
                }

                $calendarElement->appendChild($availabilitiesElement);
            }

            $calendarsElement->appendChild($calendarElement);
        }


        $propertyElement->appendChild($calendarsElement);
    }

    private function _createPhotoElements($propertyObj)
    {
        $photos = $propertyObj->getPhotoList();

        if ($photos) {
            $photosElement = $this->_createElement('photos');

            foreach ($photos as $photoObj) {
                $photoElement = $this->_createElement(
                    'photo',
                    array('idPhoto' => $photoObj->getIdPhoto())
                );

                // approved
                $photoElement->appendChild(
                    $this->_createElement(
                        'approved',
                        null,
                        $photoObj->getApproved()
                    )
                );

                // displayPriority
                $photoElement->appendChild(
                    $this->_createElement(
                        'displayPriority',
                        null,
                        $photoObj->getDisplayPriority()
                    )
                );

                // originalFilename
                $photoElement->appendChild(
                    $this->_createElement(
                        'originalFilename',
                        null,
                        $photoObj->getOriginalFilename()
                    )
                );

                // fileType
                $photoElement->appendChild(
                    $this->_createElement(
                        'fileType',
                        null,
                        $photoObj->getFileType()
                    )
                );

                // widthPixels
                $photoElement->appendChild(
                    $this->_createElement(
                        'widthPixels',
                        null,
                        $photoObj->getWidthPixels()
                    )
                );

                // heightPixels
                $photoElement->appendChild(
                    $this->_createElement(
                        'heightPixels',
                        null,
                        $photoObj->getHeightPixels()
                    )
                );

                // sizeK
                $photoElement->appendChild(
                    $this->_createElement(
                        'sizeK',
                        null,
                        $photoObj->getSizeK()
                    )
                );

                // caption
                $photoElement->appendChild(
                    $this->_createElement(
                        'caption',
                        array('lg' => 'en'),
                        $photoObj->getCaption()
                    )
                );

                // visible
                $photoElement->appendChild(
                    $this->_createElement(
                        'visible',
                        null,
                        $photoObj->getVisible()
                    )
                );

                // added
                $photoElement->appendChild(
                    $this->_createElement(
                        'added',
                        null,
                        $this->_convertMysqlDatetimeToXsdDatetime($photoObj->getAdded())
                    )
                );

                // updated
                $photoElement->appendChild(
                    $this->_createElement(
                        'updated',
                        null,
                        $this->_convertMysqlDatetimeToXsdDatetime($photoObj->getUpdated())
                    )
                );

                // lastModifiedBy
                $photoElement->appendChild(
                    $this->_createElement(
                        'lastModifiedBy',
                        null,
                        $photoObj->getLastModifiedBy()
                    )
                );

                $photosElement->appendChild($photoElement);
            }

            return $photosElement;
        }

        return null;
    }

    private function _convertMysqlDatetimeToXsdDatetime($dt)
    {
        return str_replace(" ", "T", $dt);
    }

    private function _createElement($elementName, $attribs = array(), $textContent = null)
    {
        $element = $this->_domDocument->createElement($elementName);

        if (null !== $attribs) {
            foreach ($attribs as $name => $value) {
                $attrib = $this->_domDocument->createAttribute($name);
                $attrib->value = $value;
                $element->appendChild($attrib);
            }
        }

        if (null !== $textContent) {
            $textNode = $this->_domDocument->createTextNode($textContent);
            $element->appendChild($textNode);
        }

        return $element;
    }

    public function getPropertyXml($idProperty)
    {
        if (null === $this->_propertyMapper)
            $this->_propertyMapper = new Frontend_Model_PropertyMapper();

        $propertyObj = $this->_propertyMapper->getPropertyByPropertyId($idProperty);

        //var_dump($propertyObj);

        $this->_domDocument = new DOMDocument("1.0", "utf-8");
        $this->_domDocument->formatOutput = true;

        $propertyElement = $this->_domDocument->createElement("property");
        $this->_domDocument->appendChild($propertyElement);

        $this->_attachPropertyElements($propertyElement, $propertyObj);
        $this->_attachContentElements($propertyElement, $propertyObj);

        $this->_attachCalendarElements($propertyElement, $propertyObj);

        $photoElements = $this->_createPhotoElements($propertyObj);
        if ($photoElements) {
            $propertyElement->appendChild($photoElements);
        }

        //header("Content-Type: text/plain");
        //$xml = preg_replace("/(\s+)\<([a-zA-Z-]+)\>/", "\n$1<$2>", );
        $xml = preg_replace("/( ){2}/", "    ", $this->_domDocument->saveXML());
        return $xml;
    }
}
