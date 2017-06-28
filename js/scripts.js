$(document).ready(function() {
    $(".lots__select").change(function () {
        var selected = $(this).find("option:selected").val();
        $(location).attr('href','/main/show/category/'+selected);
    });

    if(window.File && window.FileReader && window.FileList && window.Blob) {
        $("#photo2").change(
            function onFileSelect(e) {
                var f = e.target.files[0]; // Первый выбранный файл
                if ( !f.type.match(/image\/(jpeg|jpg|png|gif)/) ) {
                    alert ('Фотография должна быть в формате jpg, png или gif');
                } else {

                var reader = new FileReader();
                var place = $(".preview__img").find("img")[0]; // Сюда покажем картинку
                reader.readAsDataURL(f);
                reader.onload = function(e) { // Как только картинка загрузится
                    place.src = e.target.result;
                    $(".preview").show();
                }
              }
            });
        $(".preview__remove").click(function() {
            $(".preview").hide();
        });
    } else {
      console.warn( "Ваш браузер не поддерживает FileAPI");
    }

});
