<?php

$_BUFFERED_OUTPUT = "";
$_DATA_ON_COUNT = 0;

function on($event, $result, $target = "this", $swap = "outerHTML") {
    global $_BUFFERED_OUTPUT, $_DATA_ON_COUNT, $_FULL_URL;

    if (isset($_GET['data-on']) && $_GET['data-on'] == $_DATA_ON_COUNT) {
        ob_clean();

        echo $result;

        die();
    }

    $this_target = "data-on=\"{$_DATA_ON_COUNT}\"";

    echo $this_target;

    if ($target == "this") {
        $target = "[{$this_target}]";
    }

    $url = url_set_param($_FULL_URL, 'data-on', $_DATA_ON_COUNT);

    $_BUFFERED_OUTPUT .= "
        <script>
            document.querySelector('[{$this_target}]').addEventListener('{$event}', async event => {
                const response = await fetch('{$url}');
                const html = await response.text();

                const targetQuery = '{$target}';

                if (targetQuery == 'none') return;

                let target = event.target.closest(targetQuery);
                if (!target) target = document.querySelector(targetQuery);

                if (!target) return;

                const swap = '{$swap}';

                if (swap == 'outerHTML' || swap == 'outer') target.outerHTML = html;
                else if (swap == 'innerHTML' || swap == 'inner') target.innerHTML = html;
                else if (swap == 'text') target.innerText = html;
                else if (swap == 'afterbegin') target.insertAdjacentHTML('afterbegin', html);
                else if (swap == 'beforebegin') target.insertAdjacentHTML('beforebegin', html);
                else if (swap == 'beforeend') target.insertAdjacentHTML('beforeend', html);
                else if (swap == 'afterend') target.insertAdjacentHTML('afterend', html);
            })
        </script>
    ";

    $_DATA_ON_COUNT++;
}