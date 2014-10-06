Enum Behavior
=============

CakePHP does not support MySQL's `Enum` fields types. I've commed around 3 solutions to conturn this problem: 

- using a second table
- using MySQL's Enum and retrieve EnumValues like in [Baked Enums](http://bakery.cakephp.org/articles/view/baked-enums)

This behavior is using an other approach, it stores the configuration in an array which is fast and flexible. It also creates validation rules and the select lists.

Installation
------------

Simply download and put it in the `CakePHP-Enum-Behavior` folder and its content in your `app/Plugin` folder.

Load plugin in `app/Config/bootstrap.php` :

	CakePlugin::load('CakePHP-Enum-Behavior');

Using composer: 

    "require" : {
        "asper/cakephp-enum-behavior": "*"
    }

As a git submodule (execute inside `app/Plugin`):

    git submodule add https://github.com/asper/CakePHP-Enum-Behavior.git

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
    	'CakePHP-Enum-Behavior.Enum' => array(
    		'status' => array('draft', 'published', 'archive')
    	)
    );

You can use named keys in associative array with key as data field value

    public $actsAs = array(
        'CakePHP-Enum-Behavior.Enum' => array(
            'status' => array(23 => 'draft', 'pub' => 'published', 2 => 'archive')
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

Credits
-------

- [kaiyou](https://github.com/kaiyou) : Strict compatibility with CakePHP 2.3.0
- [Andrew Bashtannik](https://github.com/a-bashtannik) : Composer compatibility
- [Tomasz Mazur](https://github.com/tmazur) : Change Plugin name to reflect GitHub project name (allows use of Plugin as git submodule)

License
-------

The MIT License (MIT)

Copyright (c) 2013 Pierre Aboucaya

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
