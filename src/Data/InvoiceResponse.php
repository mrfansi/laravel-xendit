<?php

namespace Mrfansi\LaravelXendit\Data;

use Carbon\Carbon;
use DateTimeInterface;
use Mrfansi\LaravelXendit\Data\Abstracts\AbstractDataTransferObject;
use Mrfansi\LaravelXendit\Data\CardChannel\CardChannelProperties;
use Mrfansi\LaravelXendit\Enums\Currency;
use Mrfansi\LaravelXendit\Enums\InvoiceStatus;
use Mrfansi\LaravelXendit\Traits\EnumToArray;
use ReflectionClass;

class InvoiceResponse extends AbstractDataTransferObject
{
    use EnumToArray;

    /**
     * Constructor for the InvoiceResponse class.
     *
     * @param  string|null  $id  Invoice ID generated by Xendit
     * @param  string|null  $external_id  Invoice ID in your server for reconciliation
     * @param  string|null  $user_id  Your Xendit Business ID
     * @param  InvoiceStatus|null  $status  Current status of the invoice
     * @param  string|null  $merchant_name  Your company or website name
     * @param  string|null  $merchant_profile_picture_url  URL to your company's profile picture
     * @param  float|null  $amount  Invoice amount
     * @param  string|null  $payer_email  Payer's email address
     * @param  string|null  $description  Invoice description
     * @param  string|null  $invoice_url  Public URL for this invoice
     * @param  DateTimeInterface|null  $expiry_date  Invoice expiry date and time
     * @param  array|null  $available_banks  Available payment methods through banks
     * @param  array|null  $available_retail_outlets  Available payment methods through retail outlets
     * @param  array|null  $available_ewallets  Available payment methods through e-wallets
     * @param  array|null  $available_qr_codes  Available payment methods through QR codes
     * @param  array|null  $available_direct_debits  Available payment methods through direct debits
     * @param  array|null  $available_paylaters  Available payment methods through paylaters
     * @param  bool|null  $should_exclude_credit_card  Should credit card be excluded in invoice UI
     * @param  bool|null  $should_send_email  Should send email notifications
     * @param  DateTimeInterface|null  $updated  Last update timestamp
     * @param  DateTimeInterface|null  $created  Creation timestamp
     * @param  string|null  $mid_label  MID label for credit card payments
     * @param  Currency|null  $currency  Invoice currency
     * @param  string|null  $success_redirect_url  URL to redirect after successful payment
     * @param  string|null  $failure_redirect_url  URL to redirect after failed payment
     * @param  string|null  $payment_methods  Payment methods
     * @param  array|null  $fixed_va  Fixed VA configuration
     * @param  array|null  $items  Items in the invoice
     * @param  array|null  $fees  Additional fees
     * @param  array|null  $payment_details  Payment details
     * @param  bool|null  $should_authenticate_credit_card  Should authenticate credit card payment
     * @param  CardChannelProperties|null  $channel_properties  Channel properties
     * @param  array|null  $metadata  Additional metadata
     * @param  Customer|null  $customer  Customer information
     */
    public function __construct(
        public ?string $id = null,
        public ?string $external_id = null,
        public ?string $user_id = null,
        public ?InvoiceStatus $status = null,
        public ?string $merchant_name = null,
        public ?string $merchant_profile_picture_url = null,
        public ?float $amount = null,
        public ?string $payer_email = null,
        public ?string $description = null,
        public ?string $invoice_url = null,
        public ?DateTimeInterface $expiry_date = null,
        public ?array $available_banks = null,
        public ?array $available_retail_outlets = null,
        public ?array $available_ewallets = null,
        public ?array $available_qr_codes = null,
        public ?array $available_direct_debits = null,
        public ?array $available_paylaters = null,
        public ?bool $should_exclude_credit_card = null,
        public ?bool $should_send_email = null,
        public ?DateTimeInterface $updated = null,
        public ?DateTimeInterface $created = null,
        public ?string $mid_label = null,
        public ?Currency $currency = null,
        public ?string $success_redirect_url = null,
        public ?string $failure_redirect_url = null,
        public ?string $payment_methods = null,
        public ?array $fixed_va = null,
        public ?array $items = null,
        public ?array $fees = null,
        public ?array $payment_details = null,
        public ?bool $should_authenticate_credit_card = null,
        public ?CardChannelProperties $channel_properties = null,
        public ?array $metadata = null,
        public ?Customer $customer = null,
    ) {}

    /**
     * Create an instance from array data
     *
     * @param  array<string, mixed>|null  $data
     */
    public static function from(?array $data): ?static
    {
        if ($data === null) {
            return null;
        }

        return static::fromArray($data);
    }

    /**
     * Create an instance from array data
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        $instance = (new ReflectionClass(static::class))->newInstance();

        $instance->id = $data['id'] ?? null;
        $instance->external_id = $data['external_id'] ?? null;
        $instance->user_id = $data['user_id'] ?? null;
        $instance->status = $data['status'] ? InvoiceStatus::from($data['status']) : null;
        $instance->merchant_name = $data['merchant_name'] ?? null;
        $instance->merchant_profile_picture_url = $data['merchant_profile_picture_url'] ?? null;
        $instance->amount = $data['amount'] ?? null;
        $instance->payer_email = $data['payer_email'] ?? null;
        $instance->description = $data['description'] ?? null;
        $instance->invoice_url = $data['invoice_url'] ?? null;
        $instance->expiry_date = isset($data['expiry_date']) ? Carbon::parse($data['expiry_date']) : null;
        $instance->available_banks = $data['available_banks'] ?? null;
        $instance->available_retail_outlets = $data['available_retail_outlets'] ?? null;
        $instance->available_ewallets = $data['available_ewallets'] ?? null;
        $instance->available_qr_codes = $data['available_qr_codes'] ?? null;
        $instance->available_direct_debits = $data['available_direct_debits'] ?? null;
        $instance->available_paylaters = $data['available_paylaters'] ?? null;
        $instance->should_exclude_credit_card = $data['should_exclude_credit_card'] ?? null;
        $instance->should_send_email = $data['should_send_email'] ?? null;
        $instance->created = isset($data['created']) ? Carbon::parse($data['created']) : null;
        $instance->updated = isset($data['updated']) ? Carbon::parse($data['updated']) : null;
        $instance->mid_label = $data['mid_label'] ?? null;
        $instance->currency = isset($data['currency']) ? Currency::from($data['currency']) : null;
        $instance->success_redirect_url = $data['success_redirect_url'] ?? null;
        $instance->failure_redirect_url = $data['failure_redirect_url'] ?? null;
        $instance->payment_methods = $data['payment_methods'] ?? null;
        $instance->fixed_va = $data['fixed_va'] ?? null;
        $instance->items = isset($data['items']) ? array_map(fn ($item) => Item::fromArray($item), $data['items']) : null;
        $instance->fees = isset($data['fees']) ? array_map(fn ($fee) => Fee::fromArray($fee), $data['fees']) : null;
        $instance->payment_details = isset($data['payment_details']) ? array_map(fn ($detail) => PaymentDetails::fromArray($detail), $data['payment_details']) : null;
        $instance->should_authenticate_credit_card = $data['should_authenticate_credit_card'] ?? null;
        $instance->channel_properties = isset($data['channel_properties']) ? CardChannelProperties::fromArray($data['channel_properties']) : null;
        $instance->metadata = $data['metadata'] ?? null;
        $instance->customer = isset($data['customer']) ? Customer::fromArray($data['customer']) : null;

        return $instance;
    }
}
