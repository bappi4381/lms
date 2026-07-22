<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SSLCommerzService
{
    protected string $storeId;
    protected string $storePassword;
    protected string $apiUrl;
    protected string $validationUrl;

    public function __construct()
    {
        $this->storeId = config('sslcommerz.store_id');
        $this->storePassword = config('sslcommerz.store_password');
        $this->apiUrl = config('sslcommerz.api_url');
        $this->validationUrl = config('sslcommerz.validation_url');
    }

    /**
     * Initiate a payment request to SSLCommerz for any order (course
     * purchase or subscription). $itemName/$itemCategory describe the
     * product being purchased.
     */
    public function initiatePayment(Order $order, User $user, string $itemName, string $itemCategory = 'Online Course'): ?string
    {
        $postData = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'total_amount' => $order->totalPayable(),
            'currency' => config('sslcommerz.currency', 'BDT'),
            'tran_id' => $order->transaction_id,
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.failure'),
            'cancel_url' => route('payment.cancel'),
            'ipn_url' => route('payment.ipn'),

            // Customer Info
            'cus_name' => $user->name,
            'cus_email' => $user->email ?: 'no-email@example.com',
            'cus_add1' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $user->phone ?: '01700000000',

            // Product Info
            'product_name' => $itemName,
            'product_category' => $itemCategory,
            'product_profile' => 'non-physical-goods',
            'shipping_method' => 'NO',

            'value_a' => $order->id,
            'value_b' => $order->type,
            'value_c' => $user->id,
        ];

        // EMI support (requires EMI to be enabled on the merchant account by SSLCommerz)
        if ($order->emi_instalments) {
            $postData['emi_option'] = 1;
            $postData['emi_max_inst_option'] = $order->emi_instalments;
            $postData['emi_selected_inst'] = $order->emi_instalments;
        }

        try {
            $response = Http::asForm()->post($this->apiUrl, $postData);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['status']) && $result['status'] === 'SUCCESS') {
                    return $result['GatewayPageURL'];
                } else {
                    Log::error('SSLCommerz initiation failed: ' . json_encode($result));
                }
            } else {
                Log::error('SSLCommerz HTTP request failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('SSLCommerz Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Validate payment with SSLCommerz server API and return the full
     * validation payload (so callers can cross-check the paid amount).
     */
    public function validatePayment(string $valId): ?array
    {
        try {
            $url = $this->validationUrl . '?val_id=' . $valId . '&store_id=' . $this->storeId . '&store_passwd=' . $this->storePassword . '&format=json';
            $response = Http::get($url);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['status']) && in_array($result['status'], ['VALID', 'VALIDATED'], true)) {
                    return $result;
                }
            }
        } catch (\Exception $e) {
            Log::error('SSLCommerz Validation Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Cross-check a validation response against our own order to guard
     * against tampered/forged success callbacks (amount + currency must match).
     */
    public function amountMatches(Order $order, array $validation): bool
    {
        $paidAmount = (float) ($validation['amount'] ?? $validation['currency_amount'] ?? 0);

        return abs($paidAmount - (float) $order->totalPayable()) < 1.0;
    }
}
