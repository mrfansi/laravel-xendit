<?php

namespace Mrfansi\LaravelXendit\Data\Invoice;

use Illuminate\Support\Str;
use Mrfansi\LaravelXendit\Enums\Currency;
use Mrfansi\LaravelXendit\Enums\InvoiceStatus;
use Mrfansi\LaravelXendit\Enums\Locale;
use Mrfansi\LaravelXendit\Enums\PaymentMethod;
use Mrfansi\LaravelXendit\Enums\ReminderTimeUnit;
use Mrfansi\LaravelXendit\Exceptions\ValidationException;

class InvoiceResponse extends InvoiceData
{
    public function __construct(
        public string $id,
        public string $userId,
        public string $externalId,
        public string $status,
        public string $merchantName,
        public int $amount,
        public ?string $description = null,
        public ?InvoiceCustomerData $customer = null,
        public ?array $customerNotificationPreference = null,
        public ?int $invoiceDuration = null,
        public ?string $successRedirectUrl = null,
        public ?string $failureRedirectUrl = null,
        public ?array $paymentMethods = null,
        public ?Currency $currency = null,
        public ?string $callbackVirtualAccountId = null,
        public ?string $midLabel = null,
        public ?ReminderTimeUnit $reminderTimeUnit = null,
        public ?int $reminderTime = null,
        public ?Locale $locale = null,
        public ?array $items = null,
        public ?array $fees = null,
        public ?bool $shouldAuthenticateCreditCard = null,
        public ?array $channelProperties = null,
        public ?array $metadata = null,
        public ?string $merchantProfilePictureUrl = null,
        public ?string $payerEmail = null,
        public ?string $invoiceUrl = null,
        public ?array $availableBanks = null,
        public ?array $availableRetailOutlets = null,
        public ?bool $shouldExcludeCreditCard = null,
        public ?bool $shouldSendEmail = null,
        public ?string $created = null,
        public ?string $updated = null,
        public ?string $paidAt = null,
        public ?string $creditCardChargeId = null,
        public ?string $paymentMethod = null,
        public ?string $paymentChannel = null,
        public ?string $paymentDestination = null,
        public ?bool $fixedVa = null,
        public ?array $paymentDetails = null
    ) {
        parent::__construct($externalId, $amount, $description, $customer, $customerNotificationPreference, $invoiceDuration, $successRedirectUrl, $failureRedirectUrl, $paymentMethods, $currency, $callbackVirtualAccountId, $midLabel, $reminderTimeUnit, $reminderTime, $locale, $items, $fees, $shouldAuthenticateCreditCard, $channelProperties, $metadata);
        $this->validateResponse();
    }

