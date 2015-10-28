<?php
class Kwc_Susy_MasterLayout extends Kwf_Component_MasterLayout_Abstract
{
    protected $_layoutName;

    protected function _init()
    {
        parent::_init();
        if (!isset($this->_layoutName)) {
            $this->_layoutName = $this->_settings['layoutName'];
        }
    }

    public function getSupportedContexts()
    {
        $layouts = Kwc_Susy_Helper::getLayouts();
        $ret = array();
        foreach ($layouts[$this->_layoutName] as $breakpointName=>$layout) {
            $ret[] = array(
                'masterLayout' => $this->_layoutName,
                'breakpoint' => $breakpointName,
                'spans' => $layout['content-spans'],
            );
        }
        return $ret;
    }
    public function getSupportedBoxContexts($boxName)
    {
        $layouts = Kwc_Susy_Helper::getLayouts();
        $ret = array();
        foreach ($layouts[$this->_layoutName] as $breakpointName=>$layout) {
            if (isset($layout['box-spans'][$boxName])) {
                $ret[] = array(
                    'masterLayout' => $this->_layoutName,
                    'breakpoint' => $breakpointName,
                    'spans' => $layout['box-spans'][$boxName],
                );
            }
        }
        return $ret;
    }

    public function getContexts(Kwf_Component_Data $data)
    {
        $layouts = Kwc_Susy_Helper::getLayouts();
        $ret = array();
        foreach ($layouts[$this->_layoutName] as $breakpointName=>$layout) {
            if (isset($data->box)) {
                $ret[] = array(
                    'masterLayout' => $this->_layoutName,
                    'breakpoint' => $breakpointName,
                    'spans' => (int)$layout['box-spans'][$data->box]
                );
            } else {
                $ret[] = array(
                    'masterLayout' => $this->_layoutName,
                    'breakpoint' => $breakpointName,
                    'spans' => (int)$layout['content-spans']
                );
            }
        }
        return $ret;
    }

    public function getContentWidth(Kwf_Component_Data $data)
    {
        $layouts = Kwc_Susy_Helper::getLayouts();
        $ret = array();
        foreach ($layouts[$this->_layoutName] as $breakpointName=>$layout) {
            if (isset($layout['column-width'])) {
                $columnWidth = (int)$layout['column-width'];
            } else {
                $columnWidth = (int)$layout['breakpoint'] / $layout['columns']; //TODO not correct
            }
            if (isset($data->box)) {
                $ret[] = $layout['box-spans'][$data->box] * $columnWidth;
            } else {
                $ret[] = $layout['content-spans'] * $columnWidth;
            }
        }
        return max($ret);
    }
}
