<?php
class Kwc_Susy_TextImage_Layout extends Kwc_Susy_Layout
{
    protected function _isSupportedContext($context)
    {
        if ($context['spans'] < 3) return false;
        return true;
    }

    public function getChildContexts(Kwf_Component_Data $data, Kwf_Component_Data $child)
    {
        $ownContexts = parent::getChildContexts($data, $child);

        $widthCalc = 100/$data->getComponent()->getRow()->image_width;
        $ret = array();
        $masterLayouts = Kwc_Susy_Helper::getLayouts();
        foreach ($ownContexts as $context) {
            $breakpoint = $masterLayouts[$context['masterLayout']][$context['breakpoint']];
            //same logic in scss
            if (!isset($breakpoint['breakpoint']) || (int)$breakpoint['breakpoint'] * $context['spans'] / $breakpoint['columns'] < 300) {
                //full width
            } else {
                $context['spans'] = floor($context['spans'] * $widthCalc);
                if ($context['spans'] < 1) {
                    $context['spans'] = 1;
                }
                $ret[] = $context;
            }
        }
        return $ret;
    }
}
