Enum Behavior
=============

CakePHP does not support MySQL's `Enum` fields types. I've commed around 3 solutions to conturn this problem: 

- using a second table

- using MySQL's Enum and retrieve EnumValues like in [Baked Enums](http://bakery.cakephp.org/articles/view/baked-enums)

This behavior is using an other approach, it stores the configuration in an array which is fast and flexible. It also creates validation rules and the select lists.

Installation
------------

Simply put the `enum` folder and its content in your `app/plugins` folder.

Usage
-----

Let's assume we have a `posts` table and we want to have a `status` field that could have 3 possible values (`draft`, `published`, `archive`).

Table data :

    id			int(10)
    title		varchar(65)
    content		varchar(65)
    status		varchar(65)

In your `Post` model : 

    $actsAs = array(
    	'Enum.Enum' => array(
    		'status' => array('draft', 'published', 'archive')
    	)
    );
    
    function __translateEnums(){
    	return;
    	__('draft', true);
    	__('published', true);
    	__('archive', true);
    }

In your `Posts` controller, at the end of `add` and `edit` actions :

    $this->set($this->Enum->enumValues());




