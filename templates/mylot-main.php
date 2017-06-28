<?php
/** @var Bind[] $rates */
?>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php
        foreach ($rates as $bind):
            $lot = $bind->getLot();
        ?>
        <tr class="rates__item">
            <td class="rates__info">
                <div class="rates__img">
                    <img src="<?=$lot->img_path?>" width="54" height="40" alt="Сноуборд">
                </div>
                <h3 class="rates__title"><a href="/lot/show/id/<?=$lot->id?>"><?=$lot->name?></a></h3>
            </td>
            <td class="rates__category">
                <?=$lot->getCategory()->name?>
            </td>
            <td class="rates__timer">
            <?php if ($lot->winner == $userid):?>
                <div class="timer timer--finishing"><?=$lot->end_date?></div>
            <?php else: ?>
                <div class="timer"><?=$lot->end_date?></div>
            <?php endif; ?>
            </td>
            <td class="rates__price">
                <?=$lot->getCurrentBet()?> р
            </td>
            <td class="rates__time">
                <?=formatTime($bind->date)?>
            </td>
        </tr>
        <?php endforeach;?>
        </table>
</section>
