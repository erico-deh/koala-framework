<?php
class Kwc_Trl_ImageEnlarge_ImageEnlarge_EnlargeTag_Trl_TestModel extends Kwf_Model_Proxy
{
    public function __construct()
    {
        $config['proxyModel'] = new Kwf_Model_FnFFile(array(
            'primaryKey' => 'component_id',
            'uniqueIdentifier' => get_class($this).'-Proxy'
        ));
        parent::__construct($config);
    }
}
