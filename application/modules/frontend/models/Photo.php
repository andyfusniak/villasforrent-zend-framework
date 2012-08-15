<?php
class Frontend_Model_Photo
{
    private $idPhoto = null;
    private $approved;
    private $displayPriority;
    private $originalFilename;
    private $fileType;
    private $widthPixels;
    private $heightPixels;
    private $sizeK;
    private $caption;
    private $visible;
    private $added;
    private $updated;
    private $lastModifiedBy;

    public function __construct($idPhoto, $approved, $displayPriority, $originalFilename, $fileType,
        $widthPixels, $heightPixels, $sizeK, $caption, $visible, $added, $updated, $lastModifiedBy)
    {
        $this->idPhoto = (int) $idPhoto;
        $this->approved = (int) $approved;
        $this->displayPriority = (int) $displayPriority;
        $this->originalFilename = $originalFilename;
        $this->fileType = $fileType;
        $this->widthPixels = (int) $widthPixels;
        $this->heightPixels = (int) $heightPixels;
        $this->sizeK = (int) $sizeK;
        $this->caption = $caption;
        $this->visible = (int) $visible;
        $this->added = $added;
        $this->updated = $updated;
        $this->lastModifiedBy = $lastModifiedBy;
    }

    public function setIdPhoto($idPhoto)
    {
        $this->idPhoto = (int) $idPhoto;
    }

    public function getIdPhoto()
    {
        return $this->idPhoto;
    }

    public function setApproved($approved)
    {
        $this->approved = (int) $approved;
    }

    public function getApproved()
    {
        return $this->approved;
    }

    public function setDisplayPriority($displayPriority)
    {
        $this->displayPriority = (int) $displayPriority;
    }

    public function getDisplayPriority()
    {
        return $this->displayPriority;
    }

    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;
    }

    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    public function getFileType()
    {
        return $this->fileType;
    }

    public function setWidthPixels($widthPixels)
    {
        $this->widthPixels = (int) $widthPixels;
    }

    public function getWidthPixels()
    {
        return $this->widthPixels;
    }

    public function setHeightPixels($heightPixels)
    {
        $this->heightPixels = (int) $heightPixels;
    }

    public function getHeightPixels()
    {
        return $this->heightPixels;
    }

    public function setSizeK($sizeK)
    {
        $this->sizeK = (int) $sizeK;
    }

    public function getSizeK()
    {
        return $this->sizeK;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    public function getCaption()
    {
        return $this->caption;
    }


    public function setVisible($visible)
    {
        $this->visible = (int) $visible;
    }

    public function getVisible()
    {
        return $this->visible;
    }

    public function setAdded($added)
    {
        $this->added = $added;
    }

    public function getAdded()
    {
        return $this->added;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setLastModifiedBy($lastModifiedBy)
    {
        $this->lastModifiedBy = $lastModifiedBy;
    }

    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }
}
