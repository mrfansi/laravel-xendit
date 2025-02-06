<?php

namespace Mrfansi\LaravelXendit\Enums;

enum PaymentMethod: string
{
    // Universal Payment Method
    case CREDIT_CARD = 'CREDIT_CARD';
    case BANK_TRANSFER = 'BANK_TRANSFER';
    case EWALLET = 'EWALLET';

    case PAYLATER = 'PAYLATER';

    // Indonesia Payment Methods
    case BCA = 'BCA';
    case BNI = 'BNI';
    case BSI = 'BSI';
    case BRI = 'BRI';
    case MANDIRI = 'MANDIRI';
    case PERMATA = 'PERMATA';
    case SAHABAT_SAMPOERNA = 'SAHABAT_SAMPOERNA';
    case BNC = 'BNC';
    case ALFAMART = 'ALFAMART';
    case INDOMARET = 'INDOMARET';
    case OVO = 'OVO';
    case DANA = 'DANA';
    case SHOPEEPAY = 'SHOPEEPAY';
    case LINKAJA = 'LINKAJA';
    case JENIUSPAY = 'JENIUSPAY';
    case DD_BRI = 'DD_BRI';
    case DD_BCA_KLIKPAY = 'DD_BCA_KLIKPAY';
    case KREDIVO = 'KREDIVO';
    case AKULAKU = 'AKULAKU';
    case ATOME = 'ATOME';
    case QRIS = 'QRIS';

    // Philippines Payment Methods
    case SEVEN_ELEVEN = '7ELEVEN';
    case CEBUANA = 'CEBUANA';
    case DD_BPI = 'DD_BPI';
    case DD_UBP = 'DD_UBP';
    case DD_RCBC = 'DD_RCBC';
    case DD_BDO_EPAY = 'DD_BDO_EPAY';
    case DP_MLHUILLIER = 'DP_MLHUILLIER';
    case DP_PALAWAN = 'DP_PALAWAN';
    case DP_ECPAY_LOAN = 'DP_ECPAY_LOAN';
    case PAYMAYA = 'PAYMAYA';
    case GRABPAY = 'GRABPAY';
    case GCASH = 'GCASH';
    case BILLEASE = 'BILLEASE';
    case CASHALO = 'CASHALO';
    case BDO_ONLINE_BANKING = 'BDO_ONLINE_BANKING';
    case BPI_ONLINE_BANKING = 'BPI_ONLINE_BANKING';
    case UNIONBANK_ONLINE_BANKING = 'UNIONBANK_ONILNE_BANKING';
    case BOC_ONLINE_BANKING = 'BOC_ONLINE_BANKING';
    case CHINABANK_ONLINE_BANKING = 'CHINABANK_ONLINE_BANKING';
    case INSTAPAY_ONLINE_BANKING = 'INSTAPAY_ONLINE_BANKING';
    case LANDBANK_ONLINE_BANKING = 'LANDBANK_ONLINE_BANKING';
    case MAYBANK_ONLINE_BANKING = 'MAYBANK_ONLINE_BANKING';
    case METROBANK_ONLINE_BANKING = 'METROBANK_ONLINE_BANKING';
    case PNB_ONLINE_BANKING = 'PNB_ONLINE_BANKING';
    case PSBANK_ONLINE_BANKING = 'PSBANK_ONLINE_BANKING';
    case PESONET_ONLINE_BANKING = 'PESONET_ONLINE_BANKING';
    case RCBC_ONLINE_BANKING = 'RCBC_ONLINE_BANKING';
    case ROBINSONS_BANK_ONLINE_BANKING = 'ROBINSONS_BANK_ONLINE_BANKING';
    case SECURITY_BANK_ONLINE_BANKING = 'SECURITY_BANK_ONLINE_BANKING';
    case QRPH = 'QRPH';

    // Thailand Payment Methods
    case PROMPTPAY = 'PROMPTPAY';
    case LINEPAY = 'LINEPAY';
    case WECHATPAY = 'WECHATPAY';
    case TRUEMONEY = 'TRUEMONEY';
    case DD_SCB_MB = 'DD_SCB_MB';
    case DD_BBL_MB = 'DD_BBL_MB';
    case DD_KTB_MB = 'DD_KTB_MB';
    case DD_BAY_MB = 'DD_BAY_MB';
    case DD_KBANK_MB = 'DD_KBANK_MB';

