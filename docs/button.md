# Custom Buttons

#### Rendering a button
To set a custom button:
```php
$button = new \UMFlint\Html\Form\Button('Submit!');
$button->render();
```

Outputs:
```html
<button type="submit">Submit!</button>
```

_The default `type` that is set is `submit`. If you do not specify a type, 
it will automatically be set to `submit`_

#### Text and Type Attributes

##### Text
```php
$button = new \UMFlint\Html\Form\Button('Submit!');
$button->text('Cancel');
```

Outputs:
```html
<button type="submit">Cancel</button>
```

##### Type
```php
$button = new \UMFlint\Html\Form\Button('Submit!');
$button->type('reset');
```

Outputs:
```html
<button type="reset">Submit!</button>
```