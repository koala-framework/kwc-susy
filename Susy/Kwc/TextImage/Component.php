<?php
class Susy_Kwc_TextImage_Component extends Kwc_TextImage_Component
{
    public static function getSettings($param = null)
    {
        $ret = parent::getSettings($param);
        $ret['generators']['child']['component']['image'] = 'Susy_Kwc_TextImage_ImageEnlarge_Component';
        $ret['layoutClass'] = 'Susy_Kwc_TextImage_Layout';
        $ret['ownModel'] = 'Susy_Kwc_TextImage_Model';
        return $ret;
    }

    public function getTemplateVars(Kwf_Component_Renderer_Abstract $renderer = null)
    {
        $ret = parent::getTemplateVars($renderer);
        foreach ($this->getMasterLayoutContexts() as $c) {
            $ret['rootElementClass'] .= " kwfUp-$c[masterLayout]-$c[breakpoint]-spans$c[spans]";
        }
        $row = $this->getRow();
        if ($row->image && $this->getData()->getChildComponent('-image')->hasContent()) {
            $ret['rootElementClass'] .= " ".$this->_getBemClass('--imagewidth-'.$row->image_width);
        }
        return $ret;
    }
}