    // Vietnam Payment Methods
    case APPOTA = 'APPOTA';
    case ZALOPAY = 'ZALOPAY';
    case VNPTWALLET = 'VNPTWALLET';
    case VIETTELPAY = 'VIETTELPAY';
    case WOORI = 'WOORI';
    case VIETCAPITAL = 'VIETCAPITAL';
    case VPB = 'VPB';
    case BIDV = 'BIDV';

    // Malaysia Payment Methods
    case TOUCHNGO = 'TOUCHNGO';
    case DD_UOB_FPX = 'DD_UOB_FPX';
    case DD_PUBLIC_FPX = 'DD_PUBLIC_FPX';
    case DD_AFFIN_FPX = 'DD_AFFIN_FPX';
    case DD_AGRO_FPX = 'DD_AGRO_FPX';
    case DD_ALLIANCE_FPX = 'DD_ALLIANCE_FPX';
    case DD_AMBANK_FPX = 'DD_AMBANK_FPX';
    case DD_ISLAM_FPX = 'DD_ISLAM_FPX';
    case DD_MUAMALAT_FPX = 'DD_MUAMALAT_FPX';
    case DD_BOC_FPX = 'DD_BOC_FPX';
    case DD_RAKYAT_FPX = 'DD_RAKYAT_FPX';
    case DD_BSN_FPX = 'DD_BSN_FPX';
    case DD_CIMB_FPX = 'DD_CIMB_FPX';
    case DD_HLB_FPX = 'DD_HLB_FPX';
    case DD_HSBC_FPX = 'DD_HSBC_FPX';
    case DD_KFH_FPX = 'DD_KFH_FPX';
    case DD_MAYB2U_FPX = 'DD_MAYB2U_FPX';
    case DD_OCBC_FPX = 'DD_OCBC_FPX';
    case DD_RHB_FPX = 'DD_RHB_FPX';
    case DD_SCH_FPX = 'DD_SCH_FPX';
    case DD_AFFIN_FPX_BUSINESS = 'DD_AFFIN_FPX_BUSINESS';
    case DD_AGRO_FPX_BUSINESS = 'DD_AGRO_FPX_BUSINESS';
    case DD_ALLIANCE_FPX_BUSINESS = 'DD_ALLIANCE_FPX_BUSINESS';
    case DD_AMBANK_FPX_BUSINESS = 'DD_AMBANK_FPX_BUSINESS';
    case DD_ISLAM_FPX_BUSINESS = 'DD_ISLAM_FPX_BUSINESS';
    case DD_MUAMALAT_FPX_BUSINESS = 'DD_MUAMALAT_FPX_BUSINESS';
    case DD_BNP_FPX_BUSINESS = 'DD_BNP_FPX_BUSINESS';
    case DD_CIMB_FPX_BUSINESS = 'DD_CIMB_FPX_BUSINESS';
    case DD_CITIBANK_FPX_BUSINESS = 'DD_CITIBANK_FPX_BUSINESS';
    case DD_DEUTSCHE_FPX_BUSINESS = 'DD_DEUTSCHE_FPX_BUSINESS';
    case DD_HLB_FPX_BUSINESS = 'DD_HLB_FPX_BUSINESS';
    case DD_HSBC_FPX_BUSINESS = 'DD_HSBC_FPX_BUSINESS';
    case DD_RAKYAT_FPX_BUSINESS = 'DD_RAKYAT_FPX_BUSINESS';
    case DD_KFH_FPX_BUSINESS = 'DD_KFH_FPX_BUSINESS';
    case DD_MAYB2E_FPX_BUSINESS = 'DD_MAYB2E_FPX_BUSINESS';
    case DD_OCBC_FPX_BUSINESS = 'DD_OCBC_FPX_BUSINESS';
    case DD_PUBLIC_FPX_BUSINESS = 'DD_PUBLIC_FPX_BUSINESS';
    case DD_RHB_FPX_BUSINESS = 'DD_RHB_FPX_BUSINESS';
    case DD_SCH_FPX_BUSINESS = 'DD_SCH_FPX_BUSINESS';
    case DD_UOB_FPX_BUSINESS = 'DD_UOB_FPX_BUSINESS';

