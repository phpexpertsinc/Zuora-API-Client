<?php declare(strict_types=1);

namespace PHPExperts\ZuoraClient\DTOs\Write;

use PHPExperts\SimpleDTO\NestedDTO;
use PHPExperts\SimpleDTO\SimpleDTO;
use PHPExperts\SimpleDTO\WriteOnce;

/**
 * https://www.zuora.com/developer/api-reference/#operation/POST_CreatePayment
 *
 * @property float       $amount
 * @property null|string $invoiceItemId
 * @property null|string $taxItemId
 */
class InvoiceItemsDTO extends SimpleDTO
{
    use WriteOnce;
}
