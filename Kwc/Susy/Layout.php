<?php
class Kwc_Susy_Layout extends Kwf_Component_Layout_Abstract
{
    private static function _whoCreates($class)
    {
        static $cache = array();
        if (isset($cache[$class])) {
            return $cache[$class];
        }
        $cache[$class] = array();
        foreach (Kwc_Abstract::getComponentClasses() as $c) {
            foreach (Kwc_Abstract::getSetting($c, 'generators') as $genKey => $genSettings) {
                foreach ($genSettings['component'] as $k=>$i) {
                    if ($i === $class) {
                        $g = Kwf_Component_Generator_Abstract::getInstance($c, $genKey, $genSettings);
                        if ($g->getGeneratorFlag('box')) {
                            $boxes = $g->getBoxes();
                            if (count($boxes) > 1) {
                                $cache[$class][] = array(
                                    'box' => $k,
                                    'component' => $c,
                                    'generator' => $genKey
                                );
                            } else {
                                $cache[$class][] = array(
                                    'box' => $boxes[0],
                                    'component' => $c,
                                    'generator' => $genKey
                                );
                            }
                        } else {
                            $cache[$class][] = array(
                                'component' => $c,
                                'generator' => $genKey
                            );
                        }
                    }
                }
            }
        }
        return $cache[$class];
    }

    private function _findParentsStack($class, $stack = array())
    {
        $ret = array();
        foreach (self::_whoCreates($class) as $c) {
            foreach ($stack as $s) {
                if ($s['component'] == $c['component']) continue 2; //don't support columns in columns
            }
            if ($c['component'] == $this->_class) continue; //don't support columns in columns

            if (Kwc_Abstract::hasSetting($c['component'], 'masterLayout')) {
                $ret[] = array_merge($stack, array($c));
            } else if (is_instance_of(Kwc_Abstract::getSetting($c['component'], 'contentSender'), 'Kwf_Component_Abstract_ContentSender_Lightbox')) {
                $ret[] = array_merge($stack, array($c));
            }
            $ret = array_merge($ret, $this->_findParentsStack($c['component'], array_merge($stack, array($c))));
        }
        return $ret;
    }

    public function calcSupportedContexts()
    {
        $ret = array();
        foreach ($this->_findParentsStack($this->_class) as $stack) {
            $boxName = false;
            while ($stackEntry = array_pop($stack)) {
                $class = $stackEntry['component'];
                if (isset($stackEntry['box'])) {
                    $boxName = $stackEntry['box'];
                    $contexts = false;
                } else {
                    $contexts = Kwf_Component_Layout_Abstract::getInstance($class)->getSupportedChildContexts($stackEntry['generator']);
                }
                if ($contexts===false) {
                    $layout = false;
                    if (Kwc_Abstract::hasSetting($class, 'masterLayout')) {
                        $layout = Kwf_Component_MasterLayout_Abstract::getInstance($class);
                    } else if (is_instance_of(Kwc_Abstract::getSetting($class, 'contentSender'), 'Kwf_Component_Abstract_ContentSender_Lightbox')) {
                        $layout = new Kwc_Susy_LightboxMasterLayout();
                    }
                    if ($layout) {
                        if ($boxName) {
                            $contexts = $layout->getSupportedBoxContexts($boxName);
                        } else {
                            $contexts = $layout->getSupportedContexts();
                        }
                    }
                }
                if ($contexts !== false) {
                    foreach ($contexts as $c) {
                        if (!$this->_isSupportedContext($c)) continue;
                        $found = false;
                        foreach ($ret as $i) {
                            if ($i == $c) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $ret[] = $c;
                        }
                    }
                }
            }
        }

        usort($ret, array(__CLASS__, '_sortSupportedLayouts'));
        return $ret;
    }

    public function _sortSupportedLayouts($a, $b)
    {
        //first order by masterLayouts
        if ($a['masterLayout'] != $b['masterLayout']) {
            return $a['masterLayout'] > $b['masterLayout'] ? +1 : -1;
        }

        //then by breakpoint values, so we can mobile-first
        static $masterLayouts;
        if (!isset($masterLayouts)) $masterLayouts = Kwc_Susy_Helper::getLayouts();
        $layoutA = $masterLayouts[$a['masterLayout']][$a['breakpoint']];
        $layoutB = $masterLayouts[$b['masterLayout']][$b['breakpoint']];
        $breakpointA = isset($layoutA['breakpoint']) ? (int)$layoutA['breakpoint'] : 0;
        $breakpointB = isset($layoutB['breakpoint']) ? (int)$layoutB['breakpoint'] : 0;
        if ($breakpointA == $breakpointB) return 0;
        return ($breakpointA > $breakpointB) ? +1 : -1;
    }

    protected function _isSupportedContext($context)
    {
        return true;
    }

    public function getChildContentWidth(Kwf_Component_Data $data, Kwf_Component_Data $child)
    {
        $ret = 0;
        $masterLayouts = Kwc_Susy_Helper::getLayouts();
        foreach ($this->getChildContexts($data, $child) as $contexts) {
            $breakpoint = $masterLayouts[$contexts['masterLayout']][$contexts['breakpoint']];
            $colWidth = null;
            if (isset($breakpoint['column-width'])) {
                $colWidth = $breakpoint['column-width'];
            } else if (isset($breakpoint['breakpoint'])) {
                $colWidth = (int)$breakpoint['breakpoint'] / $breakpoint['columns'];
            }
            if ($colWidth) {
                $width = $colWidth * $contexts['spans'];
                $ret = max($ret, $width);
            }
        }
        return $ret;
    }

    public function getContentWidth(Kwf_Component_Data $data)
    {
        $ret = 0;
        $masterLayouts = Kwc_Susy_Helper::getLayouts();
        foreach ($this->getContexts($data) as $contexts) {
            $breakpoint = $masterLayouts[$contexts['masterLayout']][$contexts['breakpoint']];
            $colWidth = null;
            if (isset($breakpoint['column-width'])) {
                $colWidth = $breakpoint['column-width'];
            } else if (isset($breakpoint['breakpoint'])) {
                $colWidth = (int)$breakpoint['breakpoint'] / $breakpoint['columns'];
            }
            if ($colWidth) {
                $width = $colWidth * $contexts['spans'];
                $ret = max($ret, $width);
            }
        }
        return $ret;
    }
}
