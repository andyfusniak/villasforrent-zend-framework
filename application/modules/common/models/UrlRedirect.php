<?php
class Common_Model_UrlRedirect extends Vfr_Model_Abstract
{
    const version = '1.0.0';
    const SESSION_NS_URLREDIRECTION = 'AdminUrlRedirectNS';

    /**
     * @param $incomingUrl the incoming URL
     * @param $redirectUrl the redirection URL
     * @param $responseCode the HTTP response code e.g. 301
     */
    public function createRedirect($incomingUrl, $redirectUrl, $responseCode, $groupName)
    {
        $urlRedirectResource = $this->getResource('UrlRedirect');
        try {
            $idUrlRedirect = $urlRedirectResource->createRedirect(
                $incomingUrl,
                $redirectUrl,
                $responseCode,
                $groupName
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getAllPaginator($page, $interval, $order, $direction)
    {
        $session = new Zend_Session_Namespace(self::SESSION_NS_URLREDIRECTION);

        if (null !== $page)
            $session->page = $page;

        if (null !== $interval)
            $session->interval = $interval;

        if (null !== $order)
            $session->order = $order;

        if (null !== $direction)
            $session->direction = $direction;

        return $this->getResource('UrlRedirect')->getAllPaginator(
            isset($session->page)      ? $session->page : 1,
            isset($session->interval)  ? $session->interval : 10,
            isset($session->order)     ? $session->order : 'idUrlRedirect',
            isset($session->direction) ? $session->direction : 'ASC'
        );
    }

    public function lookupIncomingUrl($url)
    {
        $urlRedirectResource = $this->getResource('UrlRedirect');
        return $urlRedirectResource->lookupIncomingUrl($url);
    }
}
