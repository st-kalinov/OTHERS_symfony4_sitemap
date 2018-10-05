<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class AdminLoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var CsrfTokenManagerInterface
     */
    private $tokenManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(UserRepository $userRepository, RouterInterface $router,
                                CsrfTokenManagerInterface $tokenManager, UserPasswordEncoderInterface $passwordEncoder,
                                AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->tokenManager = $tokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'admin_login' && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(
          Security::LAST_USERNAME,
          $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if(!$this->tokenManager->isTokenValid($token))
        {
            throw new InvalidCsrfTokenException();
        }

        return $this->userRepository->findOneBy(['username' => $credentials['username']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('admin_index'));
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('admin_login');
    }

  ///**
  // * Returns a response that directs the user to authenticate.
  // *
  // * This is called when an anonymous request accesses a resource that
  // * requires authentication. The job of this method is to return some
  // * response that "helps" the user start into the authentication process.
  // *
  // * Examples:
  // *
  // * - For a form login, you might redirect to the login page
  // *
  // *     return new RedirectResponse('/login');
  // *
  // * - For an API token authentication system, you return a 401 response
  // *
  // *     return new Response('Auth header required', 401);
  // *
  // * @param Request $request The request that resulted in an AuthenticationException
  // * @param AuthenticationException $authException The exception that started the authentication process
  // *
  // * @return Response
  // */
  //public function start(Request $request, AuthenticationException $authException = null)
  //{
  //    return new Response('ACCESS DENIED', 401);
  //}

  ///**
  // * Called when authentication executed, but failed (e.g. wrong username password).
  // *
  // * This should return the Response sent back to the user, like a
  // * RedirectResponse to the login page or a 403 response.
  // *
  // * If you return null, the request will continue, but the user will
  // * not be authenticated. This is probably not what you want to do.
  // *
  // * @param Request $request
  // * @param AuthenticationException $exception
  // *
  // * @return Response|null
  // */
  //public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
  //{
  //    return new RedirectResponse($this->router->generate('admin_login'));
  //}

  ///**
  // * Does this method support remember me cookies?
  // *
  // * Remember me cookie will be set if *all* of the following are met:
  // *  A) This method returns true
  // *  B) The remember_me key under your firewall is configured
  // *  C) The "remember me" functionality is activated. This is usually
  // *      done by having a _remember_me checkbox in your form, but
  // *      can be configured by the "always_remember_me" and "remember_me_parameter"
  // *      parameters under the "remember_me" firewall key
  // *  D) The onAuthenticationSuccess method returns a Response object
  // *
  // * @return bool
  // */
  //public function supportsRememberMe()
  //{
  //    // TODO: Implement supportsRememberMe() method.
  //}
}
