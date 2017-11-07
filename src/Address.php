<?php
/**
 * Created by PhpStorm.
 * User: lucius
 * Date: 21.12.15
 * Time: 14:53
 */

namespace Concrete\Package\BaclucExampleBasicTablePackage\Src; //TODO delete file or change namespace
use Concrete\Package\BasicTablePackage\Src\BaseEntity;
use Concrete\Package\BasicTablePackage\Src\DiscriminatorEntry\DiscriminatorEntry;
use Concrete\Package\BasicTablePackage\Src\EntityGetterSetter;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\Table;

//TODO change use statements

/*because of the hack with @DiscriminatorEntry Annotation, all Doctrine Annotations need to be
properly imported*/


/**
 * Class Address
 * package Concrete\Package\BaclucPersonPackage\Src
 * @Entity
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorEntry( value = "Concrete\Package\BaclucPersonPackage\Src\Address" ) //TODO change discriminatorentry
 * @Table(name="bacluc_example_address")//TODO change table name
 */
class Address extends BaseEntity //TODO change class name
{
    use EntityGetterSetter;//dontchange
    //dontchange
    public static $staticEntityfilterfunction; //that you have a filter that is only for this entity
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GEneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @Column(type="string")
     */
    protected $type;


    /**
     * @Column(type="string")
     */
    protected $organisation;


    /**
     * @Column(type="string", length =65535)
     * if you want a text column or a long character column, set length to 65535
     */
    protected $info;


    public function __construct ()
    {
        parent::__construct();


    }

    public static function getDefaultGetDisplayStringFunction ()
    {
        $function =
            function (Address $item) { //TODO change displayStringFunction that it returns a unique string of enttity

                $returnString = "";
                if (strlen($item->type) > 0) {
                    $returnString .= $item->type . " ";
                }
                if (strlen($item->organisation != null) > 0) {
                    $returnString .= $item->organisation . " ";
                }
                if (strlen($item->info != null)) {
                    $returnString .= $item->info;
                }
                return $returnString;
            };
        return $function;
    }

    public function setDefaultFieldTypes ()
    {
        parent::setDefaultFieldTypes(); // TODO: Change the autogenerated stub
    }


}