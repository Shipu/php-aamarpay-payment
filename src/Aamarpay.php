<?php

namespace Shipu\Aamarpay;

use Shipu\Aamarpay\Exceptions\RouteOrUrlNotFound;

class Aamarpay extends AbstractApi
{
    protected $config;
    protected $params = [];

    protected function setBaseUrl()
    {
        if ( $this->config['sandbox'] ) {
            return 'https://sandbox.aamarpay.com/index.php';
        } else {
            return 'https://secure.aamarpay.com/index.php';
        }
    }

    /**
     * Payment constructor.
     *
     * @param $config
     */
    public function __construct( $config )
    {
        $this->config = $config;
        $this->preBuildParameter();
        parent::__construct();
    }

    /**
     * Getting Payment Url
     *
     * @return string
     */
    public function paymentUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Set Customer Info
     *
     * @param array $info
     *
     * @return $this
     */
    public function customer( $info = [] )
    {
        $this->params = array_merge($this->params, $info);

        return $this;
    }

    /**
     * Set Transaction Id
     *
     * @param null $transaction
     *
     * @return $this
     */
    public function transactionId( $transaction = null )
    {
        if ( is_null($transaction) ) {
            $transaction = $this->generateTransaction();
        }

        $this->params[ 'tran_id' ] = $transaction;

        return $this;
    }

    /**
     * Set Payment Amount
     *
     * @param $amount
     *
     * @return $this
     */
    public function amount( $amount )
    {
        $this->params[ 'amount' ] = $amount;

        return $this;
    }

    /**
     * Set Product Description
     *
     * @param null $description
     *
     * @return $this
     */
    public function product( $description = null )
    {
        $this->params[ 'desc' ] = $description;

        return $this;
    }

    /**
     * Getting Redirect Url
     *
     * @return $this
     */
    public function redirectUrl()
    {
        $redirectUrl = $this->config[ 'redirect_url' ];
        $this->params[ 'success_url' ] = $this->routeOrUrl($redirectUrl[ 'success' ]);
        $this->params[ 'fail_url' ] = $this->routeOrUrl($redirectUrl[ 'cancel' ]);
        $this->params[ 'cancel_url' ] = $this->routeOrUrl($redirectUrl[ 'cancel' ]);

        return $this;
    }

    /**
     * Set Currency
     *
     * @param string $currency
     *
     * @return $this
     */
    public function currency( $currency = 'BDT' )
    {
        $this->params[ 'currency' ] = $currency;

        return $this;
    }

    /**
     * Generate Transactions
     *
     * @return string
     */
    public function generateTransaction()
    {
        return uniqid(true) . microtime(true);
    }

    /**
     * Getting Hidden Input Value
     *
     * @return string
     */
    public function hiddenValue()
    {
        if ( !isset($this->params[ 'tran_id' ]) ) {
            $this->params[ 'tran_id' ] = $this->generateTransaction();
        }

        $formString = '';
        foreach ( $this->params as $name => $value ) {
            $formString .= "<input type=\"hidden\" name=\"" . $name . "\" value=\"" . $value . "\" />";
        }

        return $formString;
    }

    /**
     * Checking Valid Response Request with amount and transactionId
     *
     * @param $request
     * @param null $amount
     *
     * @return bool
     * @internal param null $transactionId
     *
     */
    public function valid( $request, $amount = null)
    {
        if (
            $this->statusFieldValidity($request) &&
            $this->aamarpayValidationApiResponse($request, $amount)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Checking Valid Amount in response
     *
     * @param $request
     * @param $amount
     *
     * @return bool
     */
    public function validAmount( $request, $amount )
    {
        return $this->aamarpayValidationApiResponse($request, $amount);
    }

    /**
     * Checking Valid Status
     *
     * @param $request
     *
     * @return bool
     */
    private function statusFieldValidity( $request )
    {
        if ( $request[ 'status' ] == 'VALID' ) {
            return true;
        }

        return false;
    }

    /**
     * Checking Valid Response
     *
     * @param $request
     * @param null $amount
     *
     * @return bool
     * @internal param null $transactionId
     *
     */
    private function aamarpayValidationApiResponse( $request, $amount = null )
    {
        $flag = true;
        $status = false;

        if ( $request->pay_status == 'Successful') {
            $status = true;
        }

        if ( ( !is_null($amount) && $request->amount != $amount ) || !$status ) {
            $flag = false;
        }

        return $flag;
    }

    /**
     * Getting Route or url
     *
     * @param $array
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     * @throws RouteOrUrlNotFound
     */
    private function routeOrUrl( $array )
    {
        if (isset($array[ 'route' ]) && !is_null($array[ 'route' ]) && $array[ 'route' ] != '' ) {
            return function_exists('route') ? route($array[ 'route' ]) : $array[ 'route' ];
        } elseif ( isset($array[ 'url' ]) && !is_null($array[ 'url' ]) && $array[ 'url' ] != '' ) {
            return function_exists('url') ? url($array[ 'url' ]) : $array[ 'url' ];
        }

        throw new RouteOrUrlNotFound();
    }

    /**
     * Pre build Parameter for post request
     */
    private function preBuildParameter()
    {
        $this->params[ 'store_id' ] = $this->config[ 'store_id' ];
        $this->params[ 'signature_key' ] = $this->config[ 'signature_key' ];
        $this->currency();
        $this->redirectUrl();
    }

}
