<?php
/** @var Bind[] $rates */
?>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php
        foreach ($rates as $value):
            $lot = $value->getLot();
        ?>
        <tr class="rates__item">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?=$lot->img_path?>" width="54" height="40" alt="Сноуборд">
                </div>
                <h3 class="rates__title"><a href="/lot.php?id=<?=$lot->id?>"><?=$lot->name?></a></h3>
            </td>
            <td class="rates__category">
                <?=$lot->getCategory()->name?>
            </td>
            <td class="rates__timer">
                <div class="timer timer--finishing"><?=$lot->end_date?></div>
            </td>
            <td class="rates__price">
                <?=$lot->getCurrentBet()?> р
            </td>
            <td class="rates__time">
                <?=formatTime($lot->end_date)?>
            </td>
        </tr>
        <?php endforeach;?>
        </table>
</section>
