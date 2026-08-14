# Copilot Instructions for laravel-imoje

## Project Overview
This is a Laravel 8+ package that integrates **imoje payment gateway** (Polish payment provider) with strongly-typed DTOs. Compatible with PHP 7.4/8.x. The package provides three main interfaces:
- **Paywall** - Browser redirects for payment forms
- **API** - Direct API integration for transactions, refunds, profiles
- **Notifications** - Webhook handling with signature verification

**Important**: This package uses **MyCLabs\Enum** library for PHP 7.4 compatibility instead of native PHP 8.1 enums.

## Architecture & Data Flow

### Core Components
- **`src/Lib/`** - Main service classes (Api, Paywall, Validator, Config, Utils, Url)
- **`src/DTO/`** - Immutable data transfer objects organized by domain:
  - `Api/` - API request DTOs
  - `Paywall/` - Paywall transaction DTOs  
  - `Casts/` - Nested DTOs for API responses
  - `Responses/` - API response DTOs
  - `Notifications/` - Webhook notification DTOs
- **`src/Types/`** - Enums (using MyCLabs\Enum) for type safety (Currency, TransactionStatus, Environment, etc.)
- **`src/Factories/`** - Laravel-style factories for testing DTOs (mirror DTO structure)

### DTO Pattern (Critical)
All DTOs extend `BaseDto` (which extends Laravel's `Fluent`) with these conventions:

1. **Immutable by design** - `offsetSet()` throws `ReadOnlyDtoException`
2. **Auto-casting via `$casts` property** - Supports nested DTOs, enums, primitives
3. **Null handling** - Set `protected bool $allowNull = true` to filter empty values (used in API DTOs)
4. **Constructor auto-injection** - Paywall DTOs auto-inject `serviceId`, `merchantId`, and `signature` from Config
5. **PHPDoc annotations** - All constructor params documented with PHPDoc `@param` for IDE support

Example from `src/DTO/Paywall/TransactionDto.php`:
```php
protected array $casts = ['amount' => 'int'];

/**
 * @param array $attributes
 * @param HashMethod|null $hashMethod
 */
public function __construct(
    $attributes = [],
    HashMethod $hashMethod = null
) {
    if ($hashMethod === null) {
        $hashMethod = HashMethod::SHA256();
    }
    $config = app(Config::class);
    $attributes['serviceId'] = $config->serviceId;
    $attributes['signature'] = app(Utils::class)->createSignature($attributes, $hashMethod);
    parent::__construct($attributes);
}
```

### Service Binding
`ImojeServiceProvider` binds `Config` from `config/services.php`:
```php
'imoje' => [
    'merchant_id' => env('IMOJE_MERCHANT_ID'),
    'service_id' => env('IMOJE_SERVICE_ID'),
    'service_key' => env('IMOJE_SERVICE_KEY'),
    'api_key' => env('IMOJE_API_KEY'),
    'env' => env('IMOJE_ENV'), // 'production' or 'sandbox'
]
```

### URL Building
`Url` class constructs API endpoints using `Config->env->apiUrl()` + merchant paths. Environment enum provides:
- `apiUrl()` - API base (sandbox vs production)
- `paywallUrl()` - Payment form URL
- `widgetUrl()` - OneClick widget JS
- `cdnUrl()` - CDN for assets

### Signature Verification
Critical security pattern in `Utils` and `Validator`:
- **Outgoing** (Paywall): `Utils->createSignature()` - ksort params, build query string, hash with service key
- **Incoming** (Notifications): `Validator->verifySignature()` - Parse `x-imoje-signature` header, hash request body + service key, compare

## Development Workflows

### Testing
Uses **Pest** (not PHPUnit syntax):
```bash
vendor/bin/pest              # Run tests
vendor/bin/pest --coverage   # With coverage
vendor/bin/phpstan analyse   # Static analysis
vendor/bin/pint             # Code formatting (Laravel Pint)
```

Test setup uses Orchestra Testbench with mock credentials in `TestCase->getEnvironmentSetUp()`.

### Code Style
- PHP 8.1+ with strict types (`declare(strict_types=1)`)
- Laravel 8+ compatible
- Laravel Pint for PSR-2 formatting
- PHPStan for static analysis
- No `readonly` properties (use regular properties with PHPDoc)
- Use `switch` instead of `match` expressions

### Enum Usage (MyCLabs\Enum)
All enums extend `MyCLabs\Enum\Enum` instead of native PHP 8.1 enums for PHP 7.4 compatibility:
- **Define values**: `const CONSTANT_NAME = 'value'` instead of `case ConstantName = 'value'`
- **Get value**: `$enum->getValue()` instead of `$enum->value`
- **Create instance**: `new EnumClass('value')` or `EnumClass::CONSTANT_NAME()` factory method
- **Cast from string**: `new EnumClass($value)` instead of `EnumClass::from($value)`
- **Compare**: `$enum->equals($other)` instead of `$enum === $other`
- **Get all values**: `EnumClass::toArray()` returns `['KEY' => 'value']`

### Adding New DTOs
1. Extend `BaseDto`, define `$casts` array
2. Add PHPDoc `@param` to constructor with all fields
3. Create matching Factory in `src/Factories/` extending `Factory`
4. Add `use HasFactory` and `@method static` annotation
5. If response DTO, override `Factory->getResponseModel()` to mock `Response`

### Adding New API Methods
1. Add DTO in `src/DTO/Api/`
2. Add response DTO in `src/DTO/Responses/`
3. Add URL builder method in `src/Lib/Url.php`
4. Add API method in `src/Lib/Api.php` following pattern:
   ```php
   public function methodName(Dto $dto): ResponseDto {
       $url = $this->url->createMethodUrl();
       $response = $this->request()->post($url, $dto->toArray());
       $this->validateResponse($response, $dto->toArray());
       return new ResponseDto($response);
   }
   ```

## Key Conventions

### Error Handling
- `ApiErrorException` - Wraps 422/500 API errors with `ApiErrorResponseDto`
- `InvalidSignatureException` - Webhook signature mismatch
- `SchemaValidationException` - JSON schema validation failure (uses `justinrainbow/json-schema`)
- `ReadOnlyDtoException` - Attempted mutation of DTO

### Amount Handling
Amounts are **integers in grosz/cents** (100 PLN = 10000):
```php
'amount' => 100 * 100, // 100 PLN
```

### Factory Pattern
Factories extend `Factory` from this package (not Eloquent Factory directly). Use `create()` to generate DTOs in tests.

## External Dependencies
- **Guzzle** - HTTP client (via Laravel HTTP facade)
- **justinrainbow/json-schema** - Webhook payload validation
- **Orchestra Testbench** - Laravel package testing
- **MyCLabs\Enum** - Enum emulation for PHP 7.4 compatibility

## Documentation
- `docs/paywall.md` - Paywall integration examples
- `docs/api.md` - API integration (minimal, refers to provider docs)
- `docs/notifications.md` - Webhook handling examples
- Official docs: https://imojepaywall.docs.apiary.io/ (PL), https://imojepaywalleng.docs.apiary.io/ (EN)
