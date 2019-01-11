<?php

/*
 * ------------------------------------------------------------
 * Helper functions
 * ------------------------------------------------------------
*/

function dd($_) {
    
    header('Content-Type: text/html; charset=utf-8');
    dump( $_ , true);
    
}

function dump($_, $die = false)
{
    $db_trace = debug_backtrace();

    $trace_idx = ( $die ) ? 1 : 0;

    $file = str_replace(ABSPATH, "", $db_trace[$trace_idx]["file"]);
    $line = $db_trace[$trace_idx]["line"];

    if( $die ) {
        $garbage = ob_get_clean();
    }

    echo '<div style="background: #F5F2F0; border: 1px solid #ddd; padding: 20px; color: #333; margin: 20px 0">';
    echo 'Dumping at row <span style="background: #e74c3c; color: white; padding:3px; border-radius: 3px; text-shadow: 1px 1px 0 rgba(0, 0, 0, .25);">' . $line . '</span> from <span style="background: #e74c3c; color: white; padding: 3px; border-radius: 3px;text-shadow: 1px 1px 0 rgba(0, 0, 0, .25);">' . $file . '</span><br><br>';
    echo '<pre>';
        
        ob_start();
        
        switch( $_ ) {
            
            case ( $_ === null ) :
            case is_scalar( $_ ) :
                var_dump( $_ );
                break;
            
            default : 
                print_r( $_ );
                break;
        }

        $dump = ob_get_clean();
        prettify( $dump );
    
    echo '</pre>';
    echo '</div>';

    if( $die )
        die();
}

function prettify($_)
{
    $_ = preg_replace("/(\[.*\])/", '<span style="color:#669900; font-size: 16px; text-shadow: 0 1px 0 white;">$1</span>', $_);
    $_ = preg_replace("/(\=>.*)/", '<span style="color:#888; font-size: 16px; text-shadow: 0 1px 0 white;">$1</span>', $_);
    $_ = preg_replace("/(\=>)/", '<span style="color:#333; font-size: 16px; text-shadow: 0 1px 0 white;">$1</span>', $_);
    $_ = preg_replace("/(stdClass Object)/", '<strong style="color: #333">$1</strong>', $_);
    $_ = preg_replace("/(Array)/", '<strong style="color: #E74C3C; font-size: 16px;text-shadow: 0 1px 0 white; ">$1</strong>', $_);
    echo $_;
}
/*
* -------------------------------------------------------------
*/

function is_localhost() {

    if( $_SERVER['HTTP_HOST'] == 'localhost') {
        return true;
    }
    
    if( $_SERVER["REMOTE_ADDR"] == '::1' ) {
        return true;
    }

    if( substr( $_SERVER["REMOTE_ADDR"], 0, 7 ) == '192.168' ) {
        return true;
    }

    if( $_SERVER["REMOTE_ADDR"] == '127.0.0.1' ) {
        return true;
    }
    
    return false;

}

?>