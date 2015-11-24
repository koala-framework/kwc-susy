<?php
class Susy_Kwc_Columns_Admin extends Kwc_Admin
{
    public function getScssConfig()
    {
        return array('master-layout-contexts' => Kwf_Component_Layout_Abstract::getInstance($this->_class)->getSupportedContexts());
    }

    public function getScssConfigMtime()
    {
        //TODO
        return null;
    }
}
