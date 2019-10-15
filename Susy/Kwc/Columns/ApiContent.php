<?php
class Susy_Kwc_Columns_ApiContent implements Kwf_Component_ApiContent_Interface
{
    public function getContent(Kwf_Component_Data $data)
    {
        $ret = array();
        $columnType = $data->getComponent()->getRow()->type;
        $ret['columnType'] = $columnType;

        $settingColumns = Kwc_Abstract::getSetting($data->componentClass, 'columns');
        $ret['colSpans'] = $settingColumns[$columnType]['colSpans'];

        $ret['columns'] = array();
        foreach ($data->getChildComponents(array('generator'=>'child')) as $columns) {
            $ret['columns'][] = $columns;
        }
        return $ret;
    }
}
