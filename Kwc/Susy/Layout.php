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
                                    'component' => $c
                                );
                            } else {
                                $cache[$class][] = array(
                                    'box' => $boxes[0],
                                    'component' => $c
                                );
                            }
                        } else {
                            $cache[$class][] = array(
                                'component' => $c
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
            }
            $csMasterLayout = call_user_func(array(Kwc_Abstract::getSetting($c['component'], 'contentSender'), 'getMasterLayout'));
            if ($csMasterLayout !== false) {
                $ret[] = array_merge($stack, array($c));
            }
            $ret = array_merge($ret, $this->_findParentsStack($c['component'], array_merge($stack, array($c))));
        }
        return $ret;
    }

    public function getSupportedContexts()
    {
        if (isset($this->_cacheChildContexts)) return $this->_cacheChildContexts;
        $ret = array();
        foreach ($this->_findParentsStack($this->_class) as $stack) {
            $boxName = false;
            while ($stackEntry = array_pop($stack)) {
                $class = $stackEntry['component'];
                if (isset($stackEntry['box'])) {
                    $boxName = $stackEntry['box'];
                    $contexts = false;
                } else {
                    $contexts = Kwf_Component_Layout_Abstract::getInstance($class)->getSupportedChildContexts();
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
        $this->_cacheChildContexts = $ret;
        return $ret;
    }

    protected function _isSupportedContext($context)
    {
        return true;
    }
}
