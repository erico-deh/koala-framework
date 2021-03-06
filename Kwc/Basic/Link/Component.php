<?php
class Kwc_Basic_Link_Component extends Kwc_Abstract_Composite_Component
{
    public static function getSettings()
    {
        $ret = array_merge(parent::getSettings(), array(
            'ownModel' => 'Kwc_Basic_Link_Model',
            'componentName' => trlKwfStatic('Link'),
            'componentIcon' => 'page_white_link',
            'default' => array(),
        ));
        $ret['componentCategory'] = 'content';
        $ret['generators']['child']['component'] = array(
            'linkTag' => 'Kwc_Basic_LinkTag_Component',
        );
        $ret['flags']['searchContent'] = true;
        $ret['flags']['hasFulltext'] = true;
        $ret['throwHasContentChangedOnRowColumnsUpdate'] = 'text';
        return $ret;
    }

    public function getTemplateVars(Kwf_Component_Renderer_Abstract $renderer = null)
    {
        $ret = parent::getTemplateVars($renderer);
        $ret['text'] = $this->_getRow()->text;
        if (!$this->hasContent($ret['linkTag'])) $ret['rootElementClass'] .= ' emptyLink';
        return $ret;
    }

    public function getSearchContent()
    {
        return $this->_getRow()->text;
    }

    public function getFulltextContent()
    {
        $ret = array();
        $text = $this->_getRow()->text;
        $ret['content'] = $text;
        $ret['normalContent'] = $text;
        return $ret;
    }

    public function hasContent()
    {
        if (!$this->_getRow()->text) return false;
        return parent::hasContent();
    }
}
