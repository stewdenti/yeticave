<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories_equipment as $value):?>
                <li class="nav__item">
                    <a href="/index.php?id=<?=$value["id"]?>"><?=$value["name"]?></a>
                </li>
            <?php endforeach;?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?=$lot_item['name']?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?=$lot_item['img_path'] ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?=$lot_item["category"]?></span></p>
                <p class="lot-item__description"><?=$lot_item["description"]?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                       <?=$lot_item["end_date"]?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?=$lot_item['price'] ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?php echo $lot_item["price"]+$lot_item["step"]?></span>
                        </div>
                    </div>
                    <?php if (isset($user) && $bind_done): ?>
                    <form class="lot-item__form <?php if ($error):?>form--invalid<?php endif; ?>" action="/lot.php" method="post">
                        <p class="lot-item__form-item  <?php printInvalidItemClass($error, 'cost'); ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="number" name="cost" placeholder="<?php echo $lot_item["price"]+$lot_item["step"]?>">
                            <input type="hidden" name="id" value="<?=$lot_item['id'] ?>">
                            <span class="form__error"><?php if (isset($error["cost"])) {print($error["cost"]); } ?></span>
                        </p>
                        <button type="submit" class="button"  name="send">Сделать ставку</button>
                    </form>
                    <?php endif; ?>
                </div>

                <div class="history">
                    <h3>История ставок (<span><?=count($bets)?></span>)</h3>
                    <!-- заполните эту таблицу данными из массива $bets-->
                    <table class="history__list">
                        <?php foreach ($bets as $key =>$value):?>
                            <tr class="history__item">
                                <td class="history__name"><?=$value["name"]?></td>
                                <td class="history__price"><?=$value["price"]?> р</td>
                                <td class="history__time"><?=formatTime($value["date"]);?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
