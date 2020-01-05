module.exports = `<?php

namespace App\\DTO;

use Spatie\\DataTransferObject\\DataTransferObject;

class JsonDataTransferObject extends DataTransferObject
{
\t/** @var int $id */
\tpublic $id;

\t/** @var string $name */
\tpublic $name;

\t/** @var float $price */
\tpublic $price;

\t/** @var bool $enabled */
\tpublic $enabled;
}`
