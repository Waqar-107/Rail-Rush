<?php
/**
 * Created by PhpStorm.
 * User: waqar hassan khan
 * Date: 1/3/2018
 * Time: 9:52 PM
 */

function drawingSeat($ch,$x,$temp, $sold)
{
    for ($i = 0; $i < 5; $i++)
    {
        for ($j = 1; $j <= 4; $j++)
        {
            $id = $ch.'C' . ($temp + 1);
            $idx=$temp+$x;

            if ($sold[$idx] == 0)
                echo '<button class="btn btn-outline-success" id=' . $id . '>' . ($temp + 1) . '</button>';
            else if ($sold[$idx] % 10 == 1)
                echo '<button class="btn btn-warning" type="button" disabled id=' . $id . '>' . ($temp + 1) . '</button>';
            else
                echo '<button class="btn btn-danger" type="button" disabled id=' . $id . '>' . ($temp + 1) . '</button>';

            $temp++;

            if ($j == 2)
                echo '      ';
            else
                echo '  ';
        }

        echo '</br></br>';
    }
}