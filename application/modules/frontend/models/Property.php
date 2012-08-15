<?php
class Frontend_Model_Property
{
    private $idProperty = null;
    private $idPropertyType;
    private $idAdvertiser;
    private $idHolidayType;
    private $idLocation;
    private $urlName;
    private $shortName;
    private $locationUrl;
    private $numBeds;
    private $numSleeps;
    private $approved;
    private $visible;
    private $expiry;
    private $emailAddress;
    private $photoLimit;
    private $added;
    private $updated;
    private $awaitingApproval;
    private $changesMade;
    private $status;
    private $checksumMaster;
    private $checksumUpdate;
    private $lastModifiedBy;

    private $cLocationUrl;
    private $metaData;
    private $seoData;
    private $website;
    private $headline1;
    private $headiine2;
    private $summary;
    private $description;
    private $bedroomDesc;
    private $bathroomDesc;
    private $kitchenDesc;
    private $utilityDesc;
    private $livingDesc;
    private $otherDesc;
    private $serviceDesc;
    private $notesDesc;
    private $accessDesc;
    private $outsideDesc;
    private $golfDesc;
    private $skiingDesc;
    private $specialDesc;
    private $beachDesc;
    private $travelDesc;
    private $bookingDesc;
    private $testimonialsDesc;
    private $changeoverDesc;
    private $contactDesc;
    private $country;
    private $region;
    private $location;

    private $calendars = array();
    private $photos = array();

    public function setIdProperty($idProperty)
    {
        $this->idProperty = (int) $idProperty;
        return $this;
    }

    public function getIdProperty()
    {
        return $this->idProperty;
    }

    public function setIdPropertyType($idPropertyType)
    {
        $this->idPropertyType = (int) $idPropertyType;
        return $this;
    }

    public function getIdPropertyType()
    {
        return $this->idPropertyType;
    }

    public function setIdAdvertiser($idAdvertiser)
    {
        $this->idAdvertiser = $idAdvertiser;
        return $this;
    }

    /**
     * @return int the advertiser id
     */
    public function getIdAdvertiser()
    {
        return $this->idAdvertiser;
    }

    public function setIdHolidayType($idHolidayType)
    {
        $this->idHolidayType = $idHolidayType;
        return $this;
    }

    public function getIdHolidayType()
    {
        return $this->idHolidayType;
    }

    public function setIdLocation($idLocation)
    {
        $this->idLocation = $idLocation;
        return $this;
    }

    public function getIdLocation()
    {
        return $this->idLocation;
    }

    public function setUrlName($urlName)
    {
        $this->urlName = $urlName;
        return $this;
    }

