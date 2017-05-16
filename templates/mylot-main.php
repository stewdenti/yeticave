<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($rates as $value): ?>
        <tr class="rates__item">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?=$value["img_path"]?>" width="54" height="40" alt="Сноуборд">
                </div>
                <h3 class="rates__title"><a href="/lot.php?id=<?=$value["id"]?>"><?=$value["name"]?></a></h3>
            </td>
            <td class="rates__category">
                <?=$value["category"]?>
            </td>
            <td class="rates__timer">
                <div class="timer timer--finishing"><?=$value["end_date"]?></div>
            </td>
            <td class="rates__price">
                <?=$value["price"]?> р
            </td>
            <td class="rates__time">
                <?=formatTime($value["date"])?>
            </td>
        </tr>
        <?php endforeach;?>
        </table>
</section>
