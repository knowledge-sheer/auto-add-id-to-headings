<?php  

function utf8_uri_encode( $utf8_string, $length = 0 ) {
	$unicode        = '';
	$values         = array();
	$num_octets     = 1;
	$unicode_length = 0;
	mbstring_binary_safe_encoding();
	$string_length = strlen( $utf8_string );
	reset_mbstring_encoding();
	for ( $i = 0; $i < $string_length; $i++ ) {
		$value = ord( $utf8_string[ $i ] );
		if ( $value < 128 ) {
			if ( $length && ( $unicode_length >= $length ) ) {
				break;
			}
			$unicode .= chr( $value );
			$unicode_length++;
		} else {
			if ( count( $values ) == 0 ) {
				if ( $value < 224 ) {
					$num_octets = 2;
				} elseif ( $value < 240 ) {
					$num_octets = 3;
				} else {
					$num_octets = 4;
				}
			}
			$values[] = $value;
			if ( $length && ( $unicode_length + ( $num_octets * 3 ) ) > $length ) {
				break;
			}
			if ( count( $values ) == $num_octets ) {
				for ( $j = 0; $j < $num_octets; $j++ ) {
					$unicode .= '%' . dechex( $values[ $j ] );
				}
				$unicode_length += $num_octets * 3;
				$values     = array();
				$num_octets = 1;
			}
		}
	}
	return $unicode;
}
/**
 * Set the mbstring internal encoding to a binary safe encoding when func_overload
 * is enabled.
 */
function mbstring_binary_safe_encoding( $reset = false ) {
	static $encodings  = array();
	static $overloaded = null;
	if ( is_null( $overloaded ) ) {
		$overloaded = function_exists( 'mb_internal_encoding' ) && ( ini_get( 'mbstring.func_overload' ) & 2 );
	}
	if ( false === $overloaded ) {
		return;
	}
	if ( ! $reset ) {
		$encoding = mb_internal_encoding();
		array_push( $encodings, $encoding );
		mb_internal_encoding( 'ISO-8859-1' );
	}
	if ( $reset && $encodings ) {
		$encoding = array_pop( $encodings );
		mb_internal_encoding( $encoding );
	}
}
/**
 * Reset the mbstring internal encoding to a users previously set encoding.
 */
function reset_mbstring_encoding() {
	mbstring_binary_safe_encoding( true );
}

function seems_utf8( $str ) {
    mbstring_binary_safe_encoding();
    $length = strlen( $str );
    reset_mbstring_encoding();
    for ( $i = 0; $i < $length; $i++ ) {
        $c = ord( $str[ $i ] );
        if ( $c < 0x80 ) {
            $n = 0; // 0bbbbbbb
        } elseif ( ( $c & 0xE0 ) == 0xC0 ) {
            $n = 1; // 110bbbbb
        } elseif ( ( $c & 0xF0 ) == 0xE0 ) {
            $n = 2; // 1110bbbb
        } elseif ( ( $c & 0xF8 ) == 0xF0 ) {
            $n = 3; // 11110bbb
        } elseif ( ( $c & 0xFC ) == 0xF8 ) {
            $n = 4; // 111110bb
        } elseif ( ( $c & 0xFE ) == 0xFC ) {
            $n = 5; // 1111110b
        } else {
            return false; // Does not match any model
        }
        for ( $j = 0; $j < $n; $j++ ) { // n bytes matching 10bbbbbb follow ?
            if ( ( ++$i == $length ) || ( ( ord( $str[ $i ] ) & 0xC0 ) != 0x80 ) ) {
                return false;
            }
        }
    }
    return true;
}

function create_slug_from_title( $title, $raw_title = '', $context = 'display' ) {
	$title = strip_tags( $title );
	// Preserve escaped octets.
	$title = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title );
	// Remove percent signs that are not part of an octet.
	$title = str_replace( '%', '', $title );
	// Restore octets.
	$title = preg_replace( '|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title );
	if ( seems_utf8( $title ) ) {
		if ( function_exists( 'mb_strtolower' ) ) {
			$title = mb_strtolower( $title, 'UTF-8' );
		}
		$title = utf8_uri_encode( $title, 200 );
	}
	$title = strtolower( $title );
	if ( 'save' == $context ) {
		// Convert nbsp, ndash and mdash to hyphens
		$title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );
		// Convert nbsp, ndash and mdash HTML entities to hyphens
		$title = str_replace( array( '&nbsp;', '&#160;', '&ndash;', '&#8211;', '&mdash;', '&#8212;' ), '-', $title );
		// Convert forward slash to hyphen
		$title = str_replace( '/', '-', $title );
		// Strip these characters entirely
		$title = str_replace(
			array(
				// soft hyphens
				'%c2%ad',
				// iexcl and iquest
				'%c2%a1',
				'%c2%bf',
				// angle quotes
				'%c2%ab',
				'%c2%bb',
				'%e2%80%b9',
				'%e2%80%ba',
				// curly quotes
				'%e2%80%98',
				'%e2%80%99',
				'%e2%80%9c',
				'%e2%80%9d',
				'%e2%80%9a',
				'%e2%80%9b',
				'%e2%80%9e',
				'%e2%80%9f',
				// copy, reg, deg, hellip and trade
				'%c2%a9',
				'%c2%ae',
				'%c2%b0',
				'%e2%80%a6',
				'%e2%84%a2',
				// acute accents
				'%c2%b4',
				'%cb%8a',
				'%cc%81',
				'%cd%81',
				// grave accent, macron, caron
				'%cc%80',
				'%cc%84',
				'%cc%8c',
			),
			'',
			$title
		);
		// Convert times to x
		$title = str_replace( '%c3%97', 'x', $title );
	}
	$title = preg_replace( '/&.+?;/', '', $title ); // kill entities
	$title = str_replace( '.', '-', $title );
	$title = preg_replace( '/[^%a-z0-9 _-]/', '', $title );
	$title = preg_replace( '/\s+/', '-', $title );
	$title = preg_replace( '|-+|', '-', $title );
	$title = trim( $title, '-' );
	return $title;
}

function add_id_to_header_tags( $content ) {

    $pattern = '#(?P<full_tag><(?P<tag_name>h\d)(?P<tag_extra>[^>]*)>(?P<tag_contents>[^<]*)</h\d>)#i';
    if ( preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER ) ) {
        $find = array();
        $replace = array();
        foreach( $matches as $match ) {
            if ( strlen( $match['tag_extra'] ) && false !== stripos( $match['tag_extra'], 'id=' ) ) {
                continue;
            }
            $find[]    = $match['full_tag'];
            $id        = create_slug_from_title( $match['tag_contents'] );
            $id_attr   = sprintf( ' id="%s" class="toc-item"', $id );
            $replace[] = sprintf( '<%1$s%2$s%3$s>%4$s</%1$s>', $match['tag_name'], $match['tag_extra'], $id_attr, $match['tag_contents']);
        }
        $content = str_replace( $find, $replace, $content );
    }
    return $content;
}

?>