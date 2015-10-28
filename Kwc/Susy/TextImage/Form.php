<?php
class Kwc_Susy_TextImage_Form extends Kwc_TextImage_Form
{
    protected function _initFields()
    {
        parent::_initFields();

        $fs = $this->fields['image'];
        $fs->add(new Kwf_Form_Field_Select('image_width', trlKwf('Image Width')))
            ->setValues(array(
                '25' => trlKwf('25%'),
                '33' => trlKwf('33%'),
                '50' => trlKwf('50%')
            ))
            ->setWidth(210);
    }
}
