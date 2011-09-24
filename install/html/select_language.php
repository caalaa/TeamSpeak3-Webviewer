<div id="language">
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            Please select your language / Bitte wählen Sie Ihre Sprache</p>
    </div>
    <fieldset>
        <?php 
        
        $languages = $utils->getLanguages();
        
        foreach($languages as $langCode => $langOptions) 
        {
            $xml = simplexml_load_string($langOptions['xml']);  ?>

        <button onclick="javascript: setLang('<?php echo($langCode);?>');"><?php echo($langOptions['lang']); ?></button>

        <?php } ?>  
    </fieldset>

</div>