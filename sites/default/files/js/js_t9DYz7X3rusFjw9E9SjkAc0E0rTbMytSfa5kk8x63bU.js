var $j = jQuery.noConflict();

(function ($j) {
    $j(document).ready(function(){
        var test = Drupal.settings.customers.title;
        alert(test);
    });             
}(jQuery));;
