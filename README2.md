3.  The labels for the table header row and the form fields are by default the sql field name.
To change this, you can call in the same function the setLabel() function of the Field
  ```php
     <?php
     class Example extends \Concrete\Package\BasicTablePackage\Src\BaseEntity {
         //...some class code
     public function setDefaultFieldTypes()
         {
             parent::setDefaultFieldTypes(); 
             /*TODO to change the labels of the fields, to it like this.
                     as default, the name of the sql column is taken
                     */
              $this->fieldTypes['stringcolumn']->setLabel("Label changed to String");
     
         }
         //.. some class code
      }
 ```
4. If you want that the form fields don't look like the standard (always a linebreak between the fields),
You can define your own EntityView. To do that, create a class which extends AbstractFormView in src/EntityViews
and implement the method getFormView($form, $clientSideValidationActivated=true).  
An Example is in [src/EntityViews/EmailAddressFormView.php](src/EntityViews/EmailAddressFormView.php).
Then in your Entity, override the method. You define a FormView, and if you want a special 
View if it is shown in a DirectEditAssociationEntityField, you set the $this->defaultSubFormView.
 ```php
    <?php
    class EmailAddress extends \Concrete\Package\BasicTablePackage\Src\BaseEntity {
        //...some class code
      public function setDefaultFormViews()
        {
            $this->defaultFormView = new EmailAddressFormView($this);//TODO if you have a special form view for this entity, put it here
            $this->defaultSubFormView = new EmailAddressSubFormView($this);//TODO if you have a special form view for subform for this entity, put it here
        }
        //.. some class code
     }
```
5. When you finished implementing your Entities, remove the unnessesary ones and make your first test.
Install the Package and look if the SQL Tables are generated right.  
**<Important:>**  
Before you change anything about
* the columns
* the table name of the entity
* the classname
* discriminatorvalue  
you have to first uninstall the package, apply your changes, and then install it again.
Else you have some Exceptions like columnnotfoundexception and so on
**<\/Important>**  
6. Advanced Usage:  
If you want a default Filter for your Entity, you can set YourClassname::$staticEntityfilterfunction.
It applies mostly everywhere your entity is queried. This is not a senseful example because it has no
effect (intcolumn = 1 OR NOT intcolumn = 1), testso you have to use another query. You have to change Example:: to YourClassname::
```php
<?php
 /**
 * @param \Doctrine\ORM\QueryBuilder $query
 * @param array $queryConfig
 *  array of:
 * array(
 *'fromEntityStart' => array('shortname'=> 'e0'
 *                                                       , 'class'=>get_class($this->model)
 *                                             )
 *       ,'firstAssociationFieldname'=> array('shortname' => 'e1'
 *                                                                           , 'class' => 'Namespace\To\Entity\Classname')
 *
 * );
 * @return QueryBuilder

 *
 * @return QueryBuilder
 * TODO apply entity default filter
 */
Example::$staticEntityfilterfunction = function(QueryBuilder $query, array $queryConfig = array()){
    $firstEntityName = $queryConfig['fromEntityStart']['shortname'];

    //make complex query, for more see doctrine dok
    $query->andWhere(
        $query->expr()->orX(
            $query->expr()->eq($firstEntityName.".intcolumn", ":ExampleProductintcolumn")
            ,
            $query->expr()->neq($firstEntityName.".intcolumn", ":ExampleProductintcolumn")
        )


    );
    $query->setParameter(":ExampleProductintcolumn", 1);

    return $query;
};
```
7. If you finished implementing your Entities, you uninstall the package and implement your BlockType
To do that, you copy the folder blocks/bacluc_example_block and change the name of the new folder.
Then, you have to change controller.php. Again, here are TODO comments everywhere you have to change something
8. If you finished implementing your BlockType, you can uncomment the line which installs the BlockType in your Package Controller,
install the package again and look if everything works.
9. Advanced usage:  
You can edit every point of the functionality of the basic_table_block_packaged. You can change
what happens after a new row is submitted, you can add new actions and even more. How to to this is
shown in the commentet lines after the function getBlockTypeName()
10. Javascript and CSS
view.css, auto.js, the javascript files in the js/* folder and the css files in css/* folder are automatically loaded.
If you want to be sure that everything is loaded correctly, you have to override 
the on_start and registerViewAssets function. If you want certain javascript available in your add the block form,
you have to override the add method. don't forget to first call the parent method.
If you add a less file with $al->register, it is compiled by concrete5 automatically
 ```php
    <?php
    class Controller extends \Concrete\Package\BasicTablePackage\Block\BasicTableBlockPackaged\Controller  {
        //...some class code
      


    /**
     * list needed javascript/css files
 *     */
   public function on_start()
    {
          parent::on_start();
        $package = Package::getByHandle("basic_table_package");
        $al = \Concrete\Core\Asset\AssetList::getInstance();

        $al->register(
            'javascript', 'autojs', 'blocks/basic_table_block_packaged/js/auto.js',
            array('minify' => false, 'combine' => true)
            , $package
        );

        $groupAssets = array(
            array('javascript', 'autojs'),
        );


        $al->registerGroup('example', $groupAssets);

    }

    /**
     * register needed javascript
     */
    public function registerViewAssets($outputContent = '')
    {
      parent::registerViewAssets($outputContent);
        $this->requireAsset('example');
    }

    /**
     * register also for add form
     */
    public function add()
    {
      parent::add();
        $this->requireAsset('example');
    }
        //.. some class code
     }
```