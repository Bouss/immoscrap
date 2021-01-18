<?php

namespace App\Controller;

use App\Client\GmailClient;
use App\Entity\User;
use App\Enum\PropertyFilter;
use App\Exception\GmailException;
use App\Exception\GoogleException;
use App\Exception\ParserNotFoundException;
use App\Service\GoogleOAuthService;
use App\Service\PropertyService;
use App\Service\PropertySortResolver;
use Google_Service_Gmail_Label;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/property")
 */
class PropertyController extends AbstractController
{
    private GmailClient $gmailClient;
    private GoogleOAuthService $googleOAuthService;
    private PropertyService $propertyService;
    private PropertySortResolver $sortResolver;

    public function __construct(
        GmailClient $gmailClient,
        GoogleOAuthService $googleOAuthService,
        PropertyService $propertyService,
        PropertySortResolver $sortResolver
    ) {
        $this->gmailClient = $gmailClient;
        $this->googleOAuthService = $googleOAuthService;
        $this->propertyService = $propertyService;
        $this->sortResolver = $sortResolver;
    }

    /**
     * @throws GmailException|GoogleException
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->googleOAuthService->refreshAccessTokenIfExpired($user);
        $labels = $this->gmailClient->getLabels($user->getAccessToken());
        $settings = $user->getPropertySearchSettings();

        return $this->render('property/index.html.twig', [
            'gmail_label_choices' => $this->getGmailLabelChoices($labels),
            'newer_than' => $settings[PropertyFilter::NEWER_THAN] ?? null,
            'gmail_label' => $settings[PropertyFilter::GMAIL_LABEL] ?? null,
            'provider' => $settings[PropertyFilter::PROVIDER] ?? null,
            'new_build' => isset($settings[PropertyFilter::NEW_BUILD]),
            'sort' => $settings['sort'] ?? null
        ]);
    }

    /**
     * @Route("/list", methods={"GET"}, options={"expose"=true}, name="property_list")

     * @throws GmailException|GoogleException|ParserNotFoundException
     */
    public function list(Request $request): Response
    {
        parse_str($request->query->get('filters'), $filters);
        $sort = $request->query->get('sort');

        if (isset($filters[PropertyFilter::NEW_BUILD])) {
            $filters[PropertyFilter::NEW_BUILD] = true;
        }

        /** @var User $user */
        $user = $this->getUser();
        $user->setPropertySearchSettings(array_merge($filters, ['sort' => $sort]));
        $this->getDoctrine()->getManager()->flush();

        $properties = $this->propertyService->find($user, $filters);

        return $this->render('property/_list.html.twig', [
            'properties' => $properties,
            'sort' => $this->sortResolver->resolve($sort)
        ]);
    }

    /**
     * @param Google_Service_Gmail_Label[] $labels
     */
    private function getGmailLabelChoices(array $labels): array
    {
        $choices = [];

        foreach ($labels as $label) {
            $choices[$label->getName()] = $label->getId();
        }

        return $choices;
    }
}