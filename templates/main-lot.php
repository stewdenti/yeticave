<?php
/** @var Category[] $categories_equipment */
/** @var Lot $lot_item */
/** @var Bind[] $bets */
?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories_equipment as $value):?>
                <li class="nav__item">
                    <a href="/main/show/category/<?=$value->id?>"><?=$value->name?></a>
                </li>
            <?php endforeach;?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?=$lot_item->name?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?=$lot_item->img_path ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?=$lot_item->getCategory()->name?></span></p>
                <p class="lot-item__description"><?=$lot_item->description?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                       <?=$lot_item->end_date?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?=$lot_item->getCurrentBet() ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?php echo $lot_item->getMinNextBet()?></span>
                        </div>
                    </div>
                    <?php if (isset($user) && $can_make_bet): ?>
                        <form class="lot-item__form <?php if (isset($error)):?>form--invalid<?php endif; ?>" action="/lot/bind/id/<?=$lot_item->id?>" method="post">
                            <p class="lot-item__form-item  <?php printInvalidItemClass($error, 'price'); ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="number" name="price" placeholder="<?php echo $lot_item->getMinNextBet()?>">
                                <input type="hidden" name="lot_id" value="<?=$lot_item->id ?>">
                                <span class="form__error"><?php if (isset($error["price"])) {print($error["price"]); } ?></span>
                            </p>
                            <button type="submit" class="button"  name="AddBindForm">Сделать ставку</button>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="history">
                    <h3>История ставок (<span><?=count($bets)?></span>)</h3>
                    <!-- заполните эту таблицу данными из массива $bets-->
                    <table class="history__list">
                        <?php foreach ($bets as $key => $value):?>
                            <tr class="history__item">
                                <td class="history__name"><?=$value->userName()?></td>
                                <td class="history__price"><?=$value->price?> р</td>
                                <td class="history__time"><?=formatTime($value->date);?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
