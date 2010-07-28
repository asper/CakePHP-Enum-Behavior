Enum Behavior
=============

This simple behavior eases the usage of enum fields in CakePHP. It generated select lists and creates validation rules.

Installation
------------

Simply put the enum folder and its content in your app/plugins folder.

Usage
-----

In your model : 

    $actsAs = array(
    	'Enum.Enum' => array(
    		'example_field' => array('value_1', 'value_2')
    	)
    );

In your controller :

    $this->set($this->Enum->enumValues());

Advanced usage
--------------

If you want to customise the text displayed in the select lists, simply use CakePHP's i18n function.

Put this code anywhere in your app:

    __('value_1', true);
    __('value_2', true);

