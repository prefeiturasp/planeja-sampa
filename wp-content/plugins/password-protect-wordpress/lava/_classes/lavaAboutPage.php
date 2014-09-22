<?php
class lavaAboutPage extends lavaPage
{
    public $multisiteSupport = true;

    function displayPage()
    {
        $gettingStartedItems = array();

        if( $this->_pages( false )->pageExists( "settings" ) )
        {
            $link = $this->_pages( false )->fetchPage( "settings" )->url();

            $gettingStartedItems[] = array(
                "text" => __( "Configure Settings", $this->_framework() ),
                "url" => $link
            );
        }

        if( $this->_pages( false )->pageExists( "skins" ) )
        {
            $link = $this->_pages( false )->fetchPage( "skins" )->url();

            $gettingStartedItems[] = array(
                "text" => __( "Configure Skin", $this->_framework() ),
                "url" => $link
            );
        }

        if( $this->_pages( false )->pageExists( "extensions" ) )
        {
            $link = $this->_pages( false )->fetchPage( "extensions" )->url();

            $gettingStartedItems[] = array(
                "text" => __( "Enable Extensions", $this->_framework() ),
                "url" => $link
            );
        }
        ?>
        <h2><?php _e( "Getting Started", $this->_framework() ) ?></h2>
        <div style="text-align:center;" class="clearfix getting-started">
            <?php foreach( $gettingStartedItems as $item): ?>
            <a href="<?php echo $item[ "url" ]; ?>" class="lava-btn lava-btn-chunk lava-btn-chunk-yellow"><?php echo $item[ "text" ]; ?></a>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
?>