    /**
     * Get available payment methods for a specific country
     */
    public static function getMethodsByCountry(CountryCode $countryCode): array
    {
        return match ($countryCode) {
            CountryCode::INDONESIA => [
                self::CREDIT_CARD, self::BCA, self::BNI, self::BSI, self::BRI,
                self::MANDIRI, self::PERMATA, self::SAHABAT_SAMPOERNA, self::BNC,
                self::ALFAMART, self::INDOMARET, self::OVO, self::DANA,
                self::SHOPEEPAY, self::LINKAJA, self::JENIUSPAY, self::DD_BRI,
                self::DD_BCA_KLIKPAY, self::KREDIVO, self::AKULAKU, self::ATOME,
                self::QRIS,
            ],
            CountryCode::PHILIPPINES => [
                self::CREDIT_CARD, self::SEVEN_ELEVEN, self::CEBUANA, self::DD_BPI,
                self::DD_UBP, self::DD_RCBC, self::DD_BDO_EPAY, self::DP_MLHUILLIER,
                self::DP_PALAWAN, self::DP_ECPAY_LOAN, self::PAYMAYA, self::GRABPAY,
                self::GCASH, self::SHOPEEPAY, self::BILLEASE, self::CASHALO,
                self::BDO_ONLINE_BANKING, self::BPI_ONLINE_BANKING,
                self::UNIONBANK_ONLINE_BANKING, self::BOC_ONLINE_BANKING,
                self::CHINABANK_ONLINE_BANKING, self::INSTAPAY_ONLINE_BANKING,
                self::LANDBANK_ONLINE_BANKING, self::MAYBANK_ONLINE_BANKING,
                self::METROBANK_ONLINE_BANKING, self::PNB_ONLINE_BANKING,
                self::PSBANK_ONLINE_BANKING, self::PESONET_ONLINE_BANKING,
                self::RCBC_ONLINE_BANKING, self::ROBINSONS_BANK_ONLINE_BANKING,
                self::SECURITY_BANK_ONLINE_BANKING, self::QRPH,
            ],
            CountryCode::THAILAND => [
                self::CREDIT_CARD, self::PROMPTPAY, self::LINEPAY, self::WECHATPAY,
                self::TRUEMONEY, self::SHOPEEPAY, self::DD_SCB_MB, self::DD_BBL_MB,
                self::DD_KTB_MB, self::DD_BAY_MB, self::DD_KBANK_MB,
            ],
            CountryCode::VIETNAM => [
                self::CREDIT_CARD, self::APPOTA, self::ZALOPAY, self::VNPTWALLET,
                self::VIETTELPAY, self::SHOPEEPAY, self::WOORI, self::VIETCAPITAL,
                self::VPB, self::BIDV,
            ],
            CountryCode::MALAYSIA => [
                self::CREDIT_CARD, self::TOUCHNGO, self::WECHATPAY, self::DD_UOB_FPX,
                self::DD_PUBLIC_FPX, self::DD_AFFIN_FPX, self::DD_AGRO_FPX,
                self::DD_ALLIANCE_FPX, self::DD_AMBANK_FPX, self::DD_ISLAM_FPX,
                self::DD_MUAMALAT_FPX, self::DD_BOC_FPX, self::DD_RAKYAT_FPX,
                self::DD_BSN_FPX, self::DD_CIMB_FPX, self::DD_HLB_FPX,
                self::DD_HSBC_FPX, self::DD_KFH_FPX, self::DD_MAYB2U_FPX,
                self::DD_OCBC_FPX, self::DD_RHB_FPX, self::DD_SCH_FPX,
                self::DD_AFFIN_FPX_BUSINESS, self::DD_AGRO_FPX_BUSINESS,
                self::DD_ALLIANCE_FPX_BUSINESS, self::DD_AMBANK_FPX_BUSINESS,
                self::DD_ISLAM_FPX_BUSINESS, self::DD_MUAMALAT_FPX_BUSINESS,
                self::DD_BNP_FPX_BUSINESS, self::DD_CIMB_FPX_BUSINESS,
                self::DD_CITIBANK_FPX_BUSINESS, self::DD_DEUTSCHE_FPX_BUSINESS,
                self::DD_HLB_FPX_BUSINESS, self::DD_HSBC_FPX_BUSINESS,
                self::DD_RAKYAT_FPX_BUSINESS, self::DD_KFH_FPX_BUSINESS,
                self::DD_MAYB2E_FPX_BUSINESS, self::DD_OCBC_FPX_BUSINESS,
                self::DD_PUBLIC_FPX_BUSINESS, self::DD_RHB_FPX_BUSINESS,
                self::DD_SCH_FPX_BUSINESS, self::DD_UOB_FPX_BUSINESS,
            ],
        };
    }
}
