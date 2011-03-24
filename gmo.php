<?php
/**
 * Based on MonoBook nouveau
 *
 * Authors: Milos Dinic <milos@mozilla-europe.org>
 *          Zbigniew Braniecki <gandalf@mozilla.com>
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
    die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinGMO extends SkinTemplate {
    function initPage( OutputPage $out ) {
        parent::initPage( $out );
        $this->skinname  = 'gmo';
        $this->stylename = 'gmo';
        $this->template  = 'GMOTemplate';

    }

    function setupSkinUserCss( OutputPage $out ) {
        global $wgHandheldStyle;

        parent::setupSkinUserCss( $out );

        // Append to the default screen common & print styles...
        $out->addStyle( 'gmo/style/screen.css', 'screen, projection' );
        $out->addStyle( 'gmo/style/enhanced.css', 'screen, projection' );
        $out->addStyle( 'gmo/style/mediawiki.css', 'screen, projection' );
        $out->addStyle( 'gmo/style/ie.css', 'screen, projection', 'gte IE 6');
    }
}

/**
 * @todo document
 * @ingroup Skins
 */
class GMOTemplate extends QuickTemplate {
    var $skin;
    var $stylepath;
    /**
     * Template filter callback for MonoBook skin.
     * Takes an associative array of data set from a SkinTemplate-based
     * class, and a wrapper for MediaWiki's localization database, and
     * outputs a formatted page.
     *
     * @access private
     */
    function execute() {
        global $wgRequest;
        $this->skin = $skin = $this->data['skin'];
        $this->action = $wgRequest->getText( 'action' );
        $this->stylepath = $this->data['stylepath'].'/'.$this->data['stylename'];
        
        // Suppress warnings to prevent notices about missing indexes in $this->data
        wfSuppressWarnings();
        $this->drawpage();
        wfRestoreWarnings();
    } // end of execute() method

    
    /*************************************************************************************************/
    function drawpage() {
    	$this->doctype();
    	$this->head();
        $this->header();
        $this->body();
        $this->footer();
    }

    /*************************************************************************************************/
    function doctype($html5=false) {
        if ($html5 == true) {
            print('<!DOCTYPE HTML><html xml:lang="'
                .$this->data['lang'].'" lang="'
                .$this->data['lang'].'" dir="'
                .$this->data['dir'].'">');
        } else {
            $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"';
            $doctype .= ' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
            $doctype .= '<html xmlns="'.$this->data['xhtmldefaultnamespace'].'"';
            foreach($this->data['xhtmlnamespaces'] as $tag => $ns) {
                $doctype .= 'xmlns:'.$tag.'="'.$ns.'" ';
            }
            $doctype .= ' xml:lang="'.$this->data['lang'].'" lang="'.$this->data['lang'].'" dir="'.$this->data['dir'].'">';
            print($doctype);
        }
    }
    
    /*************************************************************************************************/
    function head() {
?>
<head>
    <meta http-equiv="Content-Type" content="<?php $this->text('mimetype') ?>; charset=<?php $this->text('charset') ?>" />
    <meta name="keywords" content="web browser, mozilla, firefox, foxfire, camino, thunderbird, bugzilla, user agent, email client, seamonkey, calendar, lightning, open web, better internet" />
    <title><?php $this->text('pagetitle') ?></title>
    <?php $this->html('headlinks') ?>
    <?php $this->html('csslinks') ?>

    <?php print Skin::makeGlobalVariablesScript( $this->data ); ?>
</head>
<?php
    }

