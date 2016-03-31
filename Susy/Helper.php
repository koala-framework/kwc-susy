<?php
class Susy_Helper
{
    private static function _calcLayouts()
    {
        //Support Kwf 4.0 ($needsProviderList=false) and 4.1+ ($needsProviderList=true)
        $reflection = new ReflectionClass('Kwf_Assets_Dependency_Abstract');
        $params = $reflection->getConstructor()->getParameters();
        $needsProviderList = false;
        if ($params && $params[0]->getName() == 'providerList') {
            $needsProviderList = true;
        }


        $ret = array();
        if ($needsProviderList) {
            $d = new Kwf_Assets_Dependency_File_Scss(Kwf_Assets_ProviderList_Default::getInstance(), 'kwcSusy/Susy/Helper/get-layouts.scss');
        } else {
            $d = new Kwf_Assets_Dependency_File_Scss('kwcSusy/Susy/Helper/get-layouts.scss');
        }
        preg_match_all('#([a-z-]+)\s*{(.*?)}#', $d->getContentsPacked(null)->getFileContents(), $m);
        foreach (array_keys($m[0]) as $i) {
            $ruleName = $m[1][$i];
            $rule = $m[2][$i];
            preg_match_all('#([a-zA-Z-]+):\s*([^;]+)#', $rule, $m2);
            $entry = array();
            foreach (array_keys($m2[0]) as $k) {
                $propName = $m2[1][$k];
                $propValue = $m2[2][$k];
                if ($propName == 'content-spans') $propValue = (int)$propValue;
                $entry[$propName] = $propValue;
            }
            if ($ruleName == 'breakpoint') {
                $ret[$entry['master']][$entry['breakpoint-name']] = $entry;
                $ret[$entry['master']][$entry['breakpoint-name']]['box-spans'] = array();
            } else if ($ruleName == 'box-spans') {
                $ret[$entry['master']][$entry['breakpoint-name']]['box-spans'][$entry['box-name']] = (int)$entry['spans'];
            }
        }
        return $ret;
    }

    /**
     * @internal
     */
    public static function _build()
    {
        static $buildDone = false;
        if ($buildDone) return;
        $buildDone = true;

        $data = self::_calcLayouts();
        file_put_contents('build/susy-layouts', serialize($data));
    }

    public static function getLayouts()
    {
        static $ret;
        if (isset($ret)) return $ret;
        if (file_exists('build/susy-layouts')) {
            $ret = unserialize(file_get_contents('build/susy-layouts'));
        } else {
            $ret = self::_calcLayouts();
        }
        return $ret;
    }
}
