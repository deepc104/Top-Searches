<?php

namespace Drupal\top_searches\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\formbuilder\forms\FormBuilderModel;
use Drupal\top_searches\TopSearchesModel;
//use Drupal\top_searches\Form\TopSearchesAdminForm;

/**
 * Configure example settings for this site.
 */
class TopSearchesDeleteForm extends ConfirmFormBase {

	protected $frmid;

   public function getFormId(){
      return "top_searches_delete_form";
   }

   public function getQuestion(){
      return t('This operation cannot be undone! <br> Are you sure you want to delete all the searched keywords from Top Searches list?');
   }
   
   public function getCancelUrl(){
      return FALSE;  
   }

   public function getConfirmText() {
      return t('Delete');
   }

   public function getCancelRoute() {
      return new Url('system.top_searches');
   }

   public function buildForm(array $form, FormStateInterface $form_state, $frmid = NULL) {
      $this->frmid = $frmid;
      return parent::buildForm($form, $form_state);
   }

   public function submitForm(array &$form, FormStateInterface $form_state){
      $status = TopSearchesModel::deleteAll();

      if($status == 0){
        drupal_set_message(t('Error while deleting'));
      }else{
         drupal_set_message(t('The top search keywords have been deleted'));
         $form_state->setRedirect('system.top_searches');
      }
   } 

}