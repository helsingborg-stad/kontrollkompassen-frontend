<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractRequest;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractSession;
use \KoKoP\Interfaces\AbstractSearch;
use \KoKoP\Models\Person;
use \KoKoP\Models\CivilStatus;
use \KoKoP\Models\PropertyRegistrationHistory;
use \KoKoP\Models\Relation;

class Search implements AbstractSearch
{
    protected string $baseUrl;
    protected string $apiKey;
    protected AbstractRequest $request;
    protected AbstractSession $session;

    public function __construct(AbstractConfig $config, AbstractRequest $request, AbstractSession $session)
    {
        // Read config
        $this->baseUrl = $config->getValue(
            'MS_NAVET',
            ""
        );
        $this->apiKey = $config->getValue(
            'MS_NAVET_AUTH',
            ""
        );
        $this->request = $request;
        $this->session = $session;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
    public function getEndpoint(): string
    {
        return $this->baseUrl;
    }

    public function findPerson(string $pnr): array
    {
        $data = [
            "searchFor" => Format::socialSecuriyNumber($pnr)
        ];

        //Get data
        $person = $this->searchPerson($pnr);

        //Validate, if ok. Parse data
        if ($data['searchResult'] = !$person->isErrorResponse()) {
            $personData = new Person($person->getContent());

            $relations = $this->searchRelations($pnr);

            //Get family relations
            $data['searchResultFamilyRelations'] = $this->searchFamilyRelations(
                $relations->getContent()
            );

            //Get property data
            $data['searchResultPropertyData'] = $this->getPropertyData(
                $relations->getContent()
            );

            $data['basicData'] = [];
            $civilStatus = new CivilStatus($relations->getContent()->civilStatus);

            if ($personData->isDeregistered()) {
                $data['basicData'] = $this->createBasicDataList(
                    $personData,
                    Format::socialSecuriyNumber($pnr),
                    $civilStatus
                );

                //Create deregistration state
                $data['isDeregistered'] = true;
                $data['deregistrationReason'] = $this->getDeregistrationSentence(
                    $personData
                );
            } else {

                //Is not deregistered
                $data['isDeregistered'] = false;

                //Request basic data table
                $data['basicData']  = $this->createBasicDataList(
                    $personData,
                    Format::socialSecuriyNumber($pnr),
                    $civilStatus
                );

                //Request the readable string
                $data['readableResult'] = $this->createReadableText(
                    $personData,
                    $pnr
                );

                //Request adress data table
                $data['adressData'] = $this->createAdressDataList(
                    $personData
                );
            }
        }
        return $data;
    }

    /**
     * Returns a sentence indicating that a person has been deregistered and their status.
     *
     * @param string $reason The reason for deregistration.
     * @return string The sentence indicating the deregistration status.
     */
    public function getDeregistrationSentence(Person $person)
    {
        if (!empty($person->getDeregistrationDate())) {
            return "Personen är avregistrerad och har fått statusen: " . $person->getDeregistrationReason() . Format::addPharanthesis($person->getDeregistrationDate());
        }
        return "Personen är avregistrerad och har fått statusen: " . $person->getDeregistrationReason();
    }

    /**
     * Action method for searching with specified parameters.
     *
     * This method handles the search action with the provided parameters. It validates
     * the correctness of the provided personal number (pnr) format using the Validate class.
     * If the pnr is not in the correct format, it redirects to the search page with the 
     * 'search-pnr-malformed' action and the sanitized pnr. If the pnr is valid, it sanitizes
     * the input and retrieves data for the specified person. If the search is successful, 
     * it parses the data into readable formats, such as readable text, basic data list, 
     * and address data list. If the search is not successful, it redirects to the search page 
     * with the 'search-no-hit' action and the sanitized pnr.
     *
     * @param array $req An associative array of request parameters.
     *
     * @throws RedirectException If the pnr is not in the correct format or if the search is unsuccessful,
     *                           a RedirectException is thrown to redirect the user to the appropriate page.
     */
    protected function searchPerson($pnr)
    {
        return $this->request->post($this->baseUrl . '/lookUpAddress', [
            "personNumber" => Sanitize::number($pnr),
            "searchedBy"  => $this->session->getUser()->getAccountName()
        ], [
            'X-ApiKey' => $this->apiKey
        ]);
    }

    protected function searchRelations($pnr)
    {
        return $this->request->post($this->baseUrl . '/lookUpFamilyRelations', [
            "personNumber" => Sanitize::number($pnr),
            "searchedBy"  => $this->session->getUser()->getAccountName()
        ], [
            'X-ApiKey' => $this->apiKey
        ]);
    }

    /**
     * Search for family relations using the specified personal number (PNR) and retrieve relevant information.
     *
     * @param string $pnr The personal number for which family relations are to be searched.
     * @param string $relevantKey The key in the API response containing relevant family relation data. Default is 'relationsToFolkbokforda'.
     *
     * @return false|object Returns false if no relevant data is found, otherwise returns an object with processed family relations data.
     *
     * @throws \Exception If there is an issue with the Curl request or processing the API response.
     */
    protected function searchFamilyRelations($data, $relevantKey = 'relationsToFolkbokforda')
    {
        $stack = false;
        $predefinedCodes = ['FA', 'MO', 'VF', 'B', 'M'];

        if (!empty($data->{$relevantKey}) && is_array($data->{$relevantKey})) {
            $stack = [];

            foreach ($data->{$relevantKey} as $item) {

                $item = new Relation($item);

                // Initialize an empty array for the identity number
                if (!isset($stack[$item->getIdentityNumber()])) {
                    $stack[$item->getIdentityNumber()] = array_fill_keys($predefinedCodes, false);
                }

                // Set the value to true for the corresponding code
                $stack[$item->getIdentityNumber()][$item->getTypeCode()] = !empty($item->getCustodyDate()) ? Format::date($item->getCustodyDate()) : true;
            }
        }

        if ($stack === false) {
            return false;
        }

        return (object) $this->createRelationsDataList($stack);
    }

    /**
     * Creates readable text based on the provided data and personal number (pnr).
     *
     * This private method takes in data representing a person and their personal number (pnr)
     * to construct a readable text string. The resulting text includes the person's full name,
     * current age derived from the pnr, and residential address information in a formatted manner.
     *
     * @param object $data An object containing information about the person.
     * @param string $pnr The personal number (pnr) used to calculate the person's current age.
     *
     * @return string The constructed readable text string with person's name, age, and address.
     */
    protected function createReadableText(Person $person, $pnr)
    {
        if (empty($person->getStreetAddress())) {
            return $person->getGivenName() . " " . $person->getFamilyName() . " är " . Format::getCurrentAge($pnr) . " år gammal och har ingen registrerad bostadsadress.";
        }
        return $person->getGivenName() . " " . $person->getFamilyName() . " är " . Format::getCurrentAge($pnr) . " år gammal och är bosatt på " . $person->getStreetAddress() . " i " . $person->getAddressLocality() . ".";
    }

    /**
     * Creates a basic data list based on the provided data and personal number (pnr).
     *
     * This private method takes in data representing a person and their personal number (pnr)
     * to construct a basic data list. The resulting list includes key-value pairs for essential
     * information such as personal number, first name, last name, and additional names.
     *
     * @param object $data An object containing information about the person.
     * @param string $pnr The personal number (pnr) associated with the person.
     *
     * @return array An array representing a basic data list with key-value pairs.
     */
    protected function createBasicDataList(Person $person, $pnr, CivilStatus $civilStatus)
    {
        return [
            ['columns' => [
                'Personnummer:',
                $pnr ?? ''
            ]],
            ['columns' => [
                'Kön:',
                Format::sex($pnr, true) ?? ''
            ]],
            ['columns' => [
                'Civilstatus:',
                !empty($civilStatus->getCivilStatusDescription()) ? $civilStatus->getCivilStatusDescription() . " " . Format::addPharanthesis($civilStatus->getCivilStatusDate()) : ''
            ]],
            ['columns' => [
                'Förnamn:',
                $person->getGivenName()
            ]],
            ['columns' => [
                'Efternamn:',
                $person->getFamilyName()
            ]],
            ['columns' => [
                'Övriga namn:',
                $person->getAdditionalName()
            ]],
        ];
    }

    /**
     * Creates an address data list based on the provided data.
     *
     * This private method takes in data representing a person and constructs an address data list.
     * The resulting list includes key-value pairs for essential address information such as municipality,
     * postal code, and street address. The address information is formatted for consistency.
     *
     * @param object $data An object containing information about the person's address.
     *
     * @return array An array representing an address data list with key-value pairs.
     */
    protected function createAdressDataList(Person $person)
    {
        return [
            ['columns' => [
                'Postort:',
                $person->getAddressLocality()
            ]],
            ['columns' => [
                'Postnummer:',
                $person->getPostalCode()
            ]],
            ['columns' => [
                'Gatuadress:',
                $person->getStreetAddress()
            ]]
        ];
    }
    /**
     * Creates a property data list based on the provided data.
     *
     * @param object $data The data containing property registration history.
     * @return array|false The property data list or false if the data is invalid or empty.
     */
    protected function getPropertyData($data, $relevantKey = 'propertyRegistrationHistory')
    {
        if (!isset($data->{$relevantKey})) {
            return false;
        }

        if (empty((array) $data->{$relevantKey})) {
            return false;
        }

        $list = [];
        foreach ($data->{$relevantKey} as $property) {
            $item = new PropertyRegistrationHistory($property);

            $list[] = [
                'columns' => [
                    $item->getPropertyDesignation(),
                    $item->getTypeDescription(),
                    $item->getRegistrationDate(),
                    $item->getMunicipalityCode(),
                    $item->getCountyCode(),
                ]
            ];
        }

        return [
            'title' => "Adresshistorik",
            'headings' => ['Fastighetsbeteckning', 'Händelse', 'Datum', 'Kommunkod', 'Län'],
            'list' => $list
        ];
    }

    /**
     * Creates an address data list based on the provided data.
     *
     * This private method takes in data representing a person and constructs an address data list.
     * The resulting list includes key-value pairs for essential address information such as municipality,
     * postal code, and street address. The address information is formatted for consistency.
     *
     * @param object $data An object containing information about the person's address.
     *
     * @return array An array representing an address data list with key-value pairs.
     */
    protected function createRelationsDataList($data)
    {
        $stack = [];
        foreach ($data as $identityNumber => $relations) {
            $stack[] = [
                'columns' => [
                    '<a href="/sok/?action=sok&pnr=' . $identityNumber . '">' . Format::socialSecuriyNumber((string)$identityNumber) . '</a>',
                    $relations['FA'] ? '✕' . Format::addPharanthesis(Sanitize::string($relations['FA'])) : '-',
                    $relations['MO'] ? '✕' . Format::addPharanthesis(Sanitize::string($relations['MO'])) : '-',
                    $relations['VF'] ? '✕' . Format::addPharanthesis(Sanitize::string($relations['VF'])) : '-',
                    $relations['B'] ? '✕' . Format::addPharanthesis(Sanitize::string($relations['B'])) : '-',
                    $relations['M'] ? '✕' . Format::addPharanthesis(Sanitize::string($relations['M'])) : '-'
                ]
            ];
        }

        return $stack;
    }
}
