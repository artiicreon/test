var $j = jQuery.noConflict();

(function ($j) {
    $j(document).ready(function(){
        var test = Drupal.settings.customers.nid;
        //alert(test);
    });             
}(jQuery));

jQuery(document).ready(function($){
  //alert('hot dog flavored water');
});

//jQuery(document).ready(function($){
//  $('.view-display-id-page .views-row').addClass('fancy-pants');
//  alert('sdfsfd');
//});

Drupal.behaviors.infiniteScrollAddClass = {
  attach: function (context, settings) {
    $('.view-display-id-page .views-row').addClass('fancy-pants');
    alert('5454');
  }
};