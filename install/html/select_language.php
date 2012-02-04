<?php /**
 *  This file is part of devMX TeamSpeak3 Webviewer.
 *  Copyright (C) 2011 - 2012 Max Rath and Maximilian Narr
 *
 *  devMX TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with devMX TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */ ?>
<div class="center" style="margin-top: 80px; margin-bottom: 20px;"><img src="../html/welcome/webviewer-logo.png" alt="" /></div>
<div id="language">
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            Please select your language / Bitte wählen Sie Ihre Sprache</p>
    </div>
    <fieldset>
        <?php
        $languages = $utils->getLanguages();

        foreach ($languages as $langCode => $langOptions) :
            ?>
            <button onclick="javascript: setLang('<?php echo($langCode); ?>');"><?php echo($langOptions['lang']); ?></button>
        <?php endforeach; ?>  
    </fieldset>
</div>