<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories_equipment as $value):?>
                <li class="promo__item promo__item--boards">
                    <a class="promo__link" href="/index.php?id=<?=$value['id']?>"><?=$value['name']?></a>
                </li>
            <?php endforeach;?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
            <select class="lots__select">
                <option>Все категории</option>
                <?php foreach ($categories_equipment as $value):?>
                    <option value="<?=$value['id']?>"><?=$value['name']?></option>
                <?php endforeach;?>
            </select>
        </div>
        <ul class="lots__list">
            <?php foreach ($announcement_list as $key =>$value):if (isset($announcement_list)) {
                    ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="<?=$value['img_path'] ?>" width="350" height="260" alt="Сноуборд">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?=$value['category'] ?></span>
                                <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$value["id"]; ?>"><?=$value['name'] ?></a></h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <?php if ($value["binds_number"]>0): ?>
                                            <span class="lot__amount"><?=$value["binds_number"]?> ставок</span>
                                        <?php else: ?>
                                            <span class="lot__amount">Начальная цена</span>
                                        <?php endif; ?>
                                        <span class="lot__cost"><?=$value['price'] ?><b class="rub">р</b></span>
                                    </div>
                                    <div class="lot__timer timer">
                                        <?=$value["end_date"]?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php
            } endforeach; ?>
        </ul>
    </section>
</main>
