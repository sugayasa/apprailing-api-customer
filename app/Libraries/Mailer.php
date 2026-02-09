<?php
namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    protected $mail;
    protected $config;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->loadConfig();
        $this->setupMailer();
    }

    /**
     * Load email configuration from environment or default values
     */
    private function loadConfig()
    {
        $this->config = [
            'smtp_host'     => EMAIL_SMTP_HOST,
            'smtp_port'     => EMAIL_SMTP_PORT,
            'smtp_user'     => EMAIL_SMTP_USER,
            'smtp_password' => EMAIL_SMTP_PASSWORD,
            'smtp_crypto'   => EMAIL_SMTP_CRYPTO,
            'from_address'  => EMAIL_FROM_ADDRESS,
            'from_name'     => EMAIL_FROM_NAME,
            'smtp_debug'    => EMAIL_DEBUG,
        ];
    }

    /**
     * Setup PHPMailer with configuration
     */
    private function setupMailer()
    {
        try {
            // Server settings
            $this->mail->SMTPDebug  = $this->config['smtp_debug'];
            $this->mail->isSMTP();
            $this->mail->Host       = $this->config['smtp_host'];
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $this->config['smtp_user'];
            $this->mail->Password   = $this->config['smtp_password'];
            $this->mail->SMTPSecure = $this->config['smtp_crypto'];
            $this->mail->Port       = $this->config['smtp_port'];
            $this->mail->CharSet    = 'UTF-8';

            // Set default from
            $this->mail->setFrom($this->config['from_address'], $this->config['from_name']);
        } catch (Exception $e) {
            log_message('error', 'Mailer Setup Error: ' . $e->getMessage());
        }
    }

    /**
     * Send email with HTML content
     *
     * @param string $toAddress Recipient email address
     * @param string $toName Recipient name
     * @param string $subject Email subject
     * @param string $htmlContent HTML content of the email
     * @param array $options Optional parameters (cc, bcc, attachments, replyTo)
     * @return array Returns ['success' => true/false, 'message' => '...']
     */
    public function send($toAddress, $toName, $subject, $htmlContent, $options = [])
    {
        try {
            // Recipients
            $this->mail->addAddress($toAddress, $toName);

            // Add CC if provided
            if (isset($options['cc']) && is_array($options['cc'])) {
                foreach ($options['cc'] as $cc) {
                    $this->mail->addCC($cc['email'], $cc['name'] ?? '');
                }
            }

            // Add BCC if provided
            if (isset($options['bcc']) && is_array($options['bcc'])) {
                foreach ($options['bcc'] as $bcc) {
                    $this->mail->addBCC($bcc['email'], $bcc['name'] ?? '');
                }
            }

            // Add reply-to if provided
            if (isset($options['replyTo'])) {
                $this->mail->addReplyTo(
                    $options['replyTo']['email'],
                    $options['replyTo']['name'] ?? ''
                );
            }

            // Add attachments if provided
            if (isset($options['attachments']) && is_array($options['attachments'])) {
                foreach ($options['attachments'] as $attachment) {
                    if (file_exists($attachment['path'])) {
                        $this->mail->addAttachment(
                            $attachment['path'],
                            $attachment['name'] ?? ''
                        );
                    }
                }
            }

            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $htmlContent;
            $this->mail->AltBody = strip_tags($htmlContent);

            // Send email
            $this->mail->send();
            
            // Clear addresses for next email
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            return [
                'success' => true,
                'message' => 'Email has been sent successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Email could not be sent. Error: ' . $this->mail->ErrorInfo
            ];
        }
    }

    /**
     * Send bulk emails
     *
     * @param array $recipients Array of recipients [['email' => '', 'name' => ''], ...]
     * @param string $subject Email subject
     * @param string $htmlContent HTML content of the email
     * @return array Returns ['success' => count, 'failed' => count, 'errors' => [...]]
     */
    public function sendBulk($recipients, $subject, $htmlContent)
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($recipients as $recipient) {
            $result = $this->send(
                $recipient['email'],
                $recipient['name'] ?? '',
                $subject,
                $htmlContent
            );

            if ($result['success']) {
                $successCount++;
            } else {
                $failedCount++;
                $errors[] = [
                    'email' => $recipient['email'],
                    'error' => $result['message']
                ];
            }
        }

        return [
            'success' => $successCount,
            'failed' => $failedCount,
            'errors' => $errors
        ];
    }

    /**
     * Test email configuration
     *
     * @return array Returns ['success' => true/false, 'message' => '...']
     */
    public function testConnection()
    {
        try {
            $this->mail->smtpConnect();
            $this->mail->smtpClose();

            return [
                'success' => true,
                'message' => 'SMTP connection successful'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'SMTP connection failed: ' . $e->getMessage()
            ];
        }
    }
}