<?php
class Vfr_View_Helper_SummaryBar extends Zend_View_Helper_Abstract
{
    const version = '1.0.0';

    public function summaryBar($propertyRow)
    {
        $idProperty = $propertyRow->idProperty;
        $status     = $propertyRow->status;
        $approved   = $propertyRow->approved;

        //var_dump($idProperty, $status, $approved);

        $progressions = array(
            'location',
            'content',
            'pictures',
            'rates',
            'availability'
        );
        $xhtml = '<ul class="summary_bar">';

        if (!$approved) {
            $colour = '_orange';

            $reverseCount = $status;
            foreach ($progressions as $item) {
                if ($reverseCount < 2)
                    $colour = '_grey';

                if ($item == 'availability') {
                    $xhtml .= '<li class="' . $item . $colour . ' end"></li>';
                } else {
                    $xhtml .= '<li class="' . $item . $colour . ' normal"></li>';
                }
                $reverseCount--;
            }

        } else {
            foreach ($progressions as $item) {
                switch ($item)
                {
                    case 'content':
                        $updateContentLnk = '<a class="summary_link" href="'
                            . $this->view->url(array(
                                'module'     => 'controlpanel',
                                'controller' => 'property',
                                'action'     => 'step2-content',
                                'idProperty' => $propertyRow->idProperty,
                                'mode'       => 'update',
                                'digestKey'  => Vfr_DigestKey::generate(array(
                                    $propertyRow->idProperty,
                                    'update'
                                ))
                            )) . '">[edit]</a>';

                        if (($propertyRow->checksumMaster !== $propertyRow->checksumUpdate) &&
                            ($propertyRow->awaitingApproval == 0)) {
                            $sendForUpdateApprovalLnk = '<a class="summary_link2" href="'
                                . $this->view->url(array(
                                    'module'     => 'controlpanel',
                                    'controller' => 'property',
                                    'action'     => 'send-for-update-approval',
                                    'idProperty' => $propertyRow->idProperty,
                                    'digestKey'  => Vfr_DigestKey::generate(array(
                                        $propertyRow->idProperty
                                    ))
                                )) . '">[send]</a>';

                            $xhtml .= '<li class="content_orange normal">' . $updateContentLnk . $sendForUpdateApprovalLnk . '</li>';
                        } elseif (($propertyRow->checksumMaster !== $propertyRow->checksumUpdate) &&
                                  ($propertyRow->awaitingApproval == 1)) {
                            $xhtml .= '<li class="content_orange normal"><p class="summary_label">Awaiting update</p></li>';
                        } else {
                            $xhtml .= '<li class="content_green normal">' . $updateContentLnk . '</li>';
                        }
                        break;

                    case 'pictures':
                        $updatePhotoLnk = '<a class="summary_link" href="'
                            . $this->view->url(array(
                                'module'     => 'controlpanel',
                                'controller' => 'property',
                                'action'     => 'step3-pictures',
                                'idProperty' => $propertyRow->idProperty,
                                'digestKey'  => Vfr_DigestKey::generate(array(
                                    $propertyRow->idProperty
                                ))
                            )) . '">[edit]</a>';

                        $xhtml .= '<li class="pictures_green normal">' . $updatePhotoLnk . '</li>';
                        break;

                    case 'rates':
                        $updateRatesLnk = '<a class="summary_link" href="'
                            . $this->view->url(array(
                                'module'     => 'controlpanel',
                                'controller' => 'property',
                                'action'     => 'step4-rates',
                                'idProperty' => $propertyRow->idProperty,
                                'digestKey'  => Vfr_DigestKey::generate(array(
                                    $propertyRow->idProperty
                                ))
                            )) . '">[edit]</a>';

                        $xhtml .= '<li class="rates_green normal">' . $updateRatesLnk . '</li>';
                        break;

                    case 'availability':
                        $updateAvailabilityLnk = '<a class="summary_link" href="'
                            . $this->view->url(array(
                                'module'     => 'controlpanel',
                                'controller' => 'property',
                                'action'     => 'step5-availability',
                                'idProperty' => $propertyRow->idProperty,
                                'digestKey'  => Vfr_DigestKey::generate(array(
                                    $propertyRow->idProperty
                                ))
                            )) . '">[edit]</a>';

                        $xhtml .= '<li class="availability_green end">' . $updateAvailabilityLnk . '</li>';
                        break;

                    default:
                        $xhtml .= '<li class="' . $item . '_green normal"></li>';
                }
            }
        }

        $xhtml .= '</ul>';

        return $xhtml;
    }
}
