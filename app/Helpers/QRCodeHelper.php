<?php

namespace App\Helpers;

use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;

class QRCodeHelper
{
    public static function generateQRCodeDataUri($invoice)
    {
        $tlvPayload = '';
        $tlvPayload .= self::createTLV(0x01, 'Al Najm Al Saeed Co. Ltd.');
        $tlvPayload .= self::createTLV(0x02, '312508185500003');
        $concatenatedDateTime = Carbon::parse($invoice->issue_date)->format('d-m-Y')
            . ' ' . Carbon::parse($invoice->created_at)->format('H:i');

        $tlvPayload .= self::createTLV(0x03, $concatenatedDateTime);
        $tlvPayload .= self::createTLV(0x04, number_format($invoice->total, 2, '.', ''));
        $tlvPayload .= self::createTLV(0x05, number_format($invoice->vat_amount, 2, '.', ''));

        $base64Payload = base64_encode($tlvPayload);

        try {
            $qrCode = QrCode::create($base64Payload)
                ->setSize(300)
                ->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            return 'data:image/png;base64,' . base64_encode($result->getString());
        } catch (\Exception $e) {
            Log::error('QR Code generation failed: ' . $e->getMessage());
            return null;
        }
    }

    private static function createTLV($tag, $value)
    {
        $valueBytes = mb_convert_encoding($value, 'UTF-8');
        $length = strlen($valueBytes);
        return pack('C*', $tag, $length) . $valueBytes;
    }
}
