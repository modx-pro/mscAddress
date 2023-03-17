<?php

class mscaOnLoadWebDocument extends mscaPlugin
{
    public function run()
    {
		// Handle non-ajax requests
        if (!empty($_REQUEST['msca_action'])) {
			$this->msca->handleRequest($_REQUEST['msca_action'], @$_POST);
        }
    }
}