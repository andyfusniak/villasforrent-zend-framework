<?php
interface Frontend_Plugin_Cron_CronInterface
{
    public function __construct($args = null);

    /**
     * Run the cron task
     *
     * @return void
     * @throws Blahg_Plugin_Cron_Exception to describe any errors that should be returned to the user
     */
    public function run();
}