    /*************************************************************************************************/
    function header() {
    	
?>
<body<?php if($this->data['body_ondblclick']) { ?> ondblclick="<?php $this->text('body_ondblclick') ?>"<?php } ?>
<?php if($this->data['body_onload']) { ?> onload="<?php $this->text('body_onload') ?>"<?php } ?>
 class="mediawiki home <?php $this->text('dir') ?> <?php $this->text('pageclass') ?> <?php $this->text('skinnameclass') ?>">
<ul id="skip">
    <li><a href="#q">Skip to Search</a></li>
    <li><a href="#nav">Skip to Navigation</a></li>
    <li><a href="#localnav">Skip to Sub Navigation</a></li>
    <li><a href="#main">Skip to Content</a></li>
</ul>

<div id="header">
    <h1 class="unitPng"><a href="<?=$GLOBALS['wgSitename'];?>"><?=$GLOBALS['wgSitename'];?></a></h1>
    <div id="header-contents">
        <ul id="nav">
            <?php foreach($this->data['content_actions'] as $key => $action) {
            ?><li id="ca-<?php echo htmlspecialchars($key) ?>"
            <?php if($action['class']) { ?>class="<?php echo htmlspecialchars($action['class']) ?>"<?php } ?>
            ><a href="<?php echo htmlspecialchars($action['href']) ?>"><?php
                echo htmlspecialchars($action['text']) ?></a></li><?php
            } ?>
        </ul>
            
        <form action="<?php $this->text('searchaction') ?>" id="quick-search"><div>
	        <input id="q" name="search" type="text" />
            <input type="image" id="quick-search-btn" alt="Search" src="/skins/gmo/img/screen/template/search-submit.png">
        </form>
            
        </div>
    </div>

</div>
    
</div>

<div id="main">
    <?php
        }

        function body() {
            $title = $this->data['title'];
            $mix = $this->extractTOC($this->data['bodytext']);
            $body = $mix[0];
            $toc = $mix[1];
            $menu = $toc?True:False;

            if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php }
    ?>
            <div id="wiki-tools">
                <script type="<?php $this->text('jsmimetype') ?>"> if (window.isMSIE55) fixalpha(); </script>
                <?php foreach ($this->data['sidebar'] as $bar => $cont) {
                if($bar=='navigation') { ?>
                <div class='portlet' id='p-<?php echo htmlspecialchars($bar) ?>'>
                    <h2><?php $out = wfMsg( $bar ); if (wfEmptyMsg($bar, $out)) echo $bar; else echo $out; ?></h2>
                    <div class='pBody'>
                        <ul>
                            <?php foreach($cont as $key => $val) { ?>
                            <li id="<?php echo htmlspecialchars($val['id']) ?>"><a href="<?php echo htmlspecialchars($val['href']) ?>"><?php echo htmlspecialchars($val['text'])?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="portlet" id="p-personal">
                      <h2><?php $this->msg('personaltools') ?></h2>
                      <div class="pBody">
                        <ul>
                        <?php foreach($this->data['personal_urls'] as $key => $item) {
                           ?><li id="pt-<?php echo htmlspecialchars($key) ?>"><a href="<?php
                           echo htmlspecialchars($item['href']) ?>"<?php
                           if(!empty($item['class'])) { ?> class="<?php
                           echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php
                           echo htmlspecialchars($item['text']) ?></a></li><?php
                        } ?>
                        </ul>
                      </div>
                    </div>
                  <?php }} ?>
                </div>
            </div>
            
            <div id="main-content"<?php if($menu)print(' class="with-menu"');?>>              
                <h1 id="page-title"><?php print($title);?></h1>
                <hr>
                <div id="breadcrumbs">
                    <?php
                        if(isset($_SERVER['HTTPS'])) {
                            $protocol = 'https://';
                        } else {$protocol = 'http://';}
                    
                        $this->createBreadcrumbs($protocol); ?>
                </div>
                <h3 id="siteSub"><?php $this->msg('tagline') ?></h3>
                <div id="contentSub"><?php $this->html('subtitle') ?></div>
                <?php if($this->data['undelete']) { ?><div id="contentSub2"><?php     $this->html('undelete') ?></div><?php } ?>
                <?php if($this->data['newtalk'] ) { ?><div class="usermessage"><?php $this->html('newtalk')  ?></div><?php } ?>
                <?php if($this->data['showjumplinks']) { ?><div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#column-one"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div><?php } ?>
                <!-- start content -->
                <?php if($menu)
              $this->toc($toc); ?>
                <?php print $body ?>
                <?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
                <!-- end content -->
                <?php if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); } ?>
                <div class="visualClear"></div>
            </div>

    <?php

        }

        /*************************************************************************************************/
        function footer() {
    ?>
        </div><!-- end #main -->
</div>
<div id="footer-wrap">
    <div class="cols" id="footer">
        <div class="six-col">
	        <a href="/" id="logo-footer"><img src="/skins/gmo/img/screen/template/screen/logo_footer.png"></a>
        </div>
        <div class="col-span">
            <?php 
                echo $this->data['lastmod']; 
                echo "<br>";
                echo $this->data['viewcount']; 
            ?>
        </div>
    </div><!-- end #footer -->
</div>

<?php
    }

