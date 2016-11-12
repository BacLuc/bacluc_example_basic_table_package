<?php
/**
 * Created by PhpStorm.
 * User: lucius
 * Date: 21.10.16
 * Time: 12:34
 */

namespace Concrete\Package\BaclucExampleBasicTablePackage\Src\EntityViews;


use Concrete\Package\BasicTablePackage\Src\AbstractFormView;

class EmailAddressFormView extends AbstractFormView
{
    public function getFormView($form, $clientSideValidationActivated=true)
    {
        $variables = $this->getFilledVariables($clientSideValidationActivated); //dontchange
        $html = "
<!-- wrap everything in one row -->
    <div class='row'>
    <!-- always display the id field  somewhere -->
            ".$variables['id']['input'] ."
        <div class='row'>
         <!-- Start 3 columns bootstrap layout  -->
                                       <div class = 'col-xs-12 col-sm-4'>
                                           <div class='row'>
                                               <div class='col-sm-12 col-md-3'>
                                                   <label>" . $variables['type']['label'] . "</label>
                                               </div>
                                               <div class='col-sm-12 col-md-9'>
                                                   " . $variables['type']['input'] . "
                                               </div>
                                           </div>
                                       </div>
                                       <div class = 'col-xs-12 col-sm-4'>
                                           <div class='row'>
                                               <div class='col-sm-12 col-md-3'>
                                                   <label>" . $variables['organisation']['label'] . "</label>
                                               </div>
                                               <div class='col-sm-12 col-md-9'>
                                                   " . $variables['organisation']['input'] . "
                                               </div>
                                           </div>
                                       </div>
                                       <div class = 'col-xs-12 col-sm-4'>
                                           <div class='row'>
                                               <div class='col-sm-12 col-md-3'>
                                                   <label>" . $variables['info']['label'] . "</label>
                                               </div>
                                               <div class='col-sm-12 col-md-9'>
                                                   " . $variables['info']['input'] . "
                                               </div>
                                           </div>
                                       </div>
                      </div>
                      <!-- end 3 columns bootstrap layout  -->
                      <!-- Start 1 column bootstrap layout  -->
                      <div class='row'>
                                                  <div class = 'col-xs-12'>
                                                      <div class='row'>
                                                          <div class='col-sm-12 col-md-1'>
                                                              <label>" . $variables['email']['label'] . "</label>
                                                          </div>
                                                          <div class='col-sm-12 col-md-11'>
                                                              " . $variables['email']['input'] . "
                                                          </div>
                                                      </div>
                                                  </div>
                                                  
                                   </div>
                        <!-- End 1 column bootstrap layout  -->           
        </div>
        
        
        ";

        return $html;
    }

}