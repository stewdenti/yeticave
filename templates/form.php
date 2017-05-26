<?php
/** @var Category[] $categories_equipment */
?>
<form class="form form--add-lot container <?php if ($error):?>form--invalid<?php endif; ?>" action="/add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?php printInvalidItemClass($error, 'name'); ?>">
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value="<?php printInputItemValue($lot_item, 'name');?>">
            <span class="form__error"><?php if (isset($error["name"])) {print($error["name"]); } ?></span>
        </div>
        <div class="form__item <?php printInvalidItemClass($error, 'category_id'); ?>">
            <label for="category">Категория</label>
            <select id="category" name="category_id">
                <option value="">Выберите категорию</option>
                <?php foreach ($categories_equipment as $value) {
                    if (isset($lot_item['category_id']) && $lot_item['category_id']==$value->id) {
                        echo "<option value=\"".$value->id."\" selected>".$value->name."</option>";
                    } else {
                        echo "<option value=\"".$value->id."\">".$value->name."</option>";
                    }
                } ?>
            </select>
            <span class="form__error"><?php if (isset($error["category_id"])) {print($error["category_id"]); } ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?php printInvalidItemClass($error, 'description'); ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="description" placeholder="Напишите описание лота" ><?php printInputItemValue($lot_item, 'description');?></textarea>
        <span class="form__error"><?php if (isset($error["description"])) {print($error["description"]); } ?></span>
    </div>
    <div class="form__item form__item--file"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="../img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden"  type="file" id="photo2" name="img_path" value=" ">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?php printInvalidItemClass($error, 'start_price'); ?>">
            <label for="price">Начальная цена</label>
            <input id="price" type="number" name="start_price" placeholder="0" value="<?php printInputItemValue($lot_item, 'start_price');?>">
            <span class="form__error"><?php if (isset($error["start_price"])) {print($error["start_price"]); } ?></span>
        </div>
        <div class="form__item form__item--small <?php printInvalidItemClass($error, 'step'); ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="step" placeholder="0" value="<?php printInputItemValue($lot_item, 'step');?>">
            <span class="form__error"><?php if (isset($error["step"])) {print($error["step"]); } ?></span>
        </div>
        <div class="form__item <?php printInvalidItemClass($error, 'end_date'); ?>">
            <label for="lot-date">Дата заверщения</label>
            <input class="form__input-date" id="lot-date" type="text" name="end_date" placeholder="20.05.2017" value="<?php printInputItemValue($lot_item, 'end_date');?>">
            <span class="form__error"><?php if (isset($error["end_date"])) {print($error["end_date"]); } ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button" name="send">Добавить лот</button>
</form>
