<?php

namespace ContentEgg\application\libs\solutenetwork;

defined('\ABSPATH') || exit;

use ContentEgg\application\libs\RestClient;

use function ContentEgg\prnx;

/**
 * SoluteApi class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 *
 * @link: https://api.solutenetwork.com/content/v3/ui/#/
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'RestClient.php';

class SoluteApi extends RestClient
{
    const API_URI_BASE = 'https://api.solutenetwork.com/content/v3';

    private $user;
    private $password;

    protected $_responseTypes = array(
        'json',
    );

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->setResponseType('json');
        $this->setUri(self::API_URI_BASE);
    }

    public function search($keyword, array $options)
    {
        $options['q'] = $keyword;
        $response = $this->restGet('/search-results', $options);

        return $this->_decodeResponse($response);
    }

    public function getOffersByIds($offerIds)
    {
        if (is_array($offerIds))
        {
            $offerIds = implode(',', array_filter(array_map('trim', array_map('strval', $offerIds))));
        }
        else
        {
            $offerIds = trim((string) $offerIds);
        }

        if ($offerIds === '')
        {
            throw new \InvalidArgumentException('Offer IDs are required.');
        }

        $response = $this->restGet('/offers/' . $offerIds);

        return $this->_decodeResponse($response);
    }

    public function getOffersByProductIds($productIds, array $options = array())
    {
        if (is_array($productIds))
        {
            $productIds = implode(',', array_filter(array_map('trim', array_map('strval', $productIds))));
        }
        else
        {
            $productIds = trim((string) $productIds);
        }

        if ($productIds === '')
        {
            throw new \InvalidArgumentException('Product IDs are required.');
        }

        $response = $this->restGet('/products/' . $productIds . '/offers', $options);

        return $this->_decodeResponse($response);
    }

    public function getBaseProductsByIds($baseProductIds, array $options = array())
    {
        if (is_array($baseProductIds))
        {
            $baseProductIds = implode(',', array_filter(array_map('trim', array_map('strval', $baseProductIds))));
        }
        else
        {
            $baseProductIds = trim((string) $baseProductIds);
        }

        if ($baseProductIds === '')
        {
            throw new \InvalidArgumentException('Base product IDs are required.');
        }

        $response = $this->restGet('/baseproducts/' . $baseProductIds, $options);

        return $this->_decodeResponse($response);
    }

    public function restGet($path, ?array $query = null)
    {
        $authorization = 'Basic ' . base64_encode($this->user . ':' . $this->password);

        $this->setCustomHeaders(array(
            'Accept' => 'application/json',
            'Authorization' => $authorization,
        ));

        return parent::restGet($path, $query);
    }
}
