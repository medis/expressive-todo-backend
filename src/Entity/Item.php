<?php declare(strict_types = 1);

namespace MedisDemoApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Zend\InputFilter\Factory as InputFilterFactory;

/**
 * @ORM\Entity
 * @ORM\Table(name="item")
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=1024, nullable=false, unique=true)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="done", type="boolean", nullable=false)
     * @var bool
     */
    private $isDone = false;

    public function __construct(array $params = [])
    {
        $this->id = (string)Uuid::uuid4();
        $this->data($params);
    }

    public static function getInputFilter()
    {
        $specification = [
            'name' => [
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 3,
                            'max' => 1024,
                        ],
                    ],
                ]
            ],
            'done' => [
                'required' => true,
                'allow_empty' => true,
                'filters' => [
                    ['name' => 'Boolean'],
                ],
            ],
        ];

        $factory = new InputFilterFactory();
        $inputFilter = $factory->createInputFilter($specification);

        return $inputFilter;
    }

    public function data(array $params = []) {
        foreach ($params as $key => $value) {
            $key = ucfirst($key);
            $func = "set{$key}";
            if (method_exists($this, $func)) {
                $this->{$func}($value);
            }
        }
    }

    public static function fromName(string $name) : self
    {
        $item = new self();
        $item->name = $name;
        return $item;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * @throws \MedisDemoApp\App\Entity\Exception\ItemAlreadyComplete
     */
    public function setDone(bool $done) : void
    {
//        if ($this->isDone) {
//            throw Exception\ItemAlreadyComplete::fromItem($this);
//        }
//        $this->isDone = true;
        $this->isDone = $done;
    }

    public function isComplete() : bool
    {
        return $this->isDone;
    }
}