    /*************************************************************************************************/
    function toc($toc) {
?>
<div id="localnav">
    <ul class="first">
        <strong>Content</strong><hr>
        <div class="tocbody">
            <ul>
                <?php print($this->hackTOC($toc)) ?>
            </ul>
        </li>
    </ul>
</div>
<?php
    }
    
    /*************************************************************************************************/
    function menu($toc) {
?>
<div id="localnav">
    <ul class="first">
        <li class="first"><a href="http://www.mozilla.org/community/">Community</a>
            <ul>
                <li class="first"><a href="http://www.mozilla.org/community/blogs.html">Blogs and Feeds</a></li>
                <li><a href="/community/chat.html">Chat Servers</a></li>

                <li><a href="http://www.mozilla.org/community/events.html">Events and Parties</a></li>
                <li><a href="http://www.mozilla.org/community/intl/">International Pages</a></li>
                <li><a href="http://www.mozilla.org/community/developer-forums.html">Official Developer Forums</a></li>
                <li><a href="http://www.mozilla.org/community/other-forums.html">Other Community Forums</a></li>
                <li><a href="http://www.mozilla.org/community/social.html">Social Networking</a></li>
                <li><a href="http://www.mozilla.org/community/websites.html">Websites</a></li>

                <li><a href="http://www.mozilla.org/community/wikis.html">Wikis</a></li>
            </ul>
        </li>
    </ul>
</div>
<?php
    }

    function extractTOC(&$body) {
        $toc = '';
        $toc_pattern = '/<table id="toc".*?<\/table>/sim';
        $elems = preg_split($toc_pattern, $body,-1, PREG_SPLIT_OFFSET_CAPTURE);
        if (count($elems)<2)
            return Array($body, $toc);
        
        $toc = substr($body,strlen($elems[0][0]), $elems[1][1]-strlen($elems[0][0]));
        $body = substr($body,0, strlen($elems[0][0])) . substr($body, $elems[1][1]);
        return Array($body, $toc);
    }
    
    function hackTOC(&$toc) {
        $pattern = '/<table id="toc".*?<\/h2><\/div>\s+<ul>/sim';
        $toc = preg_replace($pattern, '', $toc);
        #<li class="toclevel-1"><a href="#Overview"><span class="tocnumber">1</span> <span class="toctext">Overview</span></a></li>
        $pattern = '/<li class="toclevel-[0-9]">/sim';
        $toc = preg_replace($pattern, '<li>', $toc);
        return $toc;        
    }
    
    function createBreadcrumbs($protocol) {
        
        $pieces = explode("/", $_SERVER['REQUEST_URI']);
        unset($pieces[0]); /* remove the first (empty item) from the array) */
        $last_piece = end($pieces); /* define a last item in the array */
        
        /* define url pieces we don't want displayed in breadcrumbs 
           breadcrumb parts will be made of url parts again made by exploding url with `slash`
           when items in this array are inside breadrumb, we'll strip it and update breadcrumb        
        */
        
        $unwanted = array(0 => 'index.php?title=', 1 => '&action=edit', 2 => '&redlink=1');
        
        /* create base url */
        
        $base_url = $protocol;        
        $base_url .= $_SERVER['SERVER_NAME'];
        $base_url = htmlspecialchars($base_url, ENT_QUOTES);
        $item_url = $base_url;
        echo "<a href=\"" . $base_url .  "\">Home</a>";
        
        foreach($pieces as $part) {
        
            /* check if there's unwanted url part */    
            foreach($unwanted as $bogus_part) {
            
                $pos = strpos($part, $bogus_part);
                if($pos !== false) {
                    $part = str_replace($bogus_part, '', $part);
                }
            
            }
            $part = htmlspecialchars($part, ENT_QUOTES);
            $item_url .= "/" . $part;
            $item_url = htmlspecialchars($item_url, ENT_QUOTES);
            echo "&nbsp;&raquo;&nbsp;<a href=\"$item_url\">" . $part . "</a>";  
            
        }
  
    
    }
    
} // end of class


