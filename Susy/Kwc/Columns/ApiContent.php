<?php
class Susy_Kwc_Columns_ApiContent implements Kwf_Component_ApiContent_Interface
{
    public function getContent(Kwf_Component_Data $data)
    {
        $ret = array();
        $ret['columnLayout'] = $data->getComponent()->getRow()->type;
        $ret['columns'] = array();

        foreach ($data->getChildComponents(array('generator'=>'child')) as $columns) {
            $ret['columns'][] = $columns;
        }
        return $ret;
    }
}
