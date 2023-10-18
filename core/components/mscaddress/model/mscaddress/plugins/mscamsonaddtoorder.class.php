<?php

class mscaMsOnAddToOrder extends mscaPlugin
{
    public function run()
    {
        $key = $this->modx->getOption('key', $this->scriptProperties);
		$value = $this->modx->getOption('value', $this->scriptProperties);
		
		if ($key == 'msca_address' && !empty($value)) {
			if (!$this->modx->user->isAuthenticated()) {
				return;
			}
			if (!$address = $this->modx->getObject('msCustomerAddress', ['id' => (int)$value, 'user_id' => $this->modx->user->get('id')])) {
				return;
			}
			$order = $this->modx->getOption('order', $this->scriptProperties);
			
			$fields = $this->modx->map['msCustomerAddress']['fields'];
			unset($fields['id'],$fields['title'],$fields['user_id'],$fields['rank']);
			
			foreach (array_keys($fields) as $field) {
				if ($addr_value = $address->get($field)) {
					if ($field != 'properties') {
						$order->add($field, $addr_value);
					} else {
						foreach ($addr_value as $prop_key => $prop_value) {
							$order->add($prop_key, $prop_value);
						}
					}
				}
			}
		}
    }
}