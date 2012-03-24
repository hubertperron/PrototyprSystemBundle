<?php

namespace Prototypr\SystemBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Prototypr\SystemBundle\Controller\Controller;
use Prototypr\SystemBundle\Exception\ApplicationNotFoundException;

/**
 * Security Controller
 */
class SecurityController extends Controller
{
    const DEFAULT_APPLICATION = 'frontend';

    /**
     * @param string $application The application name
     * @param Request $request
     * @return Response
     */
    public function loginAction($application, Request $request)
    {
        $session = $request->getSession();

        $targetPath = $session->get('_security.target_path');

        if ($application) {
            // A direct first access to a login page use the application route parameter
            $session->set('_prototypr.login_application', $application);
        } elseif ($targetPath) {
            // A redirected access from a firewall use the target path uri
            $session->set('_prototypr.login_application', $this->findApplicationNameFromUri($targetPath));
        }

        $application = $session->get('_prototypr.login_application', static::DEFAULT_APPLICATION);

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'Prototypr' . ucfirst($application) . 'CoreBundle:Security:login.html.twig',
            array(
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error' => $error,
                'target_application_name' => $application,
                'section' => $this->getDoctrine()->getEntityManager()->getRepository('PrototyprBackendSectionBundle:Section')->find(1)
            )
        );
    }

    /**
     * @param string $uri
     * @return string The application name
     * @throws ApplicationNotFoundException
     */
    protected function findApplicationNameFromUri($uri)
    {
        $uriParts = parse_url($uri);
        $uriParts = preg_split('/(.*)\.php/', $uriParts['path']);

        $route = $this->get('router')->match(array_pop($uriParts));

        if (false == $route->getOption('application')) {
            throw new ApplicationNotFoundException('could not find application to match uri ' . $uri);
        }

        return $route->getOption('application');
    }
}
