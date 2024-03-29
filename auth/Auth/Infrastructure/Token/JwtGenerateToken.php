<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Token;

use Auth\Domain\AccessToken\AccessToken;
use Auth\Domain\AccessToken\CryptKeyPrivate;
use Auth\Domain\AccessToken\CryptKeyPublic;
use Auth\Domain\AccessToken\GenerateToken;
use Auth\Domain\AccessToken\TokeType;
use Auth\Domain\Bearer\TokenBearer;
use Auth\Domain\Exception\OAuthServerException;
use Auth\Domain\RefreshToken\RefreshToken;
use Auth\Domain\RefreshToken\RefreshTokenFindRepository;
use Auth\Domain\Token\Token;
use Auth\Domain\Token\TokenFindRepository;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token as JwtToken;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class JwtGenerateToken implements GenerateToken
{
    private Configuration $configuration;
    private string $privateKey;
    private string $publicKey;

    public function __construct(
        private readonly TokenFindRepository $tokenFindRepository,
        private readonly RefreshTokenFindRepository $refreshTokenFindRepository,
        private readonly ParameterBagInterface $parameterBag
    ) {
        $this->privateKey = $this->parameterBag->get('private_key');
        $this->publicKey = $this->parameterBag->get('public_key');
    }

    public function generateAccessToken(Token $token, ?RefreshToken $refreshToken): AccessToken
    {
        $privateKeyNew = CryptKeyPrivate::create($this->privateKey);
        $this->configuration = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::plainText($privateKeyNew->getKeyContents(), $privateKeyNew->getPassPhrase()),
            InMemory::plainText('empty', 'empty'),
        );

        $jwtToken = $this->convertTokenToJWT($token);

        if (null === $refreshToken) {
            return AccessToken::create(TokeType::from('bearer'), $token->getExpiry(), $jwtToken->toString());
        }

        $jwtRefreshToken = $this->convertRefreshTokenToJWT($refreshToken);

        return AccessToken::createWithRefreshToken(
            TokeType::from('bearer'),
            $token->getExpiry(),
            $jwtToken->toString(),
            $jwtRefreshToken->toString()
        );
    }

    /**
     * Generate a JWT from the access token.
     */
    private function convertTokenToJWT(Token $token): JwtToken
    {
        return $this->configuration->builder()
            ->permittedFor((string) $token->getClient()->getCredentials()->getIdentifier())
            ->identifiedBy((string) $token->getId())
            ->issuedAt(new \DateTimeImmutable())
            ->canOnlyBeUsedAfter(new \DateTimeImmutable())
            ->expiresAt($token->getExpiry())
            ->relatedTo((string) $token->getUser()?->getId())
            ->withClaim('scopes', $token->getScopes())
            ->getToken($this->configuration->signer(), $this->configuration->signingKey());
    }

    /**
     * Generate a JWT from the access token.
     */
    private function convertRefreshTokenToJWT(RefreshToken $refreshToken): JwtToken
    {
        return $this->configuration->builder()
            ->permittedFor((string) $refreshToken->getToken()->getClient()->getIdentifier())
            ->identifiedBy((string) $refreshToken->getId())
            ->issuedAt(new \DateTimeImmutable())
            ->canOnlyBeUsedAfter(new \DateTimeImmutable())
            ->expiresAt($refreshToken->getExpiry())
            ->relatedTo((string) $refreshToken->getToken()->getUser()?->getId())
            ->withClaim('scopes', $refreshToken->getToken()->getScopes())
            ->getToken($this->configuration->signer(), $this->configuration->signingKey());
    }

    /**
     * @throws OAuthServerException
     */
    public function generateTokenByBearer(TokenBearer $tokenBearer): Token
    {
        $jwtToken = $this->getJwtToken($tokenBearer->value());

        $this->assertConstraintsJwtToken($jwtToken);

        $claims = $jwtToken->claims();

        $tokenDomainFound = $this->tokenFindRepository->findOrFail(Uuid::fromString($claims->get('jti')));

        if ($tokenDomainFound->isRevoked()) {
            throw OAuthServerException::accessDenied('Bearer Access token has been revoked');
        }

        return $this->tokenFindRepository->findOrFail(Uuid::fromString($claims->get('jti')));
    }

    /**
     * @throws OAuthServerException
     */
    public function generateTokenFromJwtToken(string $token): Token
    {
        $jwtToken = $this->getJwtToken($token);

        $claims = $jwtToken->claims();

        $tokenDomainFound = $this->tokenFindRepository->findOrFail(Uuid::fromString($claims->get('jti')));

        if ($tokenDomainFound->isRevoked()) {
            throw OAuthServerException::accessDenied('Access token has been revoked');
        }

        return $tokenDomainFound;
    }

    /**
     * @throws OAuthServerException
     */
    public function generateRefreshTokenFromJwtToken(string $token): RefreshToken
    {
        $jwtToken = $this->getJwtToken($token);

        $claims = $jwtToken->claims();

        $refreshTokenDomainFound = $this->refreshTokenFindRepository
            ->findOrFail(Uuid::fromString($claims->get('jti')));

        if ($refreshTokenDomainFound->isRevoked()) {
            throw OAuthServerException::accessDenied('Refresh Access token has been revoked');
        }

        return $refreshTokenDomainFound;
    }

    /**
     * @throws OAuthServerException
     */
    private function getJwtToken(string $token): JwtToken
    {
        $publicKeyNew = CryptKeyPublic::create($this->publicKey);
        $this->configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText('empty', 'empty')
        );

        $this->configuration->setValidationConstraints(
            \class_exists(StrictValidAt::class)
                ? new StrictValidAt(new SystemClock(new \DateTimeZone(\date_default_timezone_get())))
                : new LooseValidAt(new SystemClock(new \DateTimeZone(\date_default_timezone_get()))),
            new SignedWith(
                new Sha256(),
                InMemory::plainText($publicKeyNew->getKeyContents(), $publicKeyNew->getPassPhrase() ?? '')
            )
        );

        // Attempt to parse the JWT
        $jwtToken = $this->configuration->parser()->parse($token);

        $this->assertConstraintsJwtToken($jwtToken);

        return $jwtToken;
    }

    /**
     * @throws OAuthServerException
     */
    private function assertConstraintsJwtToken(JwtToken $jwtToken): void
    {
        try {
            // Attempt to validate the JWT
            $constraints = $this->configuration->validationConstraints();
            $this->configuration->validator()->assert($jwtToken, ...$constraints);
        } catch (RequiredConstraintsViolated $e) {
            throw OAuthServerException::accessDenied('Access token could not be verified');
        }
    }
}
