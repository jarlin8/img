<?php

namespace ContentEgg\application\libs\amazon;

defined('\ABSPATH') || exit;

use ContentEgg\application\libs\RestClient;

use function ContentEgg\prn;
use function ContentEgg\prnx;

/**
 * PHP interface to Amazon Creators API (Catalog v1)
 * @link: https://affiliate-program.amazon.com/creatorsapi/docs/en-us/introduction
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */

class AmazonCreatorsApi extends RestClient
{
    /** @var string|null */
    private $credential_id;

    /** @var string|null */
    private $credential_secret;

    /** @var string|null */
    private $credential_version;

    /** @var string|null */
    private $access_token;

    /** @var string 'v2' or 'v3' */
    private $major_version = 'v2';

    /** @var string */
    private $associate_tag;

    /** @var string */
    private $locale;

    /** @var string */
    private $marketplace;

    /** @var array */
    protected $_responseTypes = array('json');

    public function __construct($credential_id, $credential_secret, $associate_tag, $locale = 'us')
    {
        $this->credential_id = $credential_id;
        $this->credential_secret = $credential_secret;

        $this->setAssociateTag($associate_tag);
        $this->setLocale($locale);
        $this->setResponseType('json');
    }

    public function setAssociateTag($associate_tag)
    {
        $this->associate_tag = (string) $associate_tag;
    }

    public function getAssociateTag()
    {
        return $this->associate_tag;
    }

