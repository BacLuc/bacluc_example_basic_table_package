<?php
/**
 * Created by PhpStorm.
 * User: lucius
 * Date: 21.12.15
 * Time: 14:53
 */

namespace Concrete\Package\BaclucExampleBasicTablePackage\Src; //TODO change namespace
//TODO CHANGE use statemetns
use Concrete\Package\BasicTablePackage\Src\EntityGetterSetter;
use Concrete\Package\BasicTablePackage\Src\FieldTypes\DirectEditAssociatedEntityField;
use Concrete\Package\BasicTablePackage\Src\FieldTypes\DirectEditAssociatedEntityMultipleField;
use Concrete\Package\BasicTablePackage\Src\FieldTypes\DropdownLinkField;
use Concrete\Package\BasicTablePackage\Src\FieldTypes\DropdownMultilinkField;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Concrete\Package\BasicTablePackage\Src\FieldTypes\DateField;
use Concrete\Package\BasicTablePackage\Src\BaseEntity;
use Concrete\Package\BasicTablePackage\Src\FieldTypes\DropdownField;

/*because of the hack with @DiscriminatorEntry Annotation, all Doctrine Annotations need to be
properly imported*/
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Concrete\Package\BasicTablePackage\Src\DiscriminatorEntry\DiscriminatorEntry;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\QueryBuilder;

/**
 * Class Example
 * package Concrete\Package\BaclucExampleBasicTablePackage\Src
 * @Entity
 *  @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorEntry( value = "Concrete\Package\BaclucExampleBasicTablePackage\Src\Example") //TODO change discriminator value
 * @Table(name="bacluc_example")//TODO change table name
 */
class Example extends BaseEntity//TODO change class name
{
    use EntityGetterSetter;

    //dontchange
    public static $staticEntityfilterfunction; //that you have a filter that is only for this entity
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GEneratedValue(strategy="AUTO")
     */
    protected $id;




    /**
     * @Column(type="string", nullable=true)
     */
    protected $stringcolumn;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $intcolumn;

    /**
     * @Column(type="float", nullable=true)
     */
    protected $floatcolumn;


    /**
     * @Column(type="date", nullable=true)
     */
    protected $datecolumn;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $booleancolumn;

//
//    /**
//     * @var Example
//     * @OneToOne(targetEntity="Concrete\Package\BaclucExampleBasicTablePackage\Src\Example", mappedBy="OneToOneInverse")
//     */
//    protected $OneToOneOwning;
//
//    /**
//     * @var Example
//     * @OneToOne(targetEntity="Concrete\Package\BaclucExampleBasicTablePackage\Src\Example", inversedBy="OneToOneOwning")
//     */
//    protected $OneToOneInverse;


    /**
     * @var Example
     * @ManyToOne(targetEntity="Concrete\Package\BaclucExampleBasicTablePackage\Src\Example", inversedBy="OneToMany")
     */
    protected $ManyToOne;

    /**
     * @var Example[]
     * @OneToMany(targetEntity="Concrete\Package\BaclucExampleBasicTablePackage\Src\Example", mappedBy="ManyToOne")
     */
    protected $OneToMany;


    /**
     * @Column(type="string", nullable=true)
     */
    protected $dropdownfield;

    const DROPDOWNFIELD_OTHER = 'other';
    const DROPDOWNFIELD_RECIEVABLE = 'recievable';
    const DROPDOWNFIELD_PAYABLE = 'payable';
    const DROPDOWNFIELD_LIQUIDITY = 'liquidity';






    /**
     * Example of Many to many relation without a field at Emailaddress referencing back
     * @var EmailAddress[]
     * @ManyToMany(targetEntity="Concrete\Package\BaclucExampleBasicTablePackage\Src\EmailAddress")
     * @JoinTable(name="bacluc_example_person_email_address",
         joinColumns={@JoinColumn(name="person_id" , referencedColumnName="id")},
        inverseJoinColumns={@JoinColumn(name="address_id" , referencedColumnName="id")}
        )
     */
    protected $EmailAddresses;







