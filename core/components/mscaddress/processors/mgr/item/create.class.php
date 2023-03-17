<?php

class mscAddressItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'mscAddressItem';
    public $classKey = 'mscAddressItem';
    public $languageTopics = ['mscaddress'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('mscaddress_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('mscaddress_item_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'mscAddressItemCreateProcessor';