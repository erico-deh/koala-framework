<?php
class Kwc_Lightbox_Content_Root extends Kwf_Component_NoCategoriesRoot
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        unset($ret['generators']['page']);
        unset($ret['generators']['title']);
        unset($ret['generators']['box']);
        $ret['generators']['test'] = array(
            'class' => 'Kwf_Component_Generator_Page_Static',
            'component' => 'Kwc_Lightbox_Content_Test2_Component'
        );
        return $ret;
    }
}
