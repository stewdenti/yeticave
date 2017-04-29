
<?php if ($error):?>
<form class="form form--add-lot container form--invalid" action="/add.php" method="post" enctype="multipart/form-data">
<?php else:?>
<form class="form form--add-lot container" action="/add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
<?php endif; ?>
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php if (isset($error["lot-name"])):?>
        <div class="form__item form__item--invalid"> <!-- form__item--invalid -->
        <?php else:?>
        <div class="form__item">
        <?php endif; ?>
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота">
            <?php if (isset($error["lot-name"])):?>
            <span class="form__error"><?=$error["lot-name"]?></span>
            <?php else:?>
            <span class="form__error"></span>
            <?php endif; ?>
        </div>
        <?php if (isset($error["category"])):?>
        <div class="form__item form__item--invalid">
        <?php else:?>
        <div class="form__item">
        <?php endif; ?>
            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <option>Выберите категорию</option>
                <option>Доски и лыжи</option>
                <option>Крепления</option>
                <option>Ботинки</option>
                <option>Одежда</option>
                <option>Инструменты</option>
                <option>Разное</option>
            </select>
            <?php if (isset($error["category"])):?>
            <span class="form__error"><?=$error["category"]?></span>
            <?php else:?>
            <span class="form__error"></span>
            <?php endif; ?>
        </div>
    </div>
    <?php if (isset($error["message"])):?>
    <div class="form__item form__item--wide form__item--invalid">
    <?php else:?>
    <div class="form__item form__item--wide">
    <?php endif; ?>
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" ></textarea>
        <?php if (isset($error["message"])):?>
            <span class="form__error"><?=$error["message"]?></span>
        <?php else:?>
            <span class="form__error"></span>
        <?php endif; ?>
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
            <input class="visually-hidden"  type="file" id="photo2" name="lot-img" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <?php if (isset($error["lot-rate"])):?>
        <div class="form__item form__item--small form__item--invalid">
        <?php else:?>
        <div class="form__item form__item--small">
        <?php endif; ?>
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot-rate" placeholder="0" >
            <?php if (isset($error["lot-rate"])):?>
                <span class="form__error"><?=$error["lot-rate"]?></span>
            <?php else:?>
                <span class="form__error"></span>
            <?php endif; ?>
        </div>
        <?php if (isset($error["lot-step"])):?>
        <div class="form__item form__item--small form__item--invalid">
        <?php else:?>
        <div class="form__item form__item--small">
        <?php endif; ?>
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot-step" placeholder="0" >
            <?php if (isset($error["lot-step"])):?>
                <span class="form__error"><?=$error["lot-step"]?></span>
            <?php else:?>
                <span class="form__error"></span>
            <?php endif; ?>
        </div>
        <?php if (isset($error["lot-date"])):?>
        <div class="form__item form__item--invalid">
        <?php else:?>
        <div class="form__item">
        <?php endif; ?>
            <label for="lot-date">Дата заверщения</label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="20.05.2017" >
            <?php if (isset($error["lot-date"])):?>
                <span class="form__error"><?=$error["lot-date"]?></span>
            <?php else:?>
                <span class="form__error"></span>
            <?php endif; ?>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button" name="send">Добавить лот</button>
</form>