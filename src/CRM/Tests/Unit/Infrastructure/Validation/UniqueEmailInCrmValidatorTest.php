<?php

declare(strict_types=1);

namespace App\CRM\Tests\Unit\Infrastructure\Validation;

use App\Contract\CRM\AccountClient\AccountClientInterface;
use App\Contract\CRM\AccountClient\UserInterface;
use App\Contract\CRM\Validation\UniqueEmailInCrm;
use App\CRM\Infrastructure\Validation\UniqueEmailInCrmValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

#[CoversClass(UniqueEmailInCrmValidator::class)]
final class UniqueEmailInCrmValidatorTest extends TestCase
{
    private AccountClientInterface&MockObject $accountClient;
    private ExecutionContextInterface&MockObject $context;
    private UniqueEmailInCrmValidator $validator;

    protected function setUp(): void
    {
        $this->accountClient = $this->createMock(AccountClientInterface::class);
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator = new UniqueEmailInCrmValidator($this->accountClient);
        $this->validator->initialize($this->context);
    }

    /**
     * @return iterable<string, array{
     *     value: mixed,
     *     crmReturnsUser: bool,
     *     expectCrmCalls: bool,
     *     expectViolationCalls: bool
     * }>
     */
    public static function provideTestValidateData(): iterable
    {
        yield 'passes when email does not exist in CRM' => [
            'value' => 'new-user@example.com',
            'crmReturnsUser' => false,
            'expectCrmCalls' => true,
            'expectViolationCalls' => false,
        ];

        yield 'adds violation when email exists in CRM' => [
            'value' => 'existing-user@example.com',
            'crmReturnsUser' => true,
            'expectCrmCalls' => true,
            'expectViolationCalls' => true,
        ];

        yield 'skips validation for null value' => [
            'value' => null,
            'crmReturnsUser' => false,
            'expectCrmCalls' => false,
            'expectViolationCalls' => false,
        ];

        yield 'skips validation for empty string' => [
            'value' => '',
            'crmReturnsUser' => false,
            'expectCrmCalls' => false,
            'expectViolationCalls' => false,
        ];
    }

    #[DataProvider('provideTestValidateData')]
    public function testValidate(
        mixed $value,
        bool $crmReturnsUser,
        bool $expectCrmCalls,
        bool $expectViolationCalls
    ): void {
        // Given
        $constraint = new UniqueEmailInCrm();

        $this->accountClient
            ->expects($expectCrmCalls ? $this->once() : $this->never())
            ->method('getUserByEmail')
            ->with($value)
            ->willReturn($crmReturnsUser ? $this->createMock(UserInterface::class) : null);

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder
            ->expects($expectViolationCalls ? $this->once() : $this->never())
            ->method('setParameter')
            ->with('{{ value }}', $value)
            ->willReturnSelf();
        $violationBuilder
            ->expects($expectViolationCalls ? $this->once() : $this->never())
            ->method('addViolation');

        $this->context
            ->expects($expectViolationCalls ? $this->once() : $this->never())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($violationBuilder);

        // When
        $this->validator->validate($value, $constraint);
    }

    public function testValidateThrowsExceptionForInvalidConstraintType(): void
    {
        // Given
        $invalidConstraint = $this->createMock(Constraint::class);

        // Expect
        $this->expectException(UnexpectedTypeException::class);

        // When
        $this->validator->validate('test@example.com', $invalidConstraint);
    }

    public function testValidateThrowsExceptionForNonStringValue(): void
    {
        // Given
        $constraint = new UniqueEmailInCrm();
        $invalidValue = 123;

        // Expect
        $this->expectException(UnexpectedValueException::class);

        // When
        $this->validator->validate($invalidValue, $constraint);
    }
}
