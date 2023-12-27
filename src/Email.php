<?php

namespace Aslamhus\Email;

use SendGrid\Mail\Mail;
use TypeException;
use SendGrid\Mail\Attachment;
use SendGrid\Mail\Substitution;
use SendGrid\Mail\To;
use SendGrid\Response;

/**
 * Wrapper for SendGrid API v1.0.0
 *
 * A fluent interface for SendGrid API v3
 *
 * by @aslamhus on github
 *
 * For examples @see https://github.com/aslamhus/email.git
 *
 * For further information on the SendGrid API:
 * • Usage @see https://github.com/sendgrid/sendgrid-php/blob/main/USAGE.md
 * • Methods @see https://github.com/sendgrid/sendgrid-php/blob/08514e75789f192c034fdcf18efe6d8b1a7c91da/lib/BaseSendGridClientInterface.php#L65
 **/
class Email
{
    private string $apiKey;
    // the sendergrid object
    private \SendGrid $sendGrid;
    // the email object
    private Mail $email;
    private Response $response;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        // set api key
        $this->sendGrid = new \SendGrid($apiKey);
        // create email
        $this->email = new Mail();
    }

    private function validateApiKey(): bool
    {
        if (empty($this->apiKey)) {
            throw new EmailException('API key is not set');
        }

        return true;
    }

    public function addTo(string $email, string $name): self
    {
        $this->email->addTo($email, $name);
        return $this;
    }

    /**
     * Adds multiple email recipients to a Personalization object
     *
     * @param To[]|array           $toEmails             Array of To objects or key/value pairs of
     *                                                   email address/recipient names
     * @param int|null             $personalizationIndex Index into the array of existing
     *                                                   Personalization objects
     * @param Personalization|null $personalization      A pre-created
     *                                                   Personalization object
     *
     * @throws TypeException
     */
    public function addTos(array $emails): self
    {
        $this->email->addTos($emails);
        return $this;
    }

    /**
     * Set from
     *
     * @param string $email
     * @param string $name
     * @return self
     */
    public function setFrom(string $email, string $name): self
    {
        $this->email->setFrom($email, $name);
        return $this;
    }

    /**
    * Add a subject to a Personalization or Mail object
    *
    * If you don't provide a Personalization object or index, the
    * subject will be global to entire message. Note that
    * subjects added to Personalization objects override
    * global subjects.
    *
    * @param string|Subject       $subject              Email subject
    * @param int|null             $personalizationIndex Index into the array of existing
    *                                                   Personalization objects
    * @param Personalization|null $personalization      A pre-created
    *                                                   Personalization object
    * @throws TypeException
    */
    public function setSubject($subject, $personalizationIndex = null, $personalization = null): self
    {
        $this->email->setSubject($subject, $personalizationIndex, $personalization);
        return $this;
    }

    /**
    * Add a Substitution object or key/value to a Personalization object
    *
    * @param array|Substitution[] $datas                Array of Substitution
    *                                                   objects or key/values
    * @param int|null             $personalizationIndex Index into the array of existing
    *                                                   Personalization objects
    * @param Personalization|null $personalization      A pre-created
    *                                                   Personalization object
    * @throws TypeException
    */
    public function addDynamicTemplateDatas(
        $datas,
        $personalizationIndex = null,
        $personalization = null
    ): self {
        $this->email->addSubstitutions($datas, $personalizationIndex, $personalization);
        return $this;
    }


    /**
     * Add an attachment to a Mail object
     *
     * @param string|Attachment $attachment  Attachment object or
     *                                       Base64 encoded content
     * @param string|null       $type        Mime type of the attachment
     * @param string|null       $filename    File name of the attachment
     * @param string|null       $disposition How the attachment should be
     *                                       displayed: inline or attachment
     *                                       default is attachment
     * @param string|null       $content_id  Used when disposition is inline
     *                                       to display the file within the
     *                                       body of the email
     * @throws TypeException
     */
    public function addAttachment(
        $attachment,
        $type = null,
        $filename = null,
        $disposition = null,
        $content_id = null
    ) {
        $this->email->addAttachment($attachment, $type, $filename, $disposition, $content_id);
        return $this;
    }

    /**
    * Add content to a Mail object
    *
    * For a list of pre-configured mime types, please see
    * MimeType.php
    *
    * @param string|Content $type  Mime type or Content object
    * @param string|null    $value Contents (e.g. text or html)
    *
    * @throws TypeException
    */
    public function addContent(string $type, $value = null): self
    {
        $this->email->addContent($type, $value);
        return $this;
    }

    /**
     * Add a template id to a Mail object
     *
     * @param string $template_id The id of the template to be applied to this email
     * @throws TypeException
     */
    public function setTemplateId(string $template_id): self
    {

        $this->email->setTemplateId($template_id);
        return $this;
    }

    /**
     * Send an email
     *
     * Response Codes
     *
     * 200 - The email was successfully sent.
     * 202 - The email was successfully accepted for delivery.
     * 400 - Bad request. You did something wrong.
     * 401 - Unauthorized. Your API key is wrong..
     *
     *
     * @return bool - true if email was sent
     * @throws EmailException if email fails to send
     */
    public function send(): bool
    {
        $this->validateApiKey();
        /**
         * @var Response $response
         */
        $this->response = $this->sendGrid->send($this->email);
        $status = $this->response->statusCode();
        if ($status != 200 && $status != 202) {
            throw new EmailException('Failed to send email. Http response code was: ' . $this->response->statusCode());
        }

        return true;
    }



    /**
     * Send verify integration email
     *
     * @param To|array $verifiedFrom - array of email and name [email, name]
     * @return boolean
     */
    public function sendVerifyIntegrationEmail($verifiedFrom): bool
    {

        list($email, $name) = $verifiedFrom;
        $this->setFrom($email, $name)
        ->setSubject("Sending with SendGrid is Fun")
        ->addTo("test@example.com", "Example User")
        ->addContent("text/plain", "and easy to do anywhere, even with PHP")
        ->addContent(
            "text/html",
            "<strong>and easy to do anywhere, even with PHP</strong>"
        );
        return $this->send();

    }

    /**
     * Check if response is available
     *
     * @return boolean
     * @throws EmailException
     */
    private function isResponseAvailable(): bool
    {
        if(isset($this->response)) {
            return true;
        }
        throw new EmailException('Response not available, email has not been sent.');
    }

    /**
     * Get response
     *
     * Response object contains the status code, headers, and body of the response
     *
     * You can use the response object to check the status code and headers and body
     * of the response.
     *
     * @example
     *
     * $response = $email->getResponse();
     * $statusCode = $response->statusCode();
     * $headers = $response->headers();
     * $body = $response->body();
     *
     * @return Response
     */
    public function getResponse(): Response
    {
        $this->isResponseAvailable();
        return $this->response;
    }

}

class EmailException extends \Exception
{
    public function __construct(string $message, $code = 0, \Throwable $previous = null)
    {
        $message = self::class . ":  $message";
        parent::__construct($message, $code, $previous);
    }
}
