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

    private function makeContactArray($fio, $phone, $email)
    {
        $contact = array(
            'name' => $fio,
            //'responsible_user_id' => 0,
            //'created_at' => time(),
            'custom_fields' => array(
                array(
                    'id' => "73563", // Телефон
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


    private function makeLeadArray($fio, $tariff, $address, $home = '', $corp = '', $notes = array(), $contactId = false, $tariffId = false)
    {
        $lead = array(
            'name' => 'Заявка от '.$fio,
            'created_at' => time(),
            //'responsible_user_id' => 0,
            //'tags' => 'rosfondom.ru',
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
                    'id' => 572283,
                    'values' => array(
                        array(
                            'value' => $tariff,
                            'enum' => 817079,
                        )
                    )
                ),
                array( // адрес
                    'id' => 572343,
                    'values' => array(
                        array(
                            'value' => $address,
                            'enum' => 817107,
                        )
                    )
                )
            )
        );

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

        /*
        if($tariffId){
            $catalogItem = $this->findCatalogItemBySKU($tariffId);
            if($catalogItem){
                $lead['catalog_elements_id'] = array(
                  '11177' => array(
                      $catalogItem['id'] => 1,
                  )
                );
            }
        }*/

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
        $tariffId = false
    )
    {
        $incomingLeads['add'] = array();
        $incomingLeads['add'][] = $this->makeIncomingLeadArray(
            $this->makeContactArray($fio, $phone, $email),
            $this->makeLeadArray($fio, $tariff, $address, $home, $corp, $notes, false, $tariffId)
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