    /**
     * @throws ValidationException
     */
    private function validateResponse(): void
    {
        // Validate ID
        if (strlen($this->id) < 1) {
            throw new ValidationException('Invoice ID is required');
        }

        // Validate User ID
        if (strlen($this->userId) < 1) {
            throw new ValidationException('User ID is required');
        }

        // Validate Status
        if (! in_array($this->status, array_map(fn ($case) => $case->value, InvoiceStatus::cases()))) {
            throw new ValidationException("Invalid invoice status: {$this->status}");
        }

        // Validate Merchant Name
        if (strlen($this->merchantName) < 1) {
            throw new ValidationException('Merchant name is required');
        }

        // Validate Additional Optional Fields
        if ($this->merchantProfilePictureUrl !== null && ! filter_var($this->merchantProfilePictureUrl, FILTER_VALIDATE_URL)) {
            throw new ValidationException('Invalid merchant profile picture URL');
        }

        if ($this->payerEmail !== null && ! filter_var($this->payerEmail, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Invalid payer email');
        }

        if ($this->invoiceUrl !== null && ! filter_var($this->invoiceUrl, FILTER_VALIDATE_URL)) {
            throw new ValidationException('Invalid invoice URL');
        }

        if ($this->paymentMethod !== null && ! in_array($this->paymentMethod, array_map(fn ($case) => $case->value, PaymentMethod::cases()))) {
            throw new ValidationException("Invalid payment method: {$this->paymentMethod}");
        }
    }

    public static function fromArray(array $data): self
    {
        $customer = isset($data['customer']) ? InvoiceCustomerData::fromArray($data['customer']) : null;

        return new self(
            id: $data['id'],
            userId: $data['user_id'],
            externalId: $data['external_id'],
            status: $data['status'],
            merchantName: $data['merchant_name'],
            amount: $data['amount'],
            description: $data['description'] ?? null,
            customer: $customer,
            customerNotificationPreference: $data['customer_notification_preference'] ?? null,
            invoiceDuration: $data['invoice_duration'] ?? null,
            successRedirectUrl: $data['success_redirect_url'] ?? null,
            failureRedirectUrl: $data['failure_redirect_url'] ?? null,
            paymentMethods: $data['payment_methods'] ?? null,
            currency: isset($data['currency']) ? Currency::from($data['currency']) : null,
            callbackVirtualAccountId: $data['callback_virtual_account_id'] ?? null,
            midLabel: $data['mid_label'] ?? null,
            reminderTimeUnit: isset($data['reminder_time_unit']) ? ReminderTimeUnit::from($data['reminder_time_unit']) : null,
            reminderTime: $data['reminder_time'] ?? null,
            locale: isset($data['locale']) ? Locale::from($data['locale']) : null,
            items: $data['items'] ?? null,
            fees: $data['fees'] ?? null,
            shouldAuthenticateCreditCard: $data['should_authenticate_credit_card'] ?? null,
            channelProperties: $data['channel_properties'] ?? null,
            metadata: $data['metadata'] ?? null,
            merchantProfilePictureUrl: $data['merchant_profile_picture_url'] ?? null,
            payerEmail: $data['payer_email'] ?? null,
            invoiceUrl: $data['invoice_url'] ?? null,
            availableBanks: $data['available_banks'] ?? null,
            availableRetailOutlets: $data['available_retail_outlets'] ?? null,
            shouldExcludeCreditCard: $data['should_exclude_credit_card'] ?? null,
            shouldSendEmail: $data['should_send_email'] ?? null,
            created: $data['created'] ?? null,
            updated: $data['updated'] ?? null,
            paidAt: $data['paid_at'] ?? null,
            creditCardChargeId: $data['credit_card_charge_id'] ?? null,
            paymentMethod: $data['payment_method'] ?? null,
            paymentChannel: $data['payment_channel'] ?? null,
            paymentDestination: $data['payment_destination'] ?? null,
            fixedVa: $data['fixed_va'] ?? null,
            paymentDetails: $data['payment_details'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->userId,
            'external_id' => $this->externalId,
            'status' => $this->status,
            'merchant_name' => $this->merchantName,
            'amount' => $this->amount,
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->currency !== null) {
            $data['currency'] = $this->currency->value;
        }

        // Add optional fields
        $optionalFields = [
            'merchant_profile_picture_url' => $this->merchantProfilePictureUrl,
            'payer_email' => $this->payerEmail,
            'invoice_url' => $this->invoiceUrl,
            'available_banks' => $this->availableBanks,
            'available_retail_outlets' => $this->availableRetailOutlets,
            'should_exclude_credit_card' => $this->shouldExcludeCreditCard,
            'should_send_email' => $this->shouldSendEmail,
            'created' => $this->created,
            'updated' => $this->updated,
            'paid_at' => $this->paidAt,
            'credit_card_charge_id' => $this->creditCardChargeId,
            'payment_method' => $this->paymentMethod,
            'payment_channel' => $this->paymentChannel,
            'payment_destination' => $this->paymentDestination,
            'fixed_va' => $this->fixedVa,
            'payment_details' => $this->paymentDetails,
        ];

        foreach ($optionalFields as $key => $value) {
            if ($value !== null) {
                $data[$key] = $value;
            }
        }

        // Add remaining parent fields
        $parentData = parent::toArray();
        foreach ($parentData as $key => $value) {
            if (! isset($data[$key]) && $value !== null) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    public function toTable(): array
    {
        $rows = collect($this->toArray());

        return $rows->mapWithKeys(function ($value, $key) {
            if ($key == 'id') {
                return [Str::upper($key) => $value];
            }

            return [Str::headline($key) => $value];
        })->all();
    }
}
