<?php
/**
 * Adsense
 * 
 * @package    Adsense
 * @subpackage Plugin
 * @author     David Mézière <dmeziere@free.fr>
 * @copyright  Copyright (c) 2014 David Mézière <dmeziere@free.fr>
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('DOKU_INC') or die('This script cannot be called by itself.');

defined('DOKU_PLUGIN') or define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

require_once(DOKU_PLUGIN . 'syntax.php');

/**
 * Syntax plugin adsense
 */
class syntax_plugin_adsense extends DokuWiki_Syntax_Plugin
{
    /**
     * General info.
     * 
     * @see /inc/plugin.php
     * 
     * @return array Associative array.
     */
    function getInfo()
    {
        return array(
            'base'   => 'adsense',
            'author' => 'David Mézière',
            'email'  => 'dmeziere@free.fr',
            'date'   => '2014-11-28',
            'name'   => 'Google AdSense Plugin',
            'desc'   => 'Displays Google AdSense Javascripts.',
            'url'    => 'https://github.com/dmeziere/dokuwiki-plugin-adsense',
        );
    }

    /**
     * Syntax type.
     * 
     * @see /inc/parser/parser.php
     * 
     * @return string One of the $PARSER_MODES.
     */
    function getType()
    {
        return 'substition';
    }

    /**
     * Paragraph type.
     * 
     * @see /lib/plugins/syntax.php
     * 
     * @return string One of normal / block / stack.
     */
    function getPType()
    {
        return 'block';
    }

    /**
     * @return integer Sort order of this plugin.
     */
    function getSort()
    {
        return 161;
    }

    /**
     * Connect pattern to lexer.
     * 
     * @param string $mode Input type.
     * 
     * @return none.
     */
    function connectTo($mode)
    {
        $this->Lexer->addSpecialPattern('^\{ADSENSE \d+\}$', $mode, 'plugin_adsense');
    }

    /**
     * Handle the match.
     * 
     * @param string $match The text matched by the patterns.
     * @param integer $state The lexer state for the match.
     * @param integer $pos The character position of the matched text.
     * @param Doku_Handler $handler The Doku_Handler object.
     * 
     * @return array All data you want to use in render.
     */
    function handle($match, $state, $pos, &$handler)
    {
        $data = array();

        // Get the numeric part of the tag.
        preg_match("/(?P<slot>\d+)/", $match, $data);

        return array(
            'slot' => $data['slot']
        );
    }

    /**
     * Handles the actual output creation.
     * 
     * @todo Display an error message if client or slot are incorrect.
     * 
     * @param string $format Output format being rendered.
     * @param Doku_Renderer $renderer The current renderer object.
     * @param array $data Data created by handler().
     * 
     * @return boolean Rendered correctly?
     */
    function render($format, &$renderer, $data)
    {
        // Restriction to anonymous users
        if ($this->getConf('adsense_restrict') === 1 && isset($_SERVER['REMOTE_USER'])) {
            return true;
        }
        
        // Ads display when format is xhtml.
        if ($format === 'xhtml') {
            $renderer->doc .= $this->_adsense(
                $this->getConf('adsense_client'),
                $data['slot']
            );
        }
        
        return true;
    }

    /**
     * @param string $client Google AdSense client ID.
     * @param string $slot Google AdSense slot ID.
     * 
     * @return string Generated text/html.
     */
    function _adsense($client, $slot)
    {
        $result  = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>'.PHP_EOL;
        $result .= '<!-- 468x60, date de création 07/06/10 -->'.PHP_EOL;
        $result .= '<ins class="adsbygoogle"'.PHP_EOL;
        $result .= '     style="display:inline-block;width:468px;height:60px"'.PHP_EOL;
        $result .= '     data-ad-client="' . $client . '"'.PHP_EOL;
        $result .= '     data-ad-slot="' . $slot . '"></ins>'.PHP_EOL;
        $result .= '<script>'.PHP_EOL;
        $result .= '(adsbygoogle = window.adsbygoogle || []).push({});'.PHP_EOL;
        $result .= '</script>'.PHP_EOL;
        
        return $result;
    }
    
}
