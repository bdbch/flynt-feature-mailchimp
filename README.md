# Mailchimp Feature

> This is a mailchimp integration for the Wordpress development framework "Flynt"

## Installation

1. Make sure to install the required composer packages

```
composer require "drewm/mailchimp-api": "^2.5",
```

2. Clone or download this folder into your Flynt themes feature folder. For example: `/path/to/your/flynt-project/web/app/themes/your-theme/Features/Mailchimp`

3. Add the following line to your projects Init.php in `theme/lib`: `add_theme_support('flynt-mailchimp');`

4. Add the Mailchimp API Key in the Feature option page

Now you're ready to use the Mailchimp Feature in your project

## Usage

```php
<?php

use Flynt\Features\Mailchimp\Instance;

function subscribeUserToNL($listId, $email)
{
  $MailchimpInstance = new Instance();
  $result = $MailchimpInstance->SubscribeToList($listId, $email, 'pending');
}
```