    public function __construct(){
        parent::__construct();

        //TODO foreach Collection valued property, you have to set the ArrayCollection if it is null


        if($this->OneToMany == null){
            $this->OneToMany = new ArrayCollection();
        }


        if($this->EmailAddresses == null){
            $this->EmailAddresses = new ArrayCollection();
        }






    }

    public function setDefaultFieldTypes()
    {
        parent::setDefaultFieldTypes();

        /*TODO to change the labels of the fields, to it like this.
        as default, the name of the sql column is taken
        */
        $this->fieldTypes['stringcolumn']->setLabel("Label changed to String");


        //you can set min, max and step on float and integer fields
        $this->fieldTypes['intcolumn']->setMin(5);
        $this->fieldTypes['intcolumn']->setMax(100);
        $this->fieldTypes['intcolumn']->setStep(5);



        //To implement a dropdownfield, do like this:
        $this->fieldTypes['dropdownfield']=new DropdownField('dropdownfield', 'Dropdownfield', 'postdropdownfield');
        $refl = new \ReflectionClass($this);
        $constants = $refl->getConstants();
        $userConstants = array();
        foreach($constants as $key => $value){
            //if you have multiple dropdownfields, distinguish them somehow
            if(strpos($value,"DROPDOWNFIELD")!==false) {
                $userConstants[$value] = $value;
            }
        }

        /**
         * @var DropdownField
         */
        $this->fieldTypes['dropdownfield']->setOptions($userConstants);

        //you can set nullability of DropDownLinkFields
        //$this->fieldTypes['ManyToOne']->setNullable(true);

        /**
         * @var DropdownMultilinkField $addresses
         * //TODO if you want to convert a field to a DirectEditAssociatedEntityMultipleField or DirectEditAssociatedEntityField
         * do it like this
         */
        $addresses = $this->fieldTypes['EmailAddresses'];
        $directEditField = new DirectEditAssociatedEntityMultipleField($addresses->getSQLFieldName(), "Email Addresses", $addresses->getPostName());
        DropdownLinkField::copyLinkInfo($addresses,$directEditField);
        $this->fieldTypes['EmailAddresses']=$directEditField;
        $this->fieldTypes['EmailAddresses']->setNullable(true);

    }


    public static function getDefaultGetDisplayStringFunction(){
        $function = function(Example $item){//TODO change this function that it returns a unique string
            $dateField = new DateField("test", "test", "test");
            $returnString ="";
            if(strlen($item->stringcolumn)>0){
                $returnString.=$item->stringcolumn." ";
            }
            if(strlen($item->intcolumn!=null)>0){
                $returnString.=$item->intcolumn." ";
            }
            if(strlen($item->floatcolumn!=null)){
                $returnString.=$item->floatcolumn." ";
            }
            if($item->datecolumn!=null){
                $dateField->setSQLValue($item->datecolumn);
                $returnString.= " ".$dateField->getTableView();
            }
            return $returnString;
        };
        return $function;
    }




}

/**
 *
 *
 * $addFilterFunction must be of signature and is called in this function:
 * /**
 * @param QueryBuilder $query
 * @param array $queryConfig
 *  array of:
 * array(
'fromEntityStart' => array('shortname'=> 'e0'
 *                                                       , 'class'=>get_class($this->model)
 *                                             )
 *       ,'firstAssociationFieldname'=> array('shortname' => 'e1'
 *                                                                           , 'class' => 'Namespace\To\Entity\Classname')
 *
 * );
 * @return QueryBuilder

 *
 * @return QueryBuilder
 * //TODO apply entity default filtery
 */
Example::$staticEntityfilterfunction = function(QueryBuilder $query, array $queryConfig = array()){
    $firstEntityName = $queryConfig['fromEntityStart']['shortname'];

    //make complex query, for more see doctrine dok
    $query->andWhere(
        $query->expr()->orX(
            $query->expr()->eq($firstEntityName.".intcolumn", ":ExampleProductintcolumn")
            ,$query->expr()->neq($firstEntityName.".intcolumn", ":ExampleProductintcolumn")
            ,$query->expr()->isNull($firstEntityName.".intcolumn")
        )


    );
    $query->setParameter(":ExampleProductintcolumn", 1);

    return $query;
};