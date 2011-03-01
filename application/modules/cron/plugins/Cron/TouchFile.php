<?php
class Frontend_Plugin_Cron_TouchFile implements Frontend_Plugin_Cron_CronInterface
{
    protected $_filename;

    public function __construct($args = null)
    {
        if (!is_array($args) || !array_key_exists('filename', $args)) {
            throw new Plugin_Cron_Exception('The FileToucher cron task plugin is not configured correctly.');
        }
        $this->_filename = $args['filename'];
    }

    public function run()
    {
		var_dump("running this cron job task");
        $result = touch($this->_filename);
        if (!$result) {
            throw new Plugin_Cron_Exception('The file timestamp could not be updated.');
        }
    }
}
