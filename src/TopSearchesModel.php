<?php 
namespace Drupal\top_searches;

class TopSearchesModel {
  
  static function top_searches_collect_results($limit) {
     $query = \Drupal::database()->select('top_searches', 'frm')
                  ->fields('frm', array('q','counter'))
                  ->orderBy('counter', 'DESC')
                  ->orderBy('q', 'ASC')
                  ->condition('display_status','1')
                  ->range(0, $limit)
                  ->execute();
     $result = $query->fetchAll(\PDO::FETCH_OBJ);
    return $result;
  }

  static function top_searches_key_exists($keys) {
     $query = \Drupal::database()->select('top_searches', 'frm')
                  ->fields('frm', array('qid'))
                  ->condition('q',$keys)
                  ->execute();
     $result = $query->fetchAll(\PDO::FETCH_OBJ);
     return $result;
  }

  static function top_searches_update_counter($id){
     $result =  \Drupal::database()->update('top_searches')
      ->condition('qid', $id)
      ->expression('counter', 'counter + 1')
      ->execute();
      return $result;
  }

   static function top_searches_add_keys($keys){
      $result = db_insert('top_searches')->fields([
            'q' => $keys,
            'counter' => 1,
          ])->execute();
      return $result;
  }

 
  static function deleteAll(){
      $query = db_query("TRUNCATE {top_searches}");
      if($query)
       return 1;
       else return 0;
  }

  static function top_searches_count_rows() {
    $result = db_query("SELECT COUNT(*) FROM {top_searches}")->fetchField();
    return $result ? $result : '0';
  }
  
   static function top_searches_get_list(){
      $output = [];
      $result = \Drupal::database()->select('top_searches', 'ts')
             ->fields('ts', array('qid','q', 'counter', 'display_status'))
             ->orderBy('counter','DESC')
             ->orderBy('q','ASC')->execute();
      $cnt = 0;
      if($result){
        while ($row = $result->fetchAssoc()) {
          $output[$cnt] = $row;
          $cnt++;
       }
     }
    return $output;
  }

  static function top_searches_update_records($qid, $field, $value){
      $num_updated = \Drupal::database()->update('top_searches')
          ->fields([
            $field => $value
          ])
       ->condition('qid', $qid, '=')
       ->execute();
  }


}