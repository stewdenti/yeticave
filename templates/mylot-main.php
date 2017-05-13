<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($rates as $value): ?>
        <tr class="rates__item">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?=$value["rates_img"]?>" width="54" height="40" alt="Сноуборд">
                </div>
                <h3 class="rates__title"><a href="/lot.php?id=<?=$value["rates_id"]?>"><?=$value["rates_title"]?></a></h3>
            </td>
            <td class="rates__category">
                <?=$value["rates_category"]?>
            </td>
            <td class="rates__timer">
                <div class="timer timer--finishing"><?=$value["rates_timer"]?></div>
            </td>
            <td class="rates__price">
                <?=$value["rates_price"]?> р
            </td>
            <td class="rates__time">
                <?=formatTime($value["rates_time"])?>
            </td>
        </tr>
        <?php endforeach;?>
        </table>
</section>
