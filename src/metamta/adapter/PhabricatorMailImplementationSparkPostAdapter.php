<?php

/**
 * Mail adapter that uses SparkPost's RESTful API to deliver email.
 *
 * @author  Hidde Beydals <hello@hidde.co>
 * @license http://www.apache.org/licenses/LICENSE-2.0 APLv2
 */
final class PhabricatorMailImplementationSparkPostAdapter
    extends PhabricatorMailImplementationAdapter {

    /**
     * @var array
     */
    private $params = array();

    /**
     * Set the email sender.
     *
     * @param  string $email
     * @param  string $name
     *
     * @return $this
     */
    public function setFrom($email, $name = '') {
        $this->params['content']['from']['email'] = $email;
        $this->params['content']['from']['name'] = $name;

        return $this;
    }

    /**
     * Add a reply to address to the email.
     *
     * @param string $email
     * @param string $name
     *
     * @return $this
     */
    public function addReplyTo($email, $name = '') {
        $this->params['content']['reply_to'] = "{$name} <{$email}>";

        return $this;
    }

    /**
     * Add a list of recipients.
     *
     * @param array $emails
     *
     * @return $this
     */
    public function addTos(array $emails) {
        foreach ($emails as $email) {
            $this->params['recipients'][]['address']['email'] = $email;
        }

        return $this;
    }

    /**
     * Add a list of carbon copy recipients.
     *
     * @param array $emails
     *
     * @return $this
     */
    public function addCCs(array $emails) {
        foreach ($emails as $email) {
            $this->params['content']['headers']['CC'][] = $email;
        }

        return $this;
    }

    /**
     * Add an attachment to the email.
     *
     * @param mixed  $data
     * @param string $filename
     * @param string $mimetype
     *
     * @return $this
     */
    public function addAttachment($data, $filename, $mimetype) {
        $this->params['content']['attachments'][] = array(
            'type' => $mimetype,
            'name' => $filename,
            'data' => $data
        );

        return $this;
    }

    /**
     * Add a header to the email.
     *
     * @param string $header_name
     * @param mixed  $header_value
     *
     * @return $this
     */
    public function addHeader($header_name, $header_value) {
        $this->params['headers'][$header_name] = $header_value;

        return $this;
    }

    /**
     * Set the text body of the email.
     *
     * @param string $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->params['content']['text'] = $body;

        return $this;
    }

    /**
     * Set the HTML body of the email.
     *
     * @param string $html_body
     *
     * @return $this
     */
    public function setHTMLBody($html_body) {
        $this->params['content']['html'] = $html_body;

        return $this;
    }

    /**
     * Set the subject of the email.
     *
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject) {
        $this->params['content']['subject'] = $subject;

        return $this;
    }

    /**
     * Return whether or not SparkPost supports a message ID header.
     *
     * @return bool
     */
    public function supportsMessageIDHeader() {
        return false;
    }

    /**
     * Send the email.
     */
    public function send() {
        $key = PhabricatorEnv::getEnvConfig('sparkpost.api-key');

        if (!$key) {
            throw new Exception(pht("Configure '%s' to use SparkPost for mail delivery.", 'sparkpost.api-key'));
        }

        $future = new HTTPSFuture(
            "https://api.sparkpost.com/api/v1/transmissions",
            json_encode($this->params));
        $future
            ->addHeader('Authorization', $key)
            ->addHeader('Content-Type', 'application/json')
            ->setMethod('POST');

        list($body) = $future->resolvex();

        try {
            $response = phutil_json_decode($body);
        } catch (PhutilJSONParserException $e) {
            throw new PhutilProxyException(pht('Failed to JSON decode response.'), $e);
        }

        if (!idx($response['results'], 'id')) {
            $errors = $response['errors'];

            foreach ($errors as $error) {
                throw new Exception(pht('Request failed with errors: %s.', $error['message']));
            }
        }

        return true;
    }
}