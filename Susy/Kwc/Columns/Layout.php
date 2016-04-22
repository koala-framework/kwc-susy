<?php
class Susy_Kwc_Columns_Layout extends Susy_Layout
{
    public function calcSupportedChildContexts()
    {
        $ret = array('child'=>array());
        $masterLayouts = Susy_Helper::getLayouts();

        foreach ($this->getSupportedContexts() as $context) {
            foreach ($this->_getSetting('columns') as $column) {
                foreach ($column['colSpans'] as $span) {
                    $breakpoint = $masterLayouts[$context['masterLayout']][$context['breakpoint']];
                    //same logic in scss
                    if (!isset($breakpoint['breakpoint']) || (int)$breakpoint['breakpoint'] * $context['spans'] / $breakpoint['columns'] < 300) {
                        //full width
                        $spans = $context['spans'];
                    } else {
                        $spans = $breakpoint['content-spans'] / array_sum($column['colSpans']) * $span;
                        if ($spans < 1) $spans = 1;
                    }

                    $newChild = array(
                        'masterLayout' => $context['masterLayout'],
                        'breakpoint' => $context['breakpoint'],
                        'spans' => (int)$spans
                    );

                    if (!in_array($newChild, $ret['child'])) $ret['child'][] = $newChild;
                }
            }
        }

        return $ret;
    }

    public function getChildContexts(Kwf_Component_Data $data, Kwf_Component_Data $child)
    {
        $ownContexts = parent::getChildContexts($data, $child);
        $masterLayouts = Susy_Helper::getLayouts();

        $ret = array();

        foreach ($ownContexts as $context) {
            $breakpoint = $masterLayouts[$context['masterLayout']][$context['breakpoint']];
            //same logic in scss
            if (!isset($breakpoint['breakpoint']) || (int)$breakpoint['breakpoint'] * $context['spans'] / $breakpoint['columns'] < 300) {
                //full width
                $ret[] = $context;
            } else {
                $colSettings = $this->_getSetting('columns');
                $type = $data->getComponent()->getRow()->type;
                $columnTypeSpans = $colSettings[$type]['colSpans'];
                $span = $columnTypeSpans[$child->row->pos-1];

                $context['spans'] = $breakpoint['columns'] / array_sum($columnTypeSpans) * $span;
                if ($context['spans'] < 1) $context['spans'] = 1;

                $ret[] = $context;
            }
        }

        return $ret;
    }
}
