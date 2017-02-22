## Elements

Rendering an element:
```php
$div = new \UMFlint\Html\Element('div');
$div->render();
```

Output:
```html
<div></div>
```

You can get the tag of an element:
```php
$div = new \UMFlint\Html\Element('div');
$div->getTag();
```

Returns `"div"`

This also supports HTML5's self closing tags:
```php
$br = new UMFlint\Html\Element('br');
$br->render();
```

Output:
```html
<br>
```

You can append text inside your element also:
```php
$div = new UMFlint\Html\Element('div');
$div->appendChild('I am a child');
```

Element:
```html
<div>I am a child</div>
```

You can also append a child within another child:
```php
$div->appendChild((new UMFlint\Html\Element('span')))->appendChild('I am inside a span');
```

Output:
```html
<div>I am a child<span>I am inside a span</span></div>
```

Adding another class to an element:
```php
$div = new UMFlint\Html\Element('div');
$div->addClass('input');
```

The element now looks like:
```html
<div class="input"></div>
```

You can also add multiple classes as an array:
```php
$div->addClass(['submit', 'button']);
```

The element looks like:
```html
<div class="input submit button"></div>
```

Removing a class from an element is as simple as:
```php
$div->removeClass('button');
```

Now the element looks like:
```html
<div class="input submit"></div>
```

