<?php

interface mscaAddressInterface
{
    public function initialize($ctx = 'web');

    public function get($id = 0, $data = []);
	
	public function save($id = 0, $data = []);
	
	public function remove($id = 0);
}

class mscaAddressHandler implements mscaAddressInterface
{
    /* @var modX $modx */
    public $modx;
    /* @var mscAddress $msca */
    public $msca;
    /** @var array $config */
    public $config;
	
    protected $ctx = 'web';

    /**
     * msrCodeHandler constructor.
     *
     * @param msRewards $mspc
     * @param array $config
     */
    public function __construct(mscAddress &$msca, array $config = [])
    {
        $this->msca = &$msca;
        $this->modx = &$msca->modx;

        $this->config = array_merge([
		
        ], $config);

    }
	
    /**
     * @param string $ctx
     *
     * @return bool
     */
    public function initialize($ctx = 'web')
    {
        $this->ctx = $ctx;

        return true;
    }

	/**
     * Returns required fields for addresses
     *
     * @return array
     */
    public function getRequiresFields()
    {
        $requires = $this->modx->getOption('msca_requires');
        $requires = empty($requires) ? [] : array_map('trim', explode(',', $requires));
        if (!in_array('title', $requires)) {
            $requires[] = 'title';
        }
        return $requires;
    }
	
	public function get($id = 0, $data = [])
	{
		if (!$this->modx->user->isAuthenticated($this->ctx)) {
			return $this->error('msca_err_auth');
		}
		$pls = [];
		if (!empty($id)){
			if (!$address = $this->modx->getObject('msCustomerAddress', ['id' => $id, 'user_id' => $this->modx->user->get('id')])){
				return $this->error('msca_err_addr_nf');
			}
			$pls = $address->toArray();
		}
		$tplForm = $this->modx->getOption('tplForm', $this->msca->session[$this->config['hash_key']], 'tpl.mscaForm', true);
		
		$errors = [];
		if (isset($data['msca_action']) && $data['msca_action'] == 'address/save'){
			$response = $this->save($id, $data);
			if ($this->config['json_response'])
				$response = json_decode($response, true);
				
			if ($response['success'])
				return $this->success('', ['html' => '']);
				
			$errors = $response['data'];
			$pls = array_merge($pls, $data);
		}
		$requires = $this->getRequiresFields();
		$html = $this->msca->pdoTools->getChunk($tplForm, ['form' => $pls, 'requires' => $requires, 'errors' => $errors]);
		
		return $this->success('', ['html' => $html]);
	}

	public function save($id = 0, $data = [])
	{
		if (!$this->modx->user->isAuthenticated($this->ctx)) {
			return $this->error('msca_err_auth');
		}
		if (!empty($id)){
			if (!$address = $this->modx->getObject('msCustomerAddress', ['id' => $id, 'user_id' => $this->modx->user->get('id')])){
				return $this->error('msca_err_addr_nf');
			}
		}
		else{
			$address = $this->modx->newObject('msCustomerAddress');
			$address->set('rank', $this->modx->getCount('msCustomerAddress', ['user_id' => $this->modx->user->get('id')]));
			$address->set('user_id', $this->modx->user->get('id'));
		}
			
		$requires = $this->getRequiresFields();
		$this->msca->ms2->initialize($this->config['ctx'], ['json_response' => $this->config['json_response']]);
		
		$fields = $this->modx->map['msCustomerAddress']['fields'];
		unset($fields['id'],$fields['user_id'],$fields['rank'],$fields['properties']);
		
		$lex = $fields;
		unset($lex['title'],$lex['properties']);
		$lex = array_keys($lex);
		// Check for errors
		$errors = [];
		foreach ($data as $field => $val) {
			$data[$field] = $this->msca->ms2->order->validate($field, $val);
			if ((in_array($field, $requires) && empty($data[$field]))) {
				$errors[$field] = $this->modx->lexicon('msca_err_field_req', ['field' => $this->modx->lexicon((!in_array($field, $lex) ? 'msca_addr_' : 'ms2_frontend_').$field)]);
			}
		}
		if (!empty($errors))
			return $this->error('', $errors);
			
		
		foreach (array_keys($fields) as $field){
			if (isset($data[$field])){
				$address->set($field, $data[$field]);
				unset($data[$field]);
			}
		}
		unset($data['id'],$data['msca_action'],$data['ctx'],$data['hash_key']);
		
		if (!empty($data)){
			$address->set('properties', $data);
		}
		
		if (!$address->save())
			return $this->error('msca_err_addr_save');
		
		if ($html = $this->getList()){
			return $this->success('', ['html' => $html]);
		}
		else
			return $this->error('msca_err_get_list');
	}

	public function remove($id = 0)
	{
		if (!$this->modx->user->isAuthenticated($this->ctx)) {
			return $this->error('msca_err_auth');
		}
		if (!$address = $this->modx->getObject('msCustomerAddress', ['id' => $id, 'user_id' => $this->modx->user->get('id')])){
			return $this->error('msca_err_addr_nf');
		}
		if (!$address->remove())
			return $this->error('msca_err_addr_remove');
			
		if ($html = $this->getList()){
			return $this->success('', ['html' => $html]);
		}
		else
			return $this->error('msca_err_get_list');
	}
	
	protected function getList()
	{
		if( !empty($this->config['hash_key']) && $snippet = $this->modx->getObject('modSnippet', ['name'=>'mscAddress'])){
			$properties = $snippet->getProperties();
			$scriptProperties = array_merge($properties, $this->msca->session[$this->config['hash_key']]);
			return $snippet->process($scriptProperties);
		}
		return false;
	}
	
	/**
     *
     * @param string $message
     * @param array $data
     * @param array $placeholders
     *
     * @return array|string
     */
    public function error($message = '', $data = [], $placeholders = [])
    {
        return $this->msca->error($message, $data, $placeholders);
    }
	
    /**
     *
     * @param string $message
     * @param array $data
     * @param array $placeholders
     *
     * @return array|string
     */
    public function success($message = '', $data = [], $placeholders = [])
    {
        return $this->msca->success($message, $data, $placeholders);
    }
}