    public function setLocale($locale)
    {
        $this->locale = (string) $locale;

        // Base URI for Creators API
        $this->setUri(AmazonLocales::getCreatorsApiBaseUrl());

        $this->marketplace = AmazonLocales::getCreatorsMarketplace($this->locale);
        $this->credential_version = AmazonLocales::getCreatorsCredentialVersion($this->locale, $this->major_version);
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setMarketplace($marketplace)
    {
        $this->marketplace = (string) $marketplace;
    }

    public function getMarketplace()
    {
        return $this->marketplace;
    }

    public function setCredentialVersion($version)
    {
        $this->credential_version = (string) $version;
    }

    public function getCredentialVersion()
    {
        return $this->credential_version;
    }

    public function setAccessToken($token)
    {
        $this->access_token = $token !== null ? (string) $token : null;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }

    public function setMajorVersion($version)
    {
        $this->major_version = (string) $version;

        // Refresh credential_version for current locale
        if ($this->locale)
        {
            $this->credential_version = AmazonLocales::getCreatorsCredentialVersion($this->locale, $this->major_version);
        }
    }

    public function getMajorVersion()
    {
        return $this->major_version;
    }

    /**
     * OAuth2 token request (client_credentials).
     * Module should cache it (transient) and inject via setAccessToken().
     *
     * @param string|null $scope
     * @return array decoded response
     */
    public function requestAccessToken($scope = null)
    {
        $token_endpoint = AmazonLocales::getCreatorsTokenEndpoint($this->locale, $this->major_version);

        if ($this->major_version === 'v3')
        {
            // v3.x: Login with Amazon (LwA) — JSON body, credentials inline
            $scope = $scope ?: 'creatorsapi::default';

            $body = json_encode(array(
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->credential_id,
                'client_secret' => $this->credential_secret,
                'scope'         => $scope,
            ));

            $this->setCustomHeaders(array(
                'Content-Type' => 'application/json',
            ));
        }
        else
        {
            // v2.x: Cognito — form-encoded, Basic auth header
            $scope = $scope ?: 'creatorsapi/default';

            $body = http_build_query(array(
                'grant_type' => 'client_credentials',
                'scope'      => $scope,
            ), '', '&');

            $this->setCustomHeaders(array(
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode($this->credential_id . ':' . $this->credential_secret),
            ));
        }

        $response = parent::restPost($token_endpoint, $body);

        return $this->_decodeResponse($response);
    }

    /**
     * Creators API: searchItems
     */
    public function SearchItems(array $payload)
    {
        $payload = CreatorsApiPayload::normalize($payload, array(
            'associate_tag' => $this->associate_tag,
            'marketplace'   => $this->marketplace,
        ));

        $response = $this->restPost('/catalog/v1/searchItems', $payload);
        return $this->_decodeResponse($response);
    }

    /**
     * Creators API: getItems
     */
    public function GetItems(array $payload)
    {
        $payload = CreatorsApiPayload::normalize($payload, array(
            'associate_tag' => $this->associate_tag,
            'marketplace'   => $this->marketplace,
            'defaults'      => array('itemIdType' => 'ASIN'),
        ));

        $response = $this->restPost('/catalog/v1/getItems', $payload);
        return $this->_decodeResponse($response);
    }

    /**
     * Creators API: getVariations
     */
    public function GetVariations(array $payload)
    {
        $payload = CreatorsApiPayload::normalize($payload, array(
            'associate_tag' => $this->associate_tag,
            'marketplace'   => $this->marketplace,
        ));

        $response = $this->restPost('/catalog/v1/getVariations', $payload);
        return $this->_decodeResponse($response);
    }

    /**
     * REST POST override for Creators catalog calls:
     * - JSON body
     * - x-marketplace header
     * - Bearer token header (if set)
     */
    public function restPost($path, $data = null, $enctype = null, $opts = array())
    {
        $headers = array(
            'Content-Type'  => 'application/json',
            'x-marketplace' => $this->marketplace,
        );

        if (!empty($this->access_token))
        {
            if ($this->major_version === 'v3')
            {
                // v3.x: no Version suffix
                $headers['Authorization'] = 'Bearer ' . $this->access_token;
            }
            elseif (!empty($this->credential_version))
            {
                // v2.x: include Version
                $headers['Authorization'] = 'Bearer ' . $this->access_token . ', Version ' . $this->credential_version;
            }
        }

        $this->setCustomHeaders($headers);

        $json = $data !== null ? json_encode($data) : null;

        return parent::restPost($path, $json);
    }

    protected function myErrorHandler($response)
    {
        $response_code = (int) \wp_remote_retrieve_response_code($response);
        $body = \wp_remote_retrieve_body($response);
        $data = $this->_decodeResponse($body);

        // If not JSON/array, fall back to parent handler
        if (!is_array($data))
        {
            parent::myErrorHandler($response);
            return;
        }

        // Creators API error object format:
        // {
        //   "type": "ValidationException",
        //   "message": "Human readable message",
        //   "reason": "MachineReason", // optional for some types
        //   ... optional: resourceType, resourceId, fieldList, retryAfterSeconds ...
        // }
        $type = isset($data['type']) ? (string) $data['type'] : '';
        $msg  = isset($data['message']) ? (string) $data['message'] : '';
        $reason = isset($data['reason']) ? (string) $data['reason'] : '';

        // In rare cases HTTP 200 can still contain an error object (per docs: "Processing of Errors")
        // We treat presence of "type" or "message" as an error payload.
        if ($type === '' && $msg === '')
        {
            // No Creators-style error object detected; let parent handle other cases
            parent::myErrorHandler($response);
            return;
        }

        // Do not throw on "no items found" for not-found resources (treat as empty results)
        if ($type === 'ResourceNotFoundException')
        {
            return;
        }

        $parts = array();

        // Prefix: Type[:Reason]
        if ($type !== '')
        {
            $parts[] = ($reason !== '') ? ($type . ':' . $reason) : $type;
        }

        // Message
        if ($msg !== '')
        {
            $parts[] = $msg;
        }

        // Optional: fieldList (ValidationException)
        if (isset($data['fieldList']) && is_array($data['fieldList']) && $data['fieldList'])
        {
            $fields = array();

            foreach ($data['fieldList'] as $f)
            {
                if (is_string($f) && $f !== '')
                {
                    $fields[] = $f;
                    continue;
                }
                if (is_array($f))
                {
                    // Try common shapes
                    if (!empty($f['field']))
                    {
                        $fields[] = (string) $f['field'];
                    }
                    elseif (!empty($f['name']))
                    {
                        $fields[] = (string) $f['name'];
                    }
                }
            }

            if ($fields)
            {
                $parts[] = 'Fields: ' . implode(', ', $fields);
            }
        }

        // Optional: resource info (ResourceNotFoundException often includes this, but might appear elsewhere)
        if (!empty($data['resourceType']) || !empty($data['resourceId']))
        {
            $rt = !empty($data['resourceType']) ? (string) $data['resourceType'] : '';
            $rid = !empty($data['resourceId']) ? (string) $data['resourceId'] : '';
            $parts[] = 'Resource: ' . trim($rt . ' ' . $rid);
        }

        // Optional: retryAfterSeconds (ThrottleException)
        if ($type === 'ThrottleException')
        {
            $retry = isset($data['retryAfterSeconds']) ? (int) $data['retryAfterSeconds'] : 0;
            if ($retry > 0)
            {
                $parts[] = 'RetryAfterSeconds: ' . $retry;
            }
        }

        $error_mess = trim(implode(' - ', $parts));

        // If message is empty for some reason, fall back to generic
        if ($error_mess === '')
        {
            $error_mess = 'Creators API error.';
        }

        throw new \Exception(esc_html($error_mess), (int)$response_code);
    }
}
