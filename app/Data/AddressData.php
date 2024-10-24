<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Concerns\EmptyData;

class AddressData extends Data
{
    use EmptyData;

    public function __construct(
        #[Required]
        #[StringType]
        public string $street1,
        #[StringType]
        public string|Optional $street2,
        #[Required]
        #[StringType]
        public string $city,
        #[Required]
        #[StringType]
        public string $state,
        #[Required]
        #[StringType]
        public string $postal_code,
        #[Required]
        #[StringType]
        public string $country,
        #[StringType]
        public string|Optional $phone,
        #[Email]
        public string|Optional $email,
        #[StringType]
        public string|Optional $contact_name,
    ) {
    }
}
