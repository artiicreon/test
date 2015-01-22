var $j = jQuery.noConflict();

(function ($j) {
    $j(document).ready(function(){
        var test = Drupal.settings.customers.nid;
        alert(test);
    });             
}(jQuery));;
