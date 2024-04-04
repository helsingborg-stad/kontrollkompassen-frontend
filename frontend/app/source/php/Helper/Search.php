<?php

declare(strict_types=1);

namespace NavetSearch\Helper;

use NavetSearch\Interfaces\AbstractRequest;
use NavetSearch\Interfaces\AbstractConfig;
use NavetSearch\Interfaces\AbstractSession;
use NavetSearch\Interfaces\AbstractSearch;

class Search implements AbstractSearch
{
    private string $baseUrl;
    private string $apiKey;
    private AbstractRequest $request;
    private AbstractSession $session;

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

    public function find(string $pnr): array
    {
        $data = [
            "searchFor" => Format::socialSecuriyNumber($pnr)
        ];

        //Get data
        $person = $this->searchPerson($pnr);

        //Validate, if ok. Parse data
        if ($data['searchResult'] = !$person->isErrorResponse()) {
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

            if ($this->isDeregistered($person->getContent())) {
                $data['basicData']  = $this->createBasicDataList(
                    $person->getContent(),
                    Format::socialSecuriyNumber($pnr),
                    $this->getCivilStatus($relations->getContent())
                );

                //Create deregistration state
                $data['isDeregistered'] = true;
                $data['deregistrationReason'] = $this->getDeristrationSentence(
                    $person->getContent()->deregistrationReason,
                    $person->getContent()->deregistrationDate ?? null
                );
            } else {

                //Is not deregistered
                $data['isDeregistered'] = false;

                //Request basic data table
                $data['basicData']  = $this->createBasicDataList(
                    $person->getContent(),
                    Format::socialSecuriyNumber($pnr),
                    $this->getCivilStatus($relations->getContent())
                );

                //Request the readable string
                $data['readableResult'] = $this->createReadableText(
                    $person->getContent(),
                    $pnr
                );

                //Request adress data table
                $data['adressData'] = $this->createAdressDataList(
                    $person->getContent()
                );
            }
        }
        return $data;
    }
    /**
     * Checks if a person is deregistered.
     *
     * @param object $person The person object to check.
     * @return bool Returns true if the person is deregistered, false otherwise.
     */
    public function isDeregistered($person)
    {
        if (isset($person->deregistrationCode)) {
            return true;
        }
        return false;
    }

    /**
     * Returns a sentence indicating that a person has been deregistered and their status.
     *
     * @param string $reason The reason for deregistration.
     * @return string The sentence indicating the deregistration status.
     */
    public function getDeristrationSentence($reason, $date = null)
    {
        if (!is_null($date)) {
            return "Personen är avregistrerad och har fått statusen: " . $reason . Format::addPharanthesis(Format::date($date));
        }
        return "Personen är avregistrerad och har fått statusen: " . $reason;
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
    private function searchPerson($pnr)
    {
        return $this->request->post($this->baseUrl . '/lookUpAddress', [
            "personNumber" => Sanitize::number($pnr),
            "searchedBy"  => $this->session->getAccountName()
        ], [
            'X-ApiKey' => $this->apiKey
        ]);
    }

    private function searchRelations($pnr)
    {
        return $this->request->post($this->baseUrl . '/lookUpFamilyRelations', [
            "personNumber" => Sanitize::number($pnr),
            "searchedBy"  => $this->session->getAccountName()
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
    private function searchFamilyRelations($data, $relevantKey = 'relationsToFolkbokforda')
    {
        $stack = false;
        $predefinedCodes = ['FA', 'MO', 'VF', 'B', 'M'];

        if (!empty($data->{$relevantKey}) && is_array($data->{$relevantKey})) {
            $stack = [];

            foreach ($data->{$relevantKey} as $item) {

                $item = Format::convertToArray($item);

                $identityNumber = $item['identityNumber'];

                // Initialize an empty array for the identity number
                if (!isset($stack[$identityNumber])) {
                    $stack[$identityNumber] = array_fill_keys($predefinedCodes, false);
                }

                // Set the value to true for the corresponding code
                $stack[$identityNumber][$item['type']['code']] = !empty($item['custodyDate']) ? Format::date($item['custodyDate']) : true;
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
    private function createReadableText($data, $pnr)
    {
        if (empty((array) $data->address)) {
            return $data->givenName . " " . $data->familyName . " är " . Format::getCurrentAge($pnr) . " år gammal och har ingen registrerad bostadsadress.";
        }
        return $data->givenName . " " . $data->familyName . " är " . Format::getCurrentAge($pnr) . " år gammal och är bosatt på " . Format::capitalize($data->address->streetAddress) . " i " . Format::capitalize($data->address->addressLocality) . ".";
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
    private function createBasicDataList($data, $pnr, $civilStatus)
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
                $civilStatus['description'] ? $civilStatus['description'] . " " . Format::addPharanthesis($civilStatus['date']) : ''
            ]],
            ['columns' => [
                'Förnamn:',
                $data->givenName ?? ''
            ]],
            ['columns' => [
                'Efternamn:',
                $data->familyName ?? ''
            ]],
            ['columns' => [
                'Övriga namn:',
                $data->additionalName ?? ''
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
    private function createAdressDataList($data)
    {
        if (empty((array) $data->address)) {
            return false;
        }

        return [
            ['columns' => [
                'Postort:',
                Format::capitalize($data->address->addressLocality) ?? ''
            ]],
            ['columns' => [
                'Postnummer:',
                Format::postalCode($data->address->postalCode) ?? ''
            ]],
            ['columns' => [
                'Gatuadress:',
                Format::capitalize($data->address->streetAddress) ?? ''
            ]]
        ];
    }

    /**
     * Creates a property data list based on the provided data.
     *
     * @param object $data The data containing property registration history.
     * @return array|false The property data list or false if the data is invalid or empty.
     */
    private function getCivilStatus($data, $relevantKey = 'civilStatus')
    {
        if (!isset($data->{$relevantKey})) {
            return ['code' => null, 'description' => null, 'date' => null];
        }

        if (empty((array) $data->{$relevantKey})) {
            return ['code' => null, 'description' => null, 'date' => null];
        }

        return [
            'code' => $data->{$relevantKey}->code,
            'description' => $data->{$relevantKey}->description,
            'date' => Format::date($data->{$relevantKey}->date)
        ];
    }

    /**
     * Creates a property data list based on the provided data.
     *
     * @param object $data The data containing property registration history.
     * @return array|false The property data list or false if the data is invalid or empty.
     */
    private function getPropertyData($data, $relevantKey = 'propertyRegistrationHistory')
    {
        if (!isset($data->{$relevantKey})) {
            return false;
        }

        if (empty((array) $data->{$relevantKey})) {
            return false;
        }

        $list = [];
        foreach ($data->{$relevantKey} as $property) {
            $list[] = [
                'columns' => [
                    $property->property->designation ?? '',
                    $property->type->description ?? '',
                    Format::date($property->registrationDate) ?? '',
                    $property->municipalityCode ?? '',
                    $property->countyCode ?? '',
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
    private function createRelationsDataList($data)
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
