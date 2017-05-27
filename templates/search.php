<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories_equipment as $value):?>
                <li class="nav__item">
                    <a href="/index.php?id=<?=$value->id?>"><?=$value->name?></a>
                </li>
            <?php endforeach;?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?=$search_string?></span>»</h2>
            <ul class="lots__list">
                <?php foreach ($announcement_list as $key => $value):?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?=$value->img_path ?>" width="350" height="260" alt="Сноуборд">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?=$value->getCategory()->name ?></span>
                            <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?=$value->id; ?>"><?=$value->name?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <?php
                                    $bindsNumber = count($value->getBinds());
                                    if ($bindsNumber > 0):
                                        ?>
                                        <span class="lot__amount"><?=$bindsNumber?> ставок</span>
                                    <?php else: ?>
                                        <span class="lot__amount">Начальная цена</span>
                                    <?php endif; ?>
                                    <span class="lot__cost"><?=$value->start_price ?><b class="rub">р</b></span>
                                </div>
                                <div class="lot__timer timer">
                                    <?=$value->end_date?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
<!--        <ul class="pagination-list">-->
<!--            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>-->
<!--            <li class="pagination-item pagination-item-active"><a>1</a></li>-->
<!--            <li class="pagination-item"><a href="#">2</a></li>-->
<!--            <li class="pagination-item"><a href="#">3</a></li>-->
<!--            <li class="pagination-item"><a href="#">4</a></li>-->
<!--            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>-->
<!--        </ul>-->
    </div>
</main>