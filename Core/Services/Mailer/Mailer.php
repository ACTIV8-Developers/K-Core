<?php

namespace Core\Services\Mailer;

use Swift_Attachment;

/**
 * Class Mailer
 */
class Mailer
{
    /** @var array $config */
    protected $config = [];
    /** @var null|\Swift_SmtpTransport $transport */
    protected $transport = null;

    /**
     * Mailer constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        // Create the Transport

        $this->transport = (new \Swift_SmtpTransport($this->config['host'], $this->config['port'], $this->config['security']))
            ->setUsername($this->config['email'])
            ->setPassword($this->config['password']);
    }

    /**
     * @param $target
     * @param $subject
     * @param $content
     * @param array $from
     * @param null $attach
     * @return int
     */
    public function send($target, $subject, $content, $from = [FROM_EMAIL=> FROM_NAME], $attach = null)
    {
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($this->transport);

        // Create a message
        $message = new \Swift_Message($subject);

        $message->setContentType("text/html");
        $message->setFrom($from)
            ->setTo([$target])
            ->setBody($content);

        // Attachment
        if ($attach !== null) {
            $message->attach(
                Swift_Attachment::fromPath($attach['path'])->setFilename($attach['name'])
            );
        }

        // Send the message
        try {
            $result = $mailer->send($message);
        } catch (\Exception $e) {
            return false;
        }
        return $result;
    }
}