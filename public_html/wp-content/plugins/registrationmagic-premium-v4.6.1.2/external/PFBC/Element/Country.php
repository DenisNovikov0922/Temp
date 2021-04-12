<?php

class Element_Country extends Element_Select
{

    public function __construct($label, $name, array $properties = null)
    {
        $options = RM_Utilities::get_countries();
        $properties['data-country-field']=1;
        parent::__construct($label, $name, $options, $properties);
    }
}
