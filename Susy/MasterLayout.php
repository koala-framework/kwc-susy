<?php
class Susy_MasterLayout extends Kwf_Component_MasterLayout_Abstract
{
    protected $_layoutName;

    protected function _init()
    {
        parent::_init();
        if (!isset($this->_layoutName)) {
            $this->_layoutName = $this->_settings['layoutName'];
        }
    }

    protected function _build()
    {
        Susy_Helper::_build();
    }

    public function calcSupportedContexts()
    {
        $layouts = Susy_Helper::getLayouts();
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
    public function calcSupportedBoxContexts()
    {
        $layouts = Susy_Helper::getLayouts();
        $ret = array();
        foreach ($layouts[$this->_layoutName] as $breakpointName=>$layout) {
            foreach ($layout['box-spans'] as $boxName=>$spans) {
                $ret[$boxName][] = array(
                    'masterLayout' => $this->_layoutName,
                    'breakpoint' => $breakpointName,
                    'spans' => $spans,
                );
            }
        }
        return $ret;
    }

    public function getContexts(Kwf_Component_Data $data)
    {
        $layouts = Susy_Helper::getLayouts();
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
        $layouts = Susy_Helper::getLayouts();
        $ret = array();
        foreach ($layouts[$this->_layoutName] as $breakpointName=>$layout) {
            if (isset($layout['column-width'])) {
                $columnWidth = (int)$layout['column-width'];
            } else if (isset($layout['breakpoint'])) {
                $columnWidth = (int)$layout['breakpoint'] / $layout['columns']; //TODO not correct
            } else {
                //no breakpoint, no column-width: that's most probably mobile and that won't be max anyway. ignore it.
                continue;
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
