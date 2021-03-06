<?php declare(strict_types=1);

namespace PHPExperts\ZuoraClient\DTOs\Read\Account;

use PHPExperts\SimpleDTO\SimpleDTO;

/**
 * See https://www.zuora.com/developer/api-reference/#operation/GET_Account
 *
 * @property string      $id
 * @property string      $name
 * @property string      $accountNumber
 * @property null|string $notes
 * @property string      $status
 * @property null|string $crmId
 * @property string      $batch
 * @property string      $invoiceTemplateId
 * @property string      $communicationProfileId
 * @property null|string $salesRep
 * @property null|string $parentId
 * @property null|string $sequenceSetId
 */
class BasicInfoDTO extends SimpleDTO
{
}
