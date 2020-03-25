<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\GlobalSettings;
use App\GlobalSettings\GlobalSettingsManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class GlobalSettingsAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'settings';
    protected $baseRouteName = 'settings';



    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['create']);

        $collection->add('configuration');
    }


    public function getObject($id = null)
    {
        return $this->getNewInstance();
    }

    private function getGlobalSettingsManager()
    {
        return $this->getConfigurationPool()->getContainer()->get(GlobalSettingsManager::class);
    }

    public function getNewInstance()
    {
        return $this->getGlobalSettingsManager()->getSettings();
    }

    public function create($object)
    {
        $this->getGlobalSettingsManager()->setSettings($object);
        $this->getGlobalSettingsManager()->save();
    }


    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('API-ключи')
                ->add('cloudpaymentsApiKey', TextType::class, [
                    'property_path' => 'settings[cloudpayments_api_key]',
                    'empty_data' => '',
                ])
            ->end()
            ->with('Формы обратной связи')
                ->add('mail_from', TextType::class, [
                    'property_path' => 'settings[mail_from]',
                    'empty_data' => '',
                    'label' => 'Адрес отправителя',
                ])
            ->end()
            ;
    }


}
