(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.customMap = {
        attach: function (context, settings) {
           if($('#top-searches-table').length){
              $('#top-searches-table').DataTable();
           }
        }
    };
    
})(jQuery, Drupal, drupalSettings);