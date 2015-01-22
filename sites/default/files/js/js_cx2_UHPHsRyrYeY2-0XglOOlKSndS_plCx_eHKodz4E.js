//function test(){
//    //alert('value');
//}
//var myVar="<?php echo '5';?>";
//alert(myVar);

//phpVars["value1"]
//phpVars["value2"]
//alert(phpVars);
jQuery.get('page--node--4.tpl.php', setimg);

function setimg(data) {
       jQuery('#imageid').attr( "src", data );
    }
;
