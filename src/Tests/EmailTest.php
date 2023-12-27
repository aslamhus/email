<?php

use PHPUnit\Framework\TestCase;

use Aslamhus\Email\Email;

require_once __DIR__ . '/config.php';

class EmailTest extends TestCase
{
    /**
     * Test send verify integration email
     *
     * This test will send an email to Sendgrid's test email address
     *
     * @return void
     */
    public function testSendVerifyIntegrationEmail()
    {
        $email = new Email($_ENV['SENDGRID_API_KEY']);
        $didSend = $email->sendVerifyIntegrationEmail([$_ENV['TEST_VERIFIED_EMAIL_ADDRESS'],$_ENV['TEST_VERIFIED_EMAIL_NAME']]);
        $this->assertTrue($didSend === true);
    }

    /**
     * Test send dynamic template data
     *
     * These templates have three substitutions: {{air_date}} and {{show_link}} and {{subject}}
     * They will be replaced with the values provided in the dynamic template data.
     * The email is sent to your test recipient email address set in the env file
     *
     * @return void
     */
    public function testSendDynamicTemplateData()
    {
        $email = new Email($_ENV['SENDGRID_API_KEY']);
        $didSend = $email
        ->setFrom($_ENV['TEST_VERIFIED_EMAIL_ADDRESS'], $_ENV['TEST_VERIFIED_EMAIL_NAME'])
        ->addTo($_ENV['TEST_RECIPIENT_EMAIL_ADDRESS'], $_ENV['TEST_RECIPIENT_EMAIL_NAME'])
        ->setSubject('Test email from Aslamhus/Email')
        ->setTemplateId($_ENV['TEST_TEMPLATE_ID'])
        ->addDynamicTemplateDatas([
            'air_date' => '2020-01-01',
            'show_link' => 'https://www.aslamhusainphotography.com',
            'subject' => 'Test email'
        ])
        ->send();
        $this->assertTrue($didSend === true);

    }

    /**
     * Test response
     *
     * This test will send an email to Sendgrid's test email address
     * and then check the response
     *
     * @return void
     */
    public function testResponse()
    {
        $email = new Email($_ENV['SENDGRID_API_KEY']);
        $email
        ->setFrom($_ENV['TEST_VERIFIED_EMAIL_ADDRESS'], $_ENV['TEST_VERIFIED_EMAIL_NAME'])
        ->addTo($_ENV['TEST_RECIPIENT_EMAIL_ADDRESS'], $_ENV['TEST_RECIPIENT_EMAIL_NAME'])
        ->setSubject('Test email from Aslamhus/Email')
        ->setTemplateId($_ENV['TEST_TEMPLATE_ID'])
        ->addDynamicTemplateDatas([
            'air_date' => '2020-01-01',
            'show_link' => 'https://www.aslamhusainphotography.com',
            'subject' => 'Test email'
        ])
        ->send();
        $response = $email->getResponse();
        $this->assertTrue($response->statusCode() === 202);
        $this->assertTrue(is_array($response->headers()));
        $this->assertTrue(is_string($response->body()));
    }

    /**
     * Test send basic email
     *
     * This test will send an email to Sendgrid's test email address
     *
     * @return void
     */
    public function testSendBasicEmail()
    {
        $didSend = (new Email($_ENV['SENDGRID_API_KEY']))
        ->setFrom($_ENV['TEST_VERIFIED_EMAIL_ADDRESS'], $_ENV['TEST_VERIFIED_EMAIL_NAME'])
        ->addTo($_ENV['TEST_VERIFIED_EMAIL_ADDRESS'], $_ENV['TEST_VERIFIED_EMAIL_NAME'])
        ->setSubject('Test email')
        ->addContent('text/plain', 'Hello world!')
        ->send();
        $this->assertTrue($didSend === true);
    }
}
