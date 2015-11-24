<?php
class Susy_Kwc_TextImage_Component extends Kwc_TextImage_Component
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        $ret['generators']['child']['component']['image'] = 'Susy_Kwc_TextImage_ImageEnlarge_Component';
        $ret['layoutClass'] = 'Susy_Kwc_TextImage_Layout';
        return $ret;
    }

    public function getTemplateVars()
    {
        $ret = parent::getTemplateVars();
        foreach ($this->getMasterLayoutContexts() as $c) {
            $ret['rootElementClass'] .= " kwfUp-$c[masterLayout]-$c[breakpoint]-spans$c[spans]";
        }
        $ret['rootElementClass'] .= " ".$this->_getBemClass('--imagewidth-'.$this->getRow()->image_width);
        return $ret;
    }
}
