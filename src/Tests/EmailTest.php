<?php

use PHPUnit\Framework\TestCase;

use Aslamhus\Email\Email;

require_once __DIR__ . '/config.php';

class EmailTest extends TestCase
{
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
}
