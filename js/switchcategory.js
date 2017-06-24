$(document).ready(function(){
    $(".lots__select").change(function () {
        var selected = $(this).find("option:selected").val();
        $(location).attr('href','/main/show/category/'+selected);
   });
});