<?php
class Kwc_Basic_Image_CacheParentImage_ParentImage_Component extends Kwc_Basic_ImageParent_Component
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        $ret['dimension'] =array(
            'text' => 'default',
            'width' => 20,
            'height' => 0,
            'cover' => true,
        );
        return $ret;
    }
}
