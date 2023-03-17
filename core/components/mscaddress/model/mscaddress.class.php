<?php

class mscAddress
{
    public $version = '1.0.2-beta';
	
    /** @var modX $modx */
    public $modx;
	
    /** @var array $initialized */
    public $initialized = [];

    /** @var miniShop2 $miniShop2 */
    public $ms2;
	
    /** @var pdoFetch $pdoTools */
    public $pdoTools;
	
    /** @var array $session */
    public $session = [];

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/mscaddress/';
        $assetsUrl = MODX_ASSETS_URL . 'components/mscaddress/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'customPath' => $corePath . 'custom/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
			'actionUrl' => $assetsUrl . 'action.php',
			'hash_key' => '',
			
            'json_response' => false,
        ], $config);

        $this->modx->addPackage('mscaddress', $this->config['modelPath']);
        $this->modx->lexicon->load('mscaddress:default');
		
		
		$this->session = & $_SESSION['msca'];
        if (empty($this->session) || !is_array($this->session)) {
            $this->session = [];
        }
		
        $this->ms2 = $modx->getService('miniShop2');
        if (!($this->ms2 instanceof miniShop2)) {
            return false;
        }
        $this->pdoTools =& $this->ms2->pdoTools;
    }

	
    public function initialize($ctx = 'web', $scriptProperties = [])
    {
        if (isset($this->initialized[$ctx])) {
            return $this->initialized[$ctx];
        }
        $this->config = array_merge($this->config, $scriptProperties);
        $this->config['ctx'] = $ctx;

        if ($ctx != 'mgr' && (!defined('MODX_API_MODE') || !MODX_API_MODE)) {
            $config = $this->pdoTools->makePlaceholders($this->config);

            // Register CSS
            $css = trim($this->modx->getOption('msca_frontend_css'));
            if (!empty($css) && preg_match('/\.css/i', $css)) {
                if (preg_match('/\.css$/i', $css)) {
                    $css .= '?v=' . substr(md5($this->version), 0, 10);
                }
                $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
            }

            // Register JS
            $js = trim($this->modx->getOption('msca_frontend_js'));
            if (!empty($js) && preg_match('/\.js/i', $js)) {
                if (preg_match('/\.js$/i', $js)) {
                    $js .= '?v=' . substr(md5($this->version), 0, 10);
                }
                $this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));

                $data = json_encode([
                    'cssUrl' => $this->config['cssUrl'] . 'web/',
                    'jsUrl' => $this->config['jsUrl'] . 'web/',
                    'actionUrl' => $this->config['actionUrl'],
                    'hash_key' => $this->config['hash_key'],
                    'ctx' => $ctx,
                ], true);
                $this->modx->regClientStartupScript(
                    '<script type="text/javascript">mscaConfig = ' . $data . ';</script>', true
                );
            }
        }
		$this->ms2->initialize($ctx, $scriptProperties);
        $load = $this->loadHandlers($ctx);
        $this->initialized[$ctx] = $load;
		
        return $load;
    }
	
	/**
     * Handle frontend requests with actions
     *
     * @param $action
     * @param array $data
     *
     * @return array|bool|string
     */
    public function handleRequest($action, $data =[])
    {
        $ctx = !empty($data['ctx'])
            ? (string)$data['ctx']
            : 'web';
        if ($ctx != 'web') {
            $this->modx->switchContext($ctx);
        }
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		$tmp = ['json_response' => $isAjax];
		if (!empty($data['hash_key'])){
			$tmp['hash_key'] = $data['hash_key'];
		}
        $this->initialize($ctx, $tmp);

        switch ($action) {
			case 'address/add':
				$response = $this->address->get();
				break;
			case 'address/edit':
				$response = $this->address->get(@$data['id']);
				break;
			case 'address/save':
				$response = $this->address->save(@$data['id'], $data);
				break;
			case 'address/remove':
				$response = $this->address->remove(@$data['id']);
				break;
			case 'order/values':
				$values = $this->ms2->order->get();
				unset($values['msca_address'],$values['delivery'],$values['payment']);
				$response = $this->success('', $values);
				break;
			default:
				$response = $this->error('msca_err_unknown');
		}

        return $response;
    }

	/**
     * @param string $ctx
     *
     * @return bool
     */
    public function loadHandlers($ctx = 'web')
    {
        // Default classes
        if (!class_exists('mscaAddressHandler')) {
            require_once dirname(__FILE__) . '/mscaddress/mscaaddresshandler.class.php';
        }

        // Custom address class
        $address = $this->modx->getOption('msca_address_handler', null, 'mscaAddressHandler');
        if ($address != 'mscaAddressHandler') {
			$files = scandir($this->config['customPath']);
			foreach ($files as $file) {
				if (preg_match('/.*?\.class\.php$/i', $file)) {
					include_once($this->config['customPath'] . '/address/' . $file);
				}
			}
        }

        $this->address = new $address($this, $this->config);
        if (!($this->address instanceof mscaAddressInterface) || $this->address->initialize($ctx) !== true) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,
                'Could not initialize mscAddress address handler class: "' . $address . '"');

            return false;
        }

        return true;
    }
	
    /**
     * This method returns an error
     *
     * @param string $message A lexicon key for error message
     * @param array $data .Additional data
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     */
    public function error($message = '', $data = [], $placeholders = [])
    {
        $response = [
            'success' => false,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        ];

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }

    /**
     * This method returns an success
     *
     * @param string $message A lexicon key for success message
     * @param array $data .Additional data
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     */
    public function success($message = '', $data = [], $placeholders = [])
    {
        $response = [
            'success' => true,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        ];

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }
}