    public function getUrlName()
    {
        return $this->urlName;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setLocationUrl($locationUrl)
    {
        $this->locationUrl = $locationUrl;
        return $this;
    }

    public function getLocationUrl()
    {
        return $this->locationUrl;
    }

    public function setNumBeds($numBeds)
    {
        $this->numBeds = $numBeds;
        return $this;
    }

    public function getNumBeds()
    {
        return $this->numBeds;
    }

    public function setNumSleeps($numSleeps)
    {
        $this->numSleeps = $numSleeps;
        return $this;
    }

    public function getNumSleeps()
    {
        return $this->numSleeps;
    }

    public function setApproved($approved)
    {
        $this->approved = $approved;
        return $this;
    }

    public function getApproved()
    {
        return $this->approved;
    }

    public function setVisible($visible)
    {
        $this->visible = $visible;
        return $this;
    }

    public function getVisible()
    {
        return $this->visible;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setPhotoLimit($photoLimit)
    {
        $this->photoLimit = $photoLimit;
        return $this;
    }

    public function getPhotoLimit()
    {
        return $this->photoLimit;
    }

    public function setAdded($added)
    {
        $this->added = $added;
        return $this;
    }

    public function getAdded()
    {
        return $this->added;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setAwaitingApproval($awaitingApproval)
    {
        $this->awaitingApproval = $awaitingApproval;
        return $this;
    }

    public function getAwaitingApproval()
    {
        return $this->awaitingApproval;
    }

    public function setChangesMade($changesMade)
    {
        $this->changesMade = $changesMade;
        return $this;
    }

    public function getChangesMade()
    {
        return $this->changesMade;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setChecksumMaster($checksumMaster)
    {
        $this->checksumMaster = $checksumMaster;
        return $this;
    }

    public function getChecksumMaster()
    {
        return $this->checksumMaster;
    }

    public function setChecksumUpdate($checksumUpdate)
    {
        $this->checksumUpdate = $checksumUpdate;
        return $this;
    }

    public function getChecksumUpdate()
    {
        return $this->checksumUpdate;
    }

    public function setLastModifiedBy($lastModifiedBy)
    {
        $this->lastModifiedBy = $lastModifiedBy;
        return $this;
    }

    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }

    public function setClocationUrl($locationUrl)
    {
        $this->cLocationUrl = $locationUrl;
        return $this;
    }

    public function getClocationUrl()
    {
        return $this->cLocationUrl;
    }

    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
        return $this;
    }

    public function getMetaData()
    {
        return $this->metaData;
    }

    public function setSeoData($seoData)
    {
        $this->seoData = $seoData;
        return $this;
    }

    public function getSeoData()
    {
        return $this->seoData;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setHeadline1($headline1)
    {
        $this->headline1 = $headline1;
        return $this;
    }

    public function getHeadline1()
    {
        return $this->headline1;
    }

    public function setHeadline2($headiine2)
    {
        $this->headiine2 = $headiine2;
        return $this;
    }

    public function getHeadline2()
    {
        return $this->headiine2;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setBedroomDesc($bedroomDesc)
    {
        $this->bedroomDesc = $bedroomDesc;
        return $this;
    }

    public function getBedroomDesc()
    {
        return $this->bathroomDesc;
    }

    public function setBathroomDesc($bathroomDesc)
    {
        $this->bathroomDesc = $bathroomDesc;
        return $this;
    }

    public function getBathroomDesc()
    {
        return $this->bathroomDesc;
    }

    public function setKitchenDesc($kitchenDesc)
    {
        $this->kitchenDesc = $kitchenDesc;
        return $this;
    }

    public function getKitchenDesc()
    {
        return $this->kitchenDesc;
    }

    public function setUtilityDesc($utilityDesc)
    {
        $this->utilityDesc = $utilityDesc;
        return $this;
    }

    public function getUtilityDesc()
    {
        return $this->utilityDesc;
    }

    public function setLivingDesc($livingDesc)
    {
        $this->livingDesc = $livingDesc;
        return $this;
    }

    public function getLivingDesc()
    {
        return $this->livingDesc;
    }

    public function setOtherDesc($otherDesc)
    {
        $this->otherDesc = $otherDesc;
        return $this;
    }

    public function getOtherDesc()
    {
        return $this->otherDesc;
    }

    public function setServiceDesc($serviceDesc)
    {
        $this->serviceDesc = $serviceDesc;
        return $this;
    }

    public function getServiceDesc()
    {
        return $this->serviceDesc;
    }

    public function setNotesDesc($notesDesc)
    {
        $this->notesDesc = $notesDesc;
        return $this;
    }

    public function getNotesDesc()
    {
        return $this->notesDesc;
    }

    public function setAccessDesc($accessDesc)
    {
        $this->accessDesc = $accessDesc;
        return $this;
    }

    public function getAccessDesc()
    {
        return $this->accessDesc;
    }

    public function setOutsideDesc($outsideDesc)
    {
        $this->outsideDesc = $outsideDesc;
        return $this;
    }

    public function getOutsideDesc()
    {
        return $this->outsideDesc;
    }

    public function setGolfDesc($golfDesc)
    {
        $this->golfDesc = $golfDesc;
        return $this;
    }

    public function getGolfDesc()
    {
        return $this->golfDesc;
    }

    public function setSkiingDesc($skiingDesc)
    {
        $this->skiingDesc = $skiingDesc;
        return $this;
    }

    public function getSkiingDesc()
    {
        return $this->skiingDesc;
    }

    public function setSpecialDesc($specialDesc)
    {
        $this->specialDesc = $specialDesc;
        return $this;
    }

    public function getSpecialDesc()
    {
        return $this->specialDesc;
    }

    public function setBeachDesc($beachDesc)
    {
        $this->beachDesc = $beachDesc;
        return $this;
    }

    public function getBeachDesc()
    {
        return $this->beachDesc;
    }

    public function setTravelDesc($travelDesc)
    {
        $this->travelDesc = $travelDesc;
        return $this;
    }

    public function getTravelDesc()
    {
        return $this->travelDesc;
    }

    public function setBookingDesc($bookingDesc)
    {
        $this->bookingDesc = $bookingDesc;
        return $this;
    }

    public function getBookingDesc()
    {
        return $this->bookingDesc;
    }

    public function setTestimonialsDesc($testimonialsDesc)
    {
        $this->testimonialsDesc = $testimonialsDesc;
        return $this;
    }

    public function getTestimonialsDesc()
    {
        return $this->testimonialsDesc;
    }

    public function setChangeoverDesc($changeoverDesc)
    {
        $this->changeoverDesc = $changeoverDesc;
        return $this;
    }

    public function getChangeoverDesc()
    {
        return $this->changeoverDesc;
    }

    public function setContactDesc($contactDesc)
    {
        $this->contactDesc = $contactDesc;
        return $this;
    }

    public function getContactDesc()
    {
        return $this->contactDesc;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function addCalendar(Frontend_Model_Calendar $calendar)
    {
        array_push($this->calendars, $calendar);
    }

    public function getCalendar($idx)
    {
        if (isset($this->calendars[$idx])) {
            return $this->calendars[$idx];
        } else {
            throw new Exception("Calendar of index " . $idx . " does not exist in the list");
        }
    }

    public function getCalendars()
    {
        return $this->calendars;
    }

    public function addPhoto(Frontend_Model_Photo $photo)
    {
        array_push($this->photos, $photo);
    }

    public function getPhoto($idx)
    {
        if (isset($this->photos[$idx])) {
            return $this->photos[$idx];
        } else {
            throw new Exception("Photo of index " . $idx . " does not exist in the list");
        }
    }

    public function getPhotoList()
    {
        return $this->photos;
    }
}
