<?php

namespace Drupal\top_searches\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\top_searches\TopSearchesModel;

/**
 * Configure example settings for this site.
 */
class TopSearchesAdminForm extends ConfigFormBase {

	/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'top_searches_setting_form';
  }

   /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['system.topsearches'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config('system.topsearches');
      $form = [];
	  
	  $form['top_searches_block'] = [
	      '#type' => 'fieldset',
	      '#title' => t('Top Searches block configuration'),
	  ];

	  $form['top_searches_block']['top_searches_block_items'] = [
	    '#type' => 'textfield',
	    '#maxlength' => 2,
	    '#size' => 2,
	    '#title' => t('Maximum number of items to show in Top searches block'),
	    '#default_value' => $config->get('top_searches_block_items'),
	  ];

	  $form['top_searches_block']['top_searches_show_counters'] = [
	    '#type' => 'radios',
	    '#title' => t('Should counters be presented next to the items in the block?'),
	    '#default_value' => $config->get('top_searches_show_counters'),
	    '#options' => [t('No'), t('Yes')],
	  ];

	  $form['top_searches_list'] = [
	      '#type' => 'fieldset',
	      '#title' => t('Top Searches List'),
	  ];
    
	  $list = TopSearchesModel::top_searches_get_list();
	    if (isset($list) && !empty($list)) {
	      $rows = $default_values = [];
	      foreach ($list as $k => $v) {
	        $rows[$v['qid']] = [$v['q'], $v['counter'],];
	        if ($v['display_status']) {
	          $default_values[$v['qid']] = true;
	        }
	  }

	  $form['top_searches_list']['top_searches'] = [
        '#prefix' => t('There are total @rows searched keywords. Select following keywords to display in \'Top Searches\' block', ['@rows' => TopSearchesModel::top_searches_count_rows()]),
        '#type' => 'tableselect',
        '#empty' => t('No results to select.'),
        '#attributes' => ['id' => 'top-searches-table'],
        '#header' => [t('Keyword'), t('Count')],
        '#options' => $rows,
        '#default_value' => $default_values,
      ];
    }

     $form['top_searches_list']['clear_searches'] = [
	    '#type' => 'submit',
	    '#value' => t('Reset search List'),
	    '#weight' => 10,
	    '#submit' => ['::resetSearchData'],
	    '#attributes' => ['class' => ['button--danger']]
	 ];
    $form['#attached']['library'][] = 'top_searches/datatable';

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function resetSearchData(array $form, FormStateInterface $form_state) {
    if ($form_state->getValue('clear_searches') == 'Reset search List') {
        $form_state->setRedirect('system.top_searches_delete');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
	$this->config('system.topsearches')
	      ->set('top_searches_block_items', $form_state->getValue('top_searches_block_items'))
	      ->set('top_searches_show_counters', $form_state->getValue('top_searches_show_counters'))
	      ->save();

		foreach ($form_state->getValue('top_searches') as $key => $value) {
	      $value = ($value != 0)?1:0;
	      TopSearchesModel::top_searches_update_records($key, 'display_status', $value);
	    }     
	    parent::submitForm($form, $form_state);
  }

  public static function top_searches_count_rows() {
	  $result = TopSearchesModel::getRowCount(); 
	  return $result ? $result : 0;
  }

}