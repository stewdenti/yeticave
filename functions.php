<?php

function connectTemplates ($filename, $data)
{
    if (file_exists($filename)) {

        foreach ($data as $key => $value){
           $data[$key] = protectXSS($value);
        }

        extract($data);
        ob_start();
        include ($filename);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;

    } else {

        return ("");
    }
}

function protectXSS($data) {

    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = protectXSS($value);
        }
        return $data;
    } else {
        return htmlspecialchars($data);
    }

}