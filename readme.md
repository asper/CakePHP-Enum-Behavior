Enum Behavior
=============

CakePHP does not support MySQL's `Enum` fields types. I've commed around 3 solutions to conturn this problem: 

- using a second table
- using MySQL's Enum and retrieve EnumValues like in [Baked Enums](http://bakery.cakephp.org/articles/view/baked-enums)

This behavior is using an other approach, it stores the configuration in an array which is fast and flexible. It also creates validation rules and the select lists.

Installation
------------

Simply download and put it in the `CakephpEnumBehavior` folder and its content in your `app/Plugin` folder.

Load plugin in `app/Config/bootstrap.php` :

	CakePlugin::load('CakephpEnumBehavior');

Using composer: 

    "require" : {
        "asper/cakephp-enum-behavior": "*"
    }

Usage
-----

Let's assume we have a `posts` table and we want to have a `status` field that could have 3 possible values (`draft`, `published`, `archive`).

Table data :

    id			int(10)
    title		varchar(65)
    content		varchar(65)
    status		varchar(65)

In your `Post` model : 

    public $actsAs = array(
    	'CakephpEnumBehavior.Enum' => array(
    		'status' => array('draft', 'published', 'archive')
    	)
    );

In your `Posts` controller, in the `beforeRender` callback add :

    $this->set($this->Post->enumValues());

To display the `status` dropdown menu in your forms you just have to use the `FormHelper` as usual :

    $this->Form->input('Post.status');
    
To display the status in your `view` use :

    echo $statuses[$post['Post']['status']];

If yout want the strings to be translated, add (anywhere in your application [even if it doesn't seems right]):

    __('Draft'); // Humanized value
    __('Published');
    __('Archive');
