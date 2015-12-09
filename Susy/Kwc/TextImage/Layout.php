<?php
class Susy_Kwc_TextImage_Layout extends Susy_Layout
{
    protected function _isSupportedContext($context)
    {
        if ($context['spans'] < 3) return false;
        return true;
    }

    public function getChildContexts(Kwf_Component_Data $data, Kwf_Component_Data $child)
    {
        $ownContexts = parent::getChildContexts($data, $child);

        if ($child->id == 'image') {
            if (!$data->getComponent()->getRow()->image) {
                return null;
            }

            $imageWidth = $data->getComponent()->getRow()->image_width;
            if (!$imageWidth) $imageWidth = 25;
            $widthCalc = 100/$imageWidth;
            $ret = array();
            $masterLayouts = Susy_Helper::getLayouts();
            foreach ($ownContexts as $context) {
                $breakpoint = $masterLayouts[$context['masterLayout']][$context['breakpoint']];
                //same logic in scss
                if (!isset($breakpoint['breakpoint']) || (int)$breakpoint['breakpoint'] * $context['spans'] / $breakpoint['columns'] < 300) {
                    //full width
                    $ret[] = $context;
                } else {
                    $context['spans'] = floor($context['spans'] * $widthCalc);
                    if ($context['spans'] < 1) {
                        $context['spans'] = 1;
                    }
                    $ret[] = $context;
                }
            }
            return $ret;
        } else {
            return $ownContexts;
        }
    }
}
