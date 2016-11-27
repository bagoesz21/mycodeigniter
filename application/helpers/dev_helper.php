<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * Preformatted print_r()
     *
     */
    if ( ! function_exists('dpr'))
    {
        function dpr() {
            $args = func_get_args();
            echo "<pre>";
            foreach ($args as $k => $v) {
                echo "dpr".($k + 1).":\n";
                print_r($v);
                echo "\n";
            }
            echo "</pre>";
        }
    }

    /**
     * Preformatted var_dump()
     */
    if ( ! function_exists('mpr'))
    {
        function mpr() {
            $args = func_get_args();
            echo "<pre>";
            foreach ($args as $k => $v) {
                echo "mpr".($k + 1).":\n";
                var_dump($v);
                echo "\n";
            }
            echo "</pre>";
        }
    }