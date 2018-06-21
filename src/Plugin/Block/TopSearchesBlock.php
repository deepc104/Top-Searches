<?php
namespace Drupal\top_searches\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\top_searches\TopSearchesModel;
use Drupal\Core\Form\ConfigFormBase;

/**
 * @Block(
 *   id = "top_searches",
 *   admin_label = @Translation("Top Searches"),
 *   category = @Translation("Top Searches"),
 * )
 */
class TopSearchesBlock extends BlockBase {
  public function build() {
      $build = [
      '#theme' => 'top_searches_block',
      ];
        $config = \Drupal::config('system.topsearches');
        $build['#show_counts'] = $config->get('top_searches_show_counters');
       
        $limit= $config->get('top_searches_block_items');
        $top_searches = TopSearchesModel::top_searches_collect_results($limit);

        if (count($top_searches)) {
          $keys = array();
          foreach($top_searches as $key => $searchq){
            $build['#keys'][$key] = [
              'key' => $searchq->q,
              'count' => $searchq->counter,
            ];
          }
        }

     return $build;
  }
}
