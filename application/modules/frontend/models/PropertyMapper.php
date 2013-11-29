<?php
class Frontend_Model_PropertyMapper extends Frontend_Model_PropertyMapperAbstract
{
    public function getAllPropertyIds()
    {
        $propertyIds = array();

        $propertyIdRowset = $this->getResource('Property')->getAllPropertyIds();

        foreach ($propertyIdRowset as $propertyRow) {
            array_push($propertyIds, $propertyRow->idProperty);
        }

        return $propertyIds;
    }

    /**
     * @param int @idProperty the property to retrieve
     * @return Frontend_Model_Property
     */
    public function getPropertyByPropertyId($idProperty)
    {
        $propertyRow = $this->getResource('Property')->getPropertyById($idProperty);
        $propertyContent = $this->getResource('PropertyContent')->getPropertyContentByPropertyId(
            $idProperty
        );

        $calendarResource = $this->getResource('Calendar');
        $idCalendar = $calendarResource->getCalendarIdByPropertyId($idProperty);
        $calendarRow = $calendarResource->getCalendarById($idCalendar);

        $calendarObj = new Frontend_Model_Calendar(
            $idCalendar,
            $calendarRow->rentalBasis,
            $calendarRow->currencyCode,
            $calendarRow->added,
            $calendarRow->updated
        );

        $ratesRowset = $this->getResource('Rate')->getRatesByCalendarId($idCalendar);
        foreach ($ratesRowset as $ratesRow) {
            $rateObj = new Frontend_Model_Rate(
                $ratesRow->idRate,
                $ratesRow->startDate,
                $ratesRow->endDate,
                $ratesRow->name,
                $ratesRow->weeklyRate,
                $ratesRow->weekendNightlyRate,
                $ratesRow->midweekNightlyRate,
                $ratesRow->added,
                $ratesRow->updated
            );

            $calendarObj->addRate($rateObj);
        }

        $availabilityRowset = $this->getResource('Availability')->getAvailabilityByCalendarId($idCalendar);
        foreach ($availabilityRowset as $availabilityRow) {
            $availabilityObj = new Frontend_Model_Availability(
                $availabilityRow->idAvailability,
                $availabilityRow->startDate,
                $availabilityRow->endDate
            );

            $calendarObj->addAvailability($availabilityObj);
        }

        $propertyObj = new Frontend_Model_Property();

        // set the property values
        $propertyObj->setIdProperty($idProperty);
        $propertyObj->setIdPropertyType($propertyRow->idPropertyType);
        $propertyObj->setIdAdvertiser($propertyRow->idAdvertiser);
        $propertyObj->setIdHolidayType($propertyRow->idHolidayType);
        $propertyObj->setIdLocation($propertyRow->idLocation);
        $propertyObj->setUrlName($propertyRow->urlName);
        $propertyObj->setShortName($propertyRow->shortName);
        $propertyObj->setLocationUrl($propertyRow->locationUrl);
        $propertyObj->setNumBeds($propertyRow->numBeds);
        $propertyObj->setNumSleeps($propertyRow->numSleeps);
        $propertyObj->setApproved($propertyRow->approved);
        $propertyObj->setVisible($propertyRow->visible);
        $propertyObj->setEmailAddress($propertyRow->emailAddress);
        $propertyObj->setPhotoLimit($propertyRow->photoLimit);
        $propertyObj->setAdded($propertyRow->added);
        $propertyObj->setUpdated($propertyRow->updated);
        $propertyObj->setAwaitingApproval($propertyRow->awaitingApproval);
        $propertyObj->setChangesMade($propertyRow->changesMade);
        $propertyObj->setStatus($propertyRow->status);
        $propertyObj->setChecksumMaster($propertyRow->checksumMaster);
        $propertyObj->setChecksumUpdate($propertyRow->checksumUpdate);
        $propertyObj->setLastModifiedBy($propertyRow->lastModifiedBy);

        // set the property content
        foreach ($propertyContent as $row) {
            switch ($row['idPropertyContentField']) {
                case Common_Resource_PropertyContent::FIELD_LOCATION_URL:
                    $propertyObj->setClocationUrl($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_META_DATA:
                    $propertyObj->setMetaData($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_SEO_DATA:
                    $propertyObj->setSeoData($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_WEBSITE:
                    $propertyObj->setWebsite($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_HEADLINE_1:
                    $propertyObj->setHeadline1($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_HEADLINE_2:
                    $propertyObj->setHeadline2($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_SUMMARY:
                    $propertyObj->setSummary($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_DESCRIPTION:
                    $propertyObj->setDescription($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_BEDROOM_DESC:
                    $propertyObj->setBedroomDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_BATHROOM_DESC:
                    $propertyObj->setBathroomDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_KITCHEN_DESC:
                    $propertyObj->setKitchenDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_UTILITY_DESC:
                    $propertyObj->setUtilityDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_LIVING_DESC:
                    $propertyObj->setLivingDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_OTHER_DESC:
                    $propertyObj->setOtherDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_SERVICE_DESC:
                    $propertyObj->setServiceDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_NOTES_DESC:
                    $propertyObj->setNotesDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_ACCESS_DESC:
                    $propertyObj->setAccessDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_OUTSIDE_DESC:
                    $propertyObj->setOutsideDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_GOLF_DESC:
                    $propertyObj->setGolfDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_SKIING_DESC:
                    $propertyObj->setSkiingDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_SPECIAL_DESC:
                    $propertyObj->setSpecialDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_BEACH_DESC:
                    $propertyObj->setBeachDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_TRAVEL_DESC:
                    $propertyObj->setTravelDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_BOOKING_DESC:
                    $propertyObj->setBookingDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_TESTIMONIALS_DESC:
                    $propertyObj->setTestimonialsDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_CHANGEOVER_DESC:
                    $propertyObj->setChangeoverDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_CONTACT_DESC:
                    $propertyObj->setContactDesc($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_COUNTRY:
                    $propertyObj->setCountry($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_REGION:
                    $propertyObj->setRegion($row['content']);
                    break;
                case Common_Resource_PropertyContent::FIELD_LOCATION:
                    $propertyObj->setLocation($row['content']);
                    break;
                default:
                    throw new Exception("Invalid idPropertyContentField");
            }
        }

        $propertyObj->addCalendar($calendarObj);

        $photoRowset = $this->getResource('Photo')->getPhotoByPropertyId($idProperty);
        foreach ($photoRowset as $photoRow) {
            $photoObj = new Frontend_Model_Photo(
                $photoRow->idPhoto,
                $photoRow->approved,
                $photoRow->displayPriority,
                $photoRow->originalFilename,
                $photoRow->fileType,
                $photoRow->widthPixels,
                $photoRow->heightPixels,
                $photoRow->sizeK,
                $photoRow->caption,
                $photoRow->visible,
                $photoRow->added,
                $photoRow->updated,
                $photoRow->lastModifiedBy
            );

            $propertyObj->addPhoto($photoObj);
        }

        return $propertyObj;
    }
}
