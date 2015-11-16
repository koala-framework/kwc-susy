<?php
class Kwc_Susy_Columns_Layout extends Kwc_Susy_Layout
{
    public function calcSupportedChildContexts()
    {
        $ret = array('child'=>array());
        $masterLayouts = Kwc_Susy_Helper::getLayouts();
        foreach ($this->getSupportedContexts() as $context) {
            foreach ($this->_getSetting('columns') as $column) {
                $breakpoint = $masterLayouts[$context['masterLayout']][$context['breakpoint']];
                //same logic in scss
                if (!isset($breakpoint['breakpoint']) || (int)$breakpoint['breakpoint'] * $context['spans'] / $breakpoint['columns'] < 300) {
                    //full width
                    $spans = $context['spans'];
                } else {
                    $spans = $context['spans'] / $column['columns'];
                    $spans = floor($spans);
                    if ($spans < 1) $spans = 1;
                }

                $ret['child'][] = array(
                    'masterLayout' => $context['masterLayout'],
                    'breakpoint' => $context['breakpoint'],
                    'spans' => (int)$spans
                );
            }
        }

        return $ret;
    }

    public function getChildContexts(Kwf_Component_Data $data, Kwf_Component_Data $child)
    {
        $ownContexts = parent::getChildContexts($data, $child);

        $widthCalc = $child->row->col_span / $child->row->columns;
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
