<?php


namespace App\Entity;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

class ConnectionFormOrder
{
    public const TYPE_INDEX = 1;
    public const TYPE_DEVELOPERS = 2;

    private $availableTypes = [
        self::TYPE_INDEX,
        self::TYPE_DEVELOPERS,
    ];

    private $name;
    private $email;
    private $phone;
    private $type;
    private $note;

    /**
     * @Recaptcha\IsTrue()
     */
    private $recaptcha;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return array
     */
    public function getAvailableTypes(): array
    {
        return $this->availableTypes;
    }


    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        if(!in_array($type, $this->getAvailableTypes()))
            throw new \InvalidArgumentException('Your type passed to the setType with ID {$type} is not available');
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note): void
    {
        $this->note = $note;
    }

    public function getCrmMessage()
    {
        if ($this->getType() == self::TYPE_INDEX)
        {
            return 'Отправлена заявка на подключение c главной страницы';
        }
        elseif ($this->getType() == self::TYPE_DEVELOPERS) {
            return "
                Отправлена заявка со страницы \"Застройщикам и УК\"\n
                Сообщение: {$this->getNote()}
            ";
        }
    }

    /**
     * @todo Open-Closed principle needed
     */
    public function getHeaderText()
    {
        if ($this->getType() == self::TYPE_INDEX) {
            return 'Отправлена заявка на подключение c главной страницы';
        }
        elseif ($this->getType() == self::TYPE_DEVELOPERS) {
            return "
                Отправлена заявка со страницы \"Застройщикам и УК\"\n
               ";
        }
    }

}