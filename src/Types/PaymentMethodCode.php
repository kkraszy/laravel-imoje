<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static PaymentMethodCode ALIOR()
 * @method static PaymentMethodCode APPLEPAY()
 * @method static PaymentMethodCode BLIK()
 * @method static PaymentMethodCode BLIK_ONECLICK()
 * @method static PaymentMethodCode BLIK_PAYLATER()
 * @method static PaymentMethodCode BNPPARIBAS()
 * @method static PaymentMethodCode BOS()
 * @method static PaymentMethodCode BS()
 * @method static PaymentMethodCode BSPB()
 * @method static PaymentMethodCode BZWBK()
 * @method static PaymentMethodCode CITI()
 * @method static PaymentMethodCode CREDITAGRICOLE()
 * @method static PaymentMethodCode ENVELO()
 * @method static PaymentMethodCode GETIN()
 * @method static PaymentMethodCode GOOGLE_PAY()
 * @method static PaymentMethodCode IDEABANK()
 * @method static PaymentMethodCode IMOJE_INSTALLMENTS()
 * @method static PaymentMethodCode ING()
 * @method static PaymentMethodCode INTELIGO()
 * @method static PaymentMethodCode IPKO()
 * @method static PaymentMethodCode MILLENNIUM()
 * @method static PaymentMethodCode MTRANSFER()
 * @method static PaymentMethodCode NEST()
 * @method static PaymentMethodCode NOBLE()
 * @method static PaymentMethodCode PAYLATER()
 * @method static PaymentMethodCode PAYPO()
 * @method static PaymentMethodCode PBS()
 * @method static PaymentMethodCode PEKAO24()
 * @method static PaymentMethodCode PLUSBANK()
 * @method static PaymentMethodCode POCZTOWY()
 * @method static PaymentMethodCode PRAGMA_GO()
 * @method static PaymentMethodCode TMOBILE()
 * @method static PaymentMethodCode VISA_MOBILE()
 * @method static PaymentMethodCode WIRE_TRANSFER()
 * @method static PaymentMethodCode ONECLICK()
 * @method static PaymentMethodCode RECURRING()
 * @method static PaymentMethodCode ECOM3DS()
 */
class PaymentMethodCode extends Enum
{
    const ALIOR = 'alior';
    const APPLEPAY = 'applepay';
    const BLIK = 'blik';
    const BLIK_ONECLICK = 'blik_oneclick';
    const BLIK_PAYLATER = 'blik_paylater';
    const BNPPARIBAS = 'bnpparibas';
    const BOS = 'bos';
    const BS = 'bs';
    const BSPB = 'bspb';
    const BZWBK = 'bzwbk';
    const CITI = 'citi';
    const CREDITAGRICOLE = 'creditagricole';
    const ENVELO = 'envelo';
    const GETIN = 'getin';
    const GOOGLE_PAY = 'gpay';
    const IDEABANK = 'ideabank';
    const IMOJE_INSTALLMENTS = 'imoje_installments';
    const ING = 'ing';
    const INTELIGO = 'inteligo';
    const IPKO = 'ipko';
    const MILLENNIUM = 'millennium';
    const MTRANSFER = 'mtransfer';
    const NEST = 'nest';
    const NOBLE = 'noble';
    const PAYLATER = 'imoje_twisto';
    const PAYPO = 'paypo';
    const PBS = 'pbs';
    const PEKAO24 = 'pekao24';
    const PLUSBANK = 'plusbank';
    const POCZTOWY = 'pocztowy';
    const PRAGMA_GO = 'pragma_go';
    const TMOBILE = 'tmobile';
    const VISA_MOBILE = 'visa_mobile';
    const WIRE_TRANSFER = 'wt';

    // For notification
    const ONECLICK = 'oneclick';
    const RECURRING = 'recurring';
    const ECOM3DS = 'ecom3ds';

    public function getLogo(): ?string
    {
        $filename = $this->getLogoFilename();

        if (! $filename) {
            return null;
        }

        return Environment::PRODUCTION()->cdnUrl().'/img/pay/'.$filename;
    }

    public function getLogoFilename(): ?string
    {
        $value = $this->getValue();
        
        switch ($value) {
            case 'alior':
                return 'alior.svg';
            case 'bnpparibas':
                return 'bnpparibas.png';
            case 'bos':
                return 'bos.png';
            case 'bs':
                return 'bs.png';
            case 'bspb':
                return 'bspb.png';
            case 'bzwbk':
                return 'bzwbk.png';
            case 'citi':
                return 'citi.png';
            case 'creditagricole':
                return 'creditagricole.svg';
            case 'envelo':
                return 'envelo.png';
            case 'getin':
                return 'getin.svg';
            case 'ideabank':
                return 'ideabank.png';
            case 'ing':
                return 'ing.png';
            case 'inteligo':
                return 'inteligo.png';
            case 'ipko':
                return 'ipko.png';
            case 'millennium':
                return 'millennium.svg';
            case 'mtransfer':
                return 'mtransfer.png';
            case 'nest':
                return 'nest.svg';
            case 'noble':
                return 'noble.png';
            case 'pbs':
                return 'pbs.png';
            case 'pekao24':
                return 'pekao24.svg';
            case 'plusbank':
                return 'plusbank.png';
            case 'pocztowy':
                return 'pocztowy.svg';
            case 'tmobile':
                return 'tmobile.svg';
            case 'paypo':
                return 'paypo.svg';
            default:
                return null;
        }
    }
}
