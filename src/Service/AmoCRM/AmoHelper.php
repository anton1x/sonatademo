<?php


namespace App\Service\AmoCRM;


class AmoHelper
{

    private $client;
    private $isAuthenticated;

    public function __construct(TonixAmoCrmClient $client)
    {
        $this->client = $client;
    }

    private function handleResponse($response)
    {
        if(!isset($response['_embedded']['items'][0]['id']))
            return false;

        return $response['_embedded']['items'][0]['id'];
    }

    private function preRequest()
    {
        if(!$this->isAuthenticated){
            $this->isAuthenticated = $this->client->doAuth() == TonixAmoCrmClient::AUTH_RESULT_SUCCESS;
        }
    }

    public function createContact($fio, $phone, $email)
    {
        $contacts['add'] = array();
        $contacts['add'][] = $this->makeContactArray($fio, $phone, $email);

        $this->preRequest();
        $response = $this->client->postObject($contacts, $this->client->getObjectURL('contacts'));

        return $this->handleResponse($response);
    }

    private function makeContactArray($fio, $phone, $email, $login = '')
    {
        $contact = array(
            'name' => $fio,
            //'responsible_user_id' => 0,
            //'created_at' => time(),
            'custom_fields' => array(
                array(
                    'id' => 73563, // Телефон
                    'values' => array(
                        array(
                            'value' => $phone,
                            'enum' => "103679",
                        )
                    )
                ),
                array(
                    'id' => 73565, // e-mail
                    'values' => array(
                        array(
                            'value' => $email,
                            'enum' => 103691,
                        )
                    )
                ),
                array(
                    'id' => 596303, // логин
                    'values' => array(
                        array(
                            'value' => $login,
                        )
                    )
                ),
            ),
        );

        return $contact;
    }

    public function createLead($fio, $tariff, $address, $home = '', $corp = '', $notes = array(), $contactId = false, $tariffId = false)
    {
        $leads['add'] = array();
        $leads['add'][] = $this->makeLeadArray($fio, $tariff, $address, $home = '', $corp = '', $notes, $contactId, $tariffId);


        $this->preRequest();
        $response = $this->client->postObject($leads, $this->client->getObjectURL('leads'));

        return $this->handleResponse($response);
    }


    private function makeLeadArray(
        $fio,
        $tariff,
        $address,
        $home = '',
        $corp = '',
        $notes = array(),
        $contactId = false,
        $services = [],
        $tv = [],
        $devices = [],
        $budget = 0,
        $connectionDate = null,
        $addrLocation = '',
        $comment = ''
    )
    {
        $lead = array(
            'name' => 'Заявка от '.$fio,
            'created_at' => time(),
            //'responsible_user_id' => 0,
            //'tags' => 'rosfondom.ru',
            'sale' => $budget,
            'notes' => array(),
            'custom_fields' => array(
                array( // Квартира
                    'id' => "572347",
                    'values' => array(
                        array(
                            'value' => $home
                        )
                    )
                ),
                array( // Корпус
                    'id' => "572345",
                    'values' => array(
                        array(
                            'value' => $corp
                        )
                    )
                ),
                array( // тариф
                    'id' => 596297,
                    'values' => array(
                        array(
                            'value' => $tariff,
                            //'enum' => 817079,
                        )
                    )
                ),
                array( // адрес
                    'id' => 597237,
                    'values' => array(
                        array(
                            'value' => $address,
                            'enum' => 817107,
                        )
                    )
                ),
                array( // устройства
                    'id' => 596291,
                    'values' => $devices,
                ),
                array( // ТВ
                    'id' => 596295,
                    'values' => $tv,
                ),
                array(
                    'id' => 596293,
                    'values' => $services,
                ),
                array(
                    'id' => 597237,
                    'values' => [
                        [
                            //'value' => 'ул. Воробьевское ш, 4',
                            'value' => $addrLocation,
                        ]
                    ]
                ),
                array(
                    'id' => 598337,
                    'values' => [
                        [
                            'value' => $comment,
                        ]
                    ]
                )
            )
        );

        if ($connectionDate !== null) {
            $lead['custom_fields'][] = [
                'id' => 598333,
                'values' => [
                    [
                        'value' => $connectionDate
                    ]
                ]
            ];
        }

        if(count($notes) > 0) {
            foreach ($notes as $note) {
                $lead['notes'][] = array(
                    'element_type' => 'lead',
                    'note_type' => 4,
                    'text' => $note,
                    'created_by' => 0
                );
            }
        }

        if($contactId)
            $lead['contacts_id'] = $contactId;


        return $lead;
    }


    public function makeIncomingLeadArray($contact, $lead)
    {
        $incomingLead = array(
            'created_at' => time(),
            'incoming_lead_info' => array(
                'form_id' => '1001',
                'form_page' => 'http://rosfondom.ru/contacts/',
                'form_name' => 'Оставить заявку',
            ),
            'incoming_entities' => array(
                'leads' => array(
                    $lead
                ),
                'contacts' => array(
                    $contact
                ),
            )
        );

        return $incomingLead;
    }

    private function handleIncomingLeadResponse($response)
    {
        return $response['status'] == 'success';
    }

    public function createIncomingLead(
        $fio,
        $phone,
        $email,
        $tariff,
        $address,
        $home = '',
        $corp = '',
        $notes = array(),
        $services = [],
        $tv = [],
        $devices = [],
        $login = '',
        $budget = 0,
        $connectionDate = null,
        $addrLocation = '',
        $comment = ''
    )
    {
        $incomingLeads['add'] = array();
        $incomingLeads['add'][] = $this->makeIncomingLeadArray(
            $this->makeContactArray($fio, $phone, $email, $login),
            $this->makeLeadArray($fio, $tariff, $address, $home, $corp, $notes, false, $services, $tv, $devices, $budget, $connectionDate, $addrLocation, $comment)
        );

        $this->preRequest();
        $response = $this->client->postObject($incomingLeads, $this->client->getObjectURL('incoming_leads/form'));

        return $this->handleIncomingLeadResponse($response);
    }


    public function findCatalogItemBySKU($SKU = '')
    {
        $this->preRequest();
        $response = $this->client->getObject($this->client->getObjectURL(
            'catalog_elements'),
            array(
                'catalog_id' => 11177,
                'term' => $SKU
            )
        );


        if(!is_array($response['_embedded']['items'])){
            return false;
        }

        if(!isset($response['_embedded']['items'][0]['id'])){
            return false;
        }

        return $response['_embedded']['items'][0];

    }


}