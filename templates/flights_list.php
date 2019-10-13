<?php

/* @var $res array */

// echo "<pre>";
// print_r($res);
// echo "</pre>";
?>
<div class="flight-title">
    Flights from <?php echo $res['depCityMain'] ?> to <?php echo $res['arrCityMain'] ?>
</div>

<div class="flights">

    <?php
    foreach ($res['flights'] as $key => $flight) {
        $toFirstPoint = $flight['flightData'][0][0];
        $toStopCount = count($flight['flightData'][0]);
        if($toStopCount > 1) {
            $toLastPoint = end($flight['flightData'][0]);
        } else {
            $toLastPoint = $toFirstPoint;
        }
        $toDuration = \Carbon\Carbon::createFromFormat(\common\Helper::DATE_FORMAT, $toLastPoint['arrTime'])
            ->diffForHumans(\Carbon\Carbon::createFromFormat(\common\Helper::DATE_FORMAT, $toFirstPoint['depTime']), true, true, 2);

        $fromFirstPoint = $flight['flightData'][1][0];
        $fromStopCount = count($flight['flightData'][1]);

        if ($fromStopCount > 1) {
            $fromLastPoint = end($flight['flightData'][1]);
        } else {
            $fromLastPoint = $fromFirstPoint;
        }
        $fromDuration = \Carbon\Carbon::createFromFormat(\common\Helper::DATE_FORMAT, $fromLastPoint['arrTime'])
            ->diffForHumans(\Carbon\Carbon::createFromFormat(\common\Helper::DATE_FORMAT, $fromFirstPoint['depTime']), true, true, 2);


        ?>
        <div class="flight"
             data-offer-number="<?php echo ($key + 1) ?>"
             data-offer-id="<?php echo $flight['offerId'] ?>"
             data-to-first-date="<?php echo $toFirstPoint['depTimeParts']['date'] ?>"
             data-to-first-time="<?php echo $toFirstPoint['depTimeParts']['time'] ?>"
             data-to-last-date="<?php echo $toLastPoint['arrTimeParts']['date'] ?>"
             data-to-last-time="<?php echo $toLastPoint['arrTimeParts']['time'] ?>"
             data-from-first-date="<?php echo $fromFirstPoint['depTimeParts']['date'] ?>"
             data-from-first-time="<?php echo $fromFirstPoint['depTimeParts']['time'] ?>"
             data-from-last-date="<?php echo $fromLastPoint['arrTimeParts']['date'] ?>"
             data-from-last-time="<?php echo $fromLastPoint['arrTimeParts']['time'] ?>"
             data-to-duration="<?php echo $toDuration ?>"
             data-from-duration="<?php echo $fromDuration ?>"
        >
            <div class="f-col-1">
                <span class="count"><?php echo ($key + 1) ?></span>
            </div>

            <div class="f-col-2">
                <div class="price">
                    <?php echo $flight['cost'] ?>
                </div>

                <div class="to"><i class="fa fa-arrow-right"></i> <?php echo $toFirstPoint['depTime'] ?> — <?php echo $toLastPoint['arrTime'] ?>, <i class="fa fa-clock"></i> <?php echo $toDuration ?></div>
                <div class="from"><i class="fa fa-arrow-left"></i> <?php echo $fromFirstPoint['depTime'] ?> — <?php echo $fromLastPoint['arrTime'] ?>, <i class="fa fa-clock"></i> <?php echo $fromDuration ?></div>
            </div>

            <div class="f-col-3">
                <div class="logo se"></div>
                <?php
                if ($toStopCount > 1) {
                    echo '<div class="stoppes">(' . ($toStopCount - 1) . ' stop)</div>';
                } else {
                    echo '<div class="stoppes">(nonstop)</div>';
                }
                ?>
            </div>

        </div>
    <?php
    }
    ?>
    <!-- ! use .inactive class for all flights after choose active flight -->


</div>