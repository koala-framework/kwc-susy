<?php
class Susy_Kwc_TextImage_Admin extends Kwc_Abstract_Composite_Admin
{
    public function getScssConfig()
    {
        return array('master-layout-contexts' => Kwf_Component_Layout_Abstract::getInstance($this->_class)->getSupportedContexts());
    }

    public function getScssConfigMasterFiles()
    {
        return Kwf_Component_Layout_Abstract::getInstance($this->_class)->getSupportedContextsMasterFiles();
    }
}
