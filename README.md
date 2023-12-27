# Aslamhus\Email PHP Class

## Overview

This PHP Library, `Aslamhus/Email`, is convenient and flexible wrapper for interacting with the SendGrid API v3.0.0. It is designed to provide a fluent interface for sending emails via SendGrid, making it simple to integrate email functionality into your PHP projects. The class includes methods for composing and sending emails with features such as dynamic template data, attachments, and more.

## Installation / Setup

To use this class in your project, follow these steps:

1. Create an account with [Sendgrid](https://sendgrid.com).

2. Create an API Key in your new sendgrid account.
   In the sidebar of your dashboard, choose `EmailApi`, then select `Integration Guide` from the dropdown. Choose WebApi -> Php, then create your api key. You do not need to install sendgrid through composer, installing this class will do that for you. The last step in the integration guide is to send a verification email. Before we do that, install the library.

3. Create a single sender identity.
   This will allow you to send emails from an authenticated email. In the sidebar choose `Sender Authentication`, then `Verify a Single Sender`. Follow the steps. You'll use this email in the `from` method when sending emails.
4. Install `Aslamhus/Email`

```php
composer require aslamhus/sendgrid-email-wrapper
```

5. Send verficiation email (optional)

```php
// add your api key
$email = new Email('your-sendgrid-api-key');
// make sure to add your single sender identity email and name
$didSend = $email->sendVerifyIntegrationEmail(['verified@example.com', 'Verified User']);
// $didSend will return true if everything is set up correctly.
```

6. Confirm verification.
   Go back to the integration guide in your Sendgrid account, and at the bottom of the page choose `Verify Integration`. You should get a confirmation that your integration was successful.

## Usage

### Send basic email

```php

$email = new Email('your-sendgrid-api-key')
    ->setFrom('sender@example.com', 'Sender Name')
    ->addTo('recipient@example.com', 'Recipient Name')
    ->setSubject('Test email')
    ->addContent('text/plain', 'Hello world!')
    ->send();

```

### Send Email with dynamic template data

Dynamic template data variables can be set using `handlebars` {{my_var}}.
For more info see [https://docs.sendgrid.com/for-developers/sending-email/using-handlebars](https://docs.sendgrid.com/for-developers/sending-email/using-handlebars)

```php
$email = new Email('your-sendgrid-api-key')
    ->setFrom('sender@example.com', 'Sender Name')
    ->addTo('recipient@example.com', 'Recipient Name')
    ->setSubject('Test email')
    ->setTemplateId('your-template-id')
    ->addDynamicTemplateDatas([
        'subject' => 'My dynamic template email'
        'name' => '2020-01-01',
        'link' => 'https://www.example.com',
    ])
    ->send();
```

### Additional Features

- **Add Attachments:**

  ```php
  $email->addAttachment('path/to/file.pdf', 'application/pdf', 'document.pdf');
  ```

- **Add Content:**

  ```php
  $email->addContent('text/plain', 'This is the plain text content');
  $email->addContent('text/html', '<p>This is the HTML content</p>');
  ```

  - **Get email response:**

  ```php
  // retrieve the response body, headers and status code after sending
  $response = $email->getResponse();
  print_r($response->headers());
  echo $response->statusCode()
  echo $response->body()

  ```

## Resources

- [SendGrid PHP Library on GitHub](https://github.com/sendgrid/sendgrid-php/blob/main/USAGE.md)
- [SendGrid API Methods](https://github.com/sendgrid/sendgrid-php/blob/08514e75789f192c034fdcf18efe6d8b1a7c91da/lib/BaseSendGridClientInterface.php#L65)

## Issues and Contributions

If you encounter any issues or would like to contribute to the development of this class, please visit the GitHub repository: [Aslamhus\Email](https://github.com/aslamhus/Email).

## Testing

To run tests on this library, follow these steps:

1. Set your sample.env file with the require fields, then rename sample.env to .env
2. Run tests

```php
composer run test
```

## License

This class is open-source and released under the [MIT License](LICENSE). Feel free to use, modify, and distribute it according to your project's needs.
