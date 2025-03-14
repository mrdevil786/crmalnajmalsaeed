<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ env('APP_NAME') }} - {{ ucfirst($invoice->name) }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css" media="screen">
        * {
            font-family: DejaVu Sans, sans-serif;
        }

        html {
            line-height: 1.15;
            margin: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 10px;
            margin: 36pt;
        }

        h4 {
            margin: 0 0 0.5rem;
            font-weight: 500;
            line-height: 1.2;
            font-size: 1.5rem;
        }

        p {
            margin: 0 0 .5rem;
        }

        strong {
            font-weight: bolder;
        }

        img {
            vertical-align: middle;
            border: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            color: #212529;
        }

        tr {
            text-transform: capitalize;
        }

        th,
        td {
            padding: 0.5rem;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .party-header,
        .items-header {
            font-size: 1.5rem;
            font-weight: 400;
            background-color: #3d9970;
            color: white;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .total-amount {
            font-size: 12px;
            font-weight: 700;
            color: #3d9970;
        }

        .cool-gray {
            color: #6B7280;
        }

        .border-0 {
            border: none;
        }

        .border-1 {
            border: 1px solid #dee2e6;
        }

        .mt-2 {
            margin-top: 1rem;
        }

        .header-section,
        .invoice-info-section,
        .party-section,
        .items-section,
        .notes-section {
            margin-bottom: 1rem;
        }

        .items-header {
            font-size: 12px;
            font-weight: 400;
            background-color: #3d9970;
            color: white;
            text-align: center;
        }

        .summary-title {
            font-weight: bold;
            font-size: 10px;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .logo-img {
            height: 100px;
        }

        .qr-code {
            width: 100px;
            height: 100px;
        }

        .nowrap {
            white-space: nowrap;
        }

        .stamp-image {
            position: fixed;
            bottom: 10px;
            right: 10px;
            z-index: 10;
            height: 125px;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="header-section">
        <table>
            <tr>
                <td width="70%">
                    @if ($invoice->logo)
                        <img src="{{ $invoice->getLogo() }}" alt="logo" class="logo-img">
                    @endif
                </td>
                <td width="30%" class="text-right">
                    @if ($invoice->getCustomData()['qrCodeData'] ?? false)
                        <img src="{{ $invoice->getCustomData()['qrCodeData'] }}" alt="QR Code" class="qr-code" />
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Invoice Info Section -->
    <div class="invoice-info-section">
        <table class="table mt-2">
            <tbody>
                <tr>
                    <td class="border-0" width="70%">
                        <h4 class="text-uppercase"><strong>{{ $invoice->name }}</strong></h4>
                    </td>
                    <td class="border-0" width="30%">
                        @if ($invoice->status)
                            <h4 class="text-uppercase cool-gray"><strong>{{ $invoice->status }}</strong></h4>
                        @endif
                        <p>{{ __('invoices::invoice.serial') }} <strong>{{ $invoice->getSerialNumber() }}</strong></p>
                        <p>{{ __('invoices::invoice.date') }}: <strong>{{ $invoice->getDate() }}</strong></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Seller and Buyer Section -->
    <div class="party-section">
        <table class="table">
            <thead>
                <tr>
                    <th class="party-header" width="48.5%">{{ __('invoices::invoice.bill_from') }}</th>
                    <th class="border-0" width="3%"></th>
                    <th class="party-header" width="48.5%">{{ __('invoices::invoice.bill_to') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-1 seller-section">
                        @if ($invoice->seller->name)
                            <p class="seller-name"><strong>{{ $invoice->seller->name }}</strong></p>
                        @endif
                        @if ($invoice->seller->address)
                            <p class="seller-address">{{ __('invoices::invoice.address') }}:
                                {{ $invoice->seller->address }}</p>
                        @endif
                        @if ($invoice->seller->code)
                            <p class="seller-code">{{ __('invoices::invoice.code') }}: {{ $invoice->seller->code }}
                            </p>
                        @endif
                        @if ($invoice->seller->vat)
                            <p class="seller-vat">{{ __('invoices::invoice.vat') }}: {{ $invoice->seller->vat }}</p>
                        @endif
                        @if ($invoice->seller->phone)
                            <p class="seller-phone">{{ __('invoices::invoice.phone') }}: {{ $invoice->seller->phone }}
                            </p>
                        @endif
                        @foreach ($invoice->seller->custom_fields as $key => $value)
                            <p class="seller-custom-field">{{ ucfirst($key) }}: {{ $value }}</p>
                        @endforeach
                    </td>
                    <td class="border-0"></td>
                    <td class="border-1 buyer-section">
                        @if ($invoice->buyer->name)
                            <p class="buyer-name"><strong>{{ $invoice->buyer->name }}</strong></p>
                        @endif
                        @if ($invoice->buyer->address)
                            <p class="buyer-address">{{ __('invoices::invoice.address') }}:
                                {{ $invoice->buyer->address }}</p>
                        @endif
                        @if ($invoice->buyer->code)
                            <p class="buyer-code">{{ __('invoices::invoice.code') }}: {{ $invoice->buyer->code }}</p>
                        @endif
                        @if ($invoice->buyer->vat)
                            <p class="buyer-vat">{{ __('invoices::invoice.vat') }}: {{ $invoice->buyer->vat }}</p>
                        @endif
                        @if ($invoice->buyer->phone)
                            <p class="buyer-phone">{{ __('invoices::invoice.phone') }}: {{ $invoice->buyer->phone }}
                            </p>
                        @endif
                        @foreach ($invoice->buyer->custom_fields as $key => $value)
                            <p class="buyer-custom-field">{{ ucfirst($key) }}: {{ $value }}</p>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Items and Summary Section -->
    <div class="items-section">
        <table class="table table-items">
            <thead>
                <tr class="items-header nowrap">
                    <th scope="col" class="text-center border-0">{{ __('invoices::invoice.description') }}</th>
                    @if ($invoice->hasItemUnits)
                        <th scope="col" class="text-center border-0">{{ __('invoices::invoice.units') }}</th>
                    @endif
                    <th scope="col" class="text-center border-0">{{ __('invoices::invoice.quantity') }}</th>
                    <th scope="col" class="text-center border-0">{{ __('invoices::invoice.price') }}</th>
                    @if ($invoice->hasItemDiscount)
                        <th scope="col" class="text-center border-0">{{ __('invoices::invoice.discount') }}</th>
                    @endif
                    @if ($invoice->hasItemTax)
                        <th scope="col" class="text-center border-0">{{ __('invoices::invoice.tax') }}</th>
                    @endif
                    <th scope="col" class="text-center border-0">{{ __('invoices::invoice.sub_total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                    <tr class="item-row">
                        <td class="border-1">{{ $item->title }}
                            @if ($item->description)
                                <p class="cool-gray">{{ $item->description }}</p>
                            @endif
                        </td>
                        @if ($invoice->hasItemUnits)
                            <td class="border-1 text-center">{{ $item->units }}</td>
                        @endif
                        <td class="border-1 text-center">{{ $item->quantity }}</td>
                        <td class="border-1 text-right nowrap">
                            {{ $invoice->formatCurrency($item->price_per_unit) }}</td>
                        @if ($invoice->hasItemDiscount)
                            <td class="border-1 text-right nowrap">
                                {{ $invoice->formatCurrency($item->discount) }}</td>
                        @endif
                        @if ($invoice->hasItemTax)
                            <td class="border-1 text-right nowrap">
                                {{ $invoice->formatCurrency($item->tax) }}</td>
                        @endif
                        <td class="border-1 text-right nowrap">
                            {{ $invoice->formatCurrency($item->sub_total_price) }}</td>
                    </tr>
                @endforeach

                @if ($invoice->hasItemOrInvoiceDiscount())
                    <tr class="summary-row discount-row">
                        <td colspan="{{ $invoice->table_columns - 3 }}" class="border-0"></td>
                        <td class="summary-title text-right border-1">{{ __('invoices::invoice.total_discount') }}</td>
                        <td class="text-right border-1">{{ $invoice->formatCurrency($invoice->total_discount) }}</td>
                    </tr>
                @endif
                @if ($invoice->taxable_amount)
                    <tr class="summary-row taxable-row">
                        <td colspan="{{ $invoice->table_columns - 3 }}" class="border-0"></td>
                        <td class="summary-title text-right border-1">{{ __('invoices::invoice.taxable_amount') }}</td>
                        <td class="text-right border-1" colspan="2">
                            {{ $invoice->formatCurrency($invoice->taxable_amount) }}</td>
                    </tr>
                @endif
                @if ($invoice->tax_rate)
                    <tr class="summary-row tax-rate-row">
                        <td colspan="{{ $invoice->table_columns - 3 }}" class="border-0"></td>
                        <td class="summary-title text-right border-1">{{ __('invoices::invoice.tax_rate') }}</td>
                        <td class="text-right border-1" colspan="2">{{ $invoice->tax_rate }}%</td>
                    </tr>
                @endif
                @if ($invoice->hasItemOrInvoiceTax())
                    <tr class="summary-row total-taxes-row">
                        <td colspan="{{ $invoice->table_columns - 3 }}" class="border-0"></td>
                        <td class="summary-title text-right border-1">{{ __('invoices::invoice.total_taxes') }}</td>
                        <td class="text-right border-1" colspan="2">
                            {{ $invoice->formatCurrency($invoice->total_taxes) }}</td>
                    </tr>
                @endif
                @if ($invoice->shipping_amount)
                    <tr class="summary-row shipping-row">
                        <td colspan="{{ $invoice->table_columns - 3 }}" class="border-0"></td>
                        <td class="summary-title text-right border-1">{{ __('invoices::invoice.shipping') }}</td>
                        <td class="text-right border-1" colspan="2">
                            {{ $invoice->formatCurrency($invoice->shipping_amount) }}</td>
                    </tr>
                @endif
                <tr class="summary-row total-amount-row">
                    <td colspan="{{ $invoice->table_columns - 3 }}" class="border-0"></td>
                    <td class="summary-title text-right border-1">{{ __('invoices::invoice.total_amount') }}</td>
                    <td class="text-right border-1 total-amount" colspan="2">
                        {{ $invoice->formatCurrency($invoice->total_amount) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Notes and Additional Information Section -->
    <div class="notes-section">
        @if ($invoice->notes)
            <p class="invoice-notes">{{ __('invoices::invoice.notes') }}: {!! $invoice->notes !!}</p>
        @endif
        <p class="amount-in-words">{{ __('invoices::invoice.amount_in_words') }}:
            {{ $invoice->getTotalAmountInWords() }}
        </p>
        @if ($invoice->name === 'quotation' && isset($invoice->getCustomData()['due_date']))
            <p>
                {{ __('invoices::invoice.valid_until') }}: {{ $invoice->getCustomData()['due_date'] }}
            </p>
        @endif
        @if (isset($invoice->getCustomData()['iban']))
            <p class="iban-info">{{ $invoice->getCustomData()['iban'] }}</p>
        @endif
    </div>

    <!-- Stamp Section -->
    <div class="stamp-section">
        @if (isset($invoice->getCustomData()['stampBase64']) && $invoice->getCustomData()['stampBase64'])
            <img src="{{ $invoice->getCustomData()['stampBase64'] }}" alt="Stamp" class="stamp-image" />
        @endif
    </div>

    <script type="text/php">
        if (isset($pdf) && $PAGE_COUNT > 1) {
            $text = "{{ __('invoices::invoice.page') }} {PAGE_NUM} / {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width);
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>

</html>
