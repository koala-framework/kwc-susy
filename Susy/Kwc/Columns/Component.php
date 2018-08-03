<?php
class Susy_Kwc_Columns_Component extends Kwc_Columns_Abstract_Component
{
    public static $needsParentComponentClass = false;
    public static function getSettings($param = null)
    {
        $ret = parent::getSettings('Kwc_Paragraphs_Component');
        $ret['layoutClass'] = 'Susy_Kwc_Columns_Layout';
        $columnsTrl = trlKwfStatic('Columns');

        $ret['columns'] = array(
            '2col-50_50' => array(
                'colSpans' => array(1, 1),
                'name' => "2 $columnsTrl (50% - 50%)"
            ),
            '2col-66_33' => array(
                'colSpans' => array(2, 1),
                'name' => "2 $columnsTrl (66% - 33%)"
            ),
            '2col-33_66' => array(
                'colSpans' => array(1, 2),
                'name' => "2 $columnsTrl (33% - 66%)"
            ),
            '2col-75_25' => array(
                'colSpans' => array(3, 1),
                'name' => "2 $columnsTrl (75% - 25%)"
            ),
            '2col-25_75' => array(
                'colSpans' => array(1, 3),
                'name' => "2 $columnsTrl (25% - 75%)"
            ),
            '3col-33_33_33' => array(
                'colSpans' => array(1, 1, 1),
                'name' => "3 $columnsTrl (33% - 33% - 33%)"
            ),
            '3col-25_50_25' => array(
                'colSpans' => array(1, 2, 1),
                'name' => "3 $columnsTrl (25% - 50% - 25%)"
            ),
            '4col-25_25_25_25' => array(
                'colSpans' => array(1, 1, 1, 1),
                'name' => "4 $columnsTrl (25% - 25% - 25% - 25%)"
            )
        );
        $ret['apiContent'] = 'Susy_Kwc_Columns_ApiContent';
        $ret['apiContentType'] = 'susyColumns';
        return $ret;
    }

    public function getTemplateVars(Kwf_Component_Renderer_Abstract $renderer = null)
    {
        $ret = parent::getTemplateVars($renderer);
        foreach ($this->getMasterLayoutContexts() as $c) {
            $ret['rootElementClass'] .= " kwfUp-$c[masterLayout]-$c[breakpoint]-spans$c[spans]";
        }
        return $ret;
    }
}
