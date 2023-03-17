<?php

class mscaOnHandleRequest extends mscaPlugin
{
    public function run()
    {
		// Handle ajax requests
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        if (empty($_REQUEST['msca_action']) || !$isAjax) {
            return;
        }
		
		$response = $this->msca->handleRequest($_REQUEST['msca_action'], @$_POST);
		@session_write_close();
		exit($response);
    }
}