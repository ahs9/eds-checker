# eds-checker

## Installation

```bash
$ composer require ahs9/eds-checker
```

## Usage

Use this library to compare user-data (from DB or from POST) with data in certificate (electronic digital signature).

## Examples

### Creating template
Certificates have different structure. Template shows to parser where user-data is. If template will be deep not enough,
parser will find duplicates of oid. For example, certificate has several keys `1.2.643.3.131.1.1`.

```php
$template = [
    ParserAsn::TEMPLATE_SEQUENCE => [
        ParserAsn::TEMPLATE_ARRAY => [
            0 => [
                ParserAsn::TEMPLATE_ARRAY => [
                    0 => null,
                    1 => null,
                    2 => null,
                    3 => null,
                    4 => null,
                    5 => ParserAsn::TEMPLATE_RESULT
                ]
            ]
        ]
    ]
];
```

### Debugging template
For debugging your template use ParserAsn::getSplitedAsn(). You can dump result when you fill out the template step by step.
Every step of template should deepen the ASN object. You need to get a part of certificate with no duplicates of oid.

### Creating parser-object

```php
$parser = new ParserAsn(base64_encode($content), [
        CertificateItem::OID_INN,
        CertificateItem::OID_SURNAME,
        CertificateItem::OID_GIVEN_NAME,
    ], $template);
```

### Getting parse-result

```php
$certificateData = $parser->getComparedData();
```

### Creating data-object for comparing from post

```php
$userData = new ComparedData(
    [
        CertificateItem::OID_INN => $post['inn'],
        CertificateItem::OID_SURNAME => $post['surname']
        CertificateItem::OID_GIVEN_NAME => $post['secondName'] . ' ' . $post['lastName']
    ]
);
```

### Comparing

```php
$checker = new Checker($userData, $certificateData);
if (!$checker->compare()) {
    var_dump($checker->getErrors());
}
// do staff
```
