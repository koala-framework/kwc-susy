<?php
class Susy_Kwc_Columns_Component extends Kwc_Columns_Abstract_Component
{
    public static $needsParentComponentClass = false;
    public static function getSettings()
    {
        $ret = parent::getSettings('Kwc_Paragraphs_Component');
        $columnsTrl = trlKwfStatic('Columns');
        $ret['columns'] = array(
            '2col-50_50' => array(
                'columns' => 2,
                'colSpans' => array(1, 1),
                'name' => "2 $columnsTrl (50% - 50%)"
            ),
            '3col-33_33_33' => array(
                'columns' => 3,
                'colSpans' => array(1, 1, 1),
                'name' => "3 $columnsTrl (33% - 33% - 33%)"
            ),
            '4col-25_25_25_25' => array(
                'columns' => 4,
                'colSpans' => array(1,1,1,1),
                'name' => "4 $columnsTrl (25% - 25% - 25% - 25%)"
            )
        );
        $ret['layoutClass'] = 'Susy_Kwc_Columns_Layout';
        return $ret;
    }

    public function getTemplateVars()
    {
        $ret = parent::getTemplateVars();
        foreach ($this->getMasterLayoutContexts() as $c) {
            $ret['rootElementClass'] .= " kwfUp-$c[masterLayout]-$c[breakpoint]-spans$c[spans]";
        }
        return $ret;
